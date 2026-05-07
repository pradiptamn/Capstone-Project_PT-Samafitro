<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DashboardItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());
        $trendType = $request->input('trend_type', 'daily');

        // --- A. Statistik Utama ---
        $globalRevenue = Order::where('payment_status', 'paid')->sum('total_price');
        $filteredRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->sum('total_price');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'paid')->count();

        // --- B. Tren Pendapatan (Logika Harian/Mingguan/Bulanan) ---
        $queryTrend = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        $revenueTrend = match ($trendType) {
            'weekly' => $queryTrend->select(DB::raw("CONCAT('W', WEEK(created_at)) as label"), DB::raw("SUM(total_price) as revenue"), DB::raw("YEARWEEK(created_at) as sort_key"))->groupBy('label', 'sort_key')->orderBy('sort_key')->get(),
            'monthly' => $queryTrend->select(DB::raw("DATE_FORMAT(created_at, '%M %Y') as label"), DB::raw("SUM(total_price) as revenue"), DB::raw("DATE_FORMAT(created_at, '%Y-%m') as sort_key"))->groupBy('label', 'sort_key')->orderBy('sort_key')->get(),
            default => $queryTrend->select(DB::raw("DATE_FORMAT(created_at, '%d %b') as label"), DB::raw("SUM(total_price) as revenue"), DB::raw("DATE(created_at) as sort_key"))->groupBy('label', 'sort_key')->orderBy('sort_key')->get(),
        };

        // --- C. Produk Terlaris & Logika Restock (MENGGUNAKAN STOK) ---
        $topSelling = OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->where('payment_status', 'paid')
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            })
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // ==========================================
        // D. DEAD STOCK (Logika: Ada stok tapi tidak laku)
        // ==========================================
        $soldProductIds = OrderItem::whereHas('order', function ($q) use ($startDate, $endDate) {
            $q->where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        })->pluck('product_id')->unique()->toArray();

        $deadStock = Product::with('category')
            ->whereNotIn('id', $soldProductIds)
            ->where('stok', '>', 0) // Dead stock = barang yang menumpuk di gudang
            ->orderByDesc('stok')    // Urutkan dari sisa stok terbanyak
            ->limit(10)->get();

        // ==========================================
        // E. TABEL PRIORITAS RESTOCK (Logika: Berdasarkan Stok Rendah/Habis)
        // ==========================================
        $restockPriority = Product::where('stok', '<=', 10) // Ambil yang stoknya kritis/menipis
            ->withSum(['orderItems as total_sold' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($q) use ($startDate, $endDate) {
                    $q->where('payment_status', 'paid')
                        ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                });
            }], 'quantity')
            ->orderBy('stok', 'asc')       // Stok 0 wajib paling atas
            ->orderByDesc('total_sold')    // Lalu yang paling laku secara historis
            ->limit(10)->get();

        $restockPriority->map(function ($item) {
            $item->current_stock = $item->stok;
            $item->total_sold = $item->total_sold ?? 0;
            $item->priority = match (true) {
                $item->stok <= 3 => 'Sangat Tinggi',
                $item->stok <= 10 => 'Tinggi',
                default => 'Normal',
            };
            return $item;
        });

        // --- F. Pie Chart (Distribusi Kategori) ---
        $categoryDistribution = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.kategori_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select('categories.name', DB::raw('SUM(order_items.quantity) as total'))
            ->groupBy('categories.name')->get();

        // --- G. Performa Sales (Berdasarkan created_by) ---
        $salesPerformance = Order::join('users', 'orders.created_by', '=', 'users.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select('users.name', DB::raw('SUM(orders.total_price) as total_revenue'), DB::raw('COUNT(orders.id) as total_deals'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_revenue')
            ->get();

        if ($request->ajax()) {
            // BARU: Jika ada permintaan detail sales
            if ($request->has('sales_name')) {
                // $details = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                //     ->join('users', 'orders.created_by', '=', 'users.id')
                //     ->where('users.name', $request->sales_name)
                //     ->where('orders.payment_status', 'paid')
                //     ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                //     ->select(
                //         'order_items.product_name',
                //         DB::raw('SUM(order_items.quantity) as total_qty'),
                //         DB::raw('SUM(order_items.subtotal) as total_amount') // Tambahkan prefix order_items.
                //     )
                //     ->groupBy('order_items.product_id', 'order_items.product_name') // Tambahkan prefix untuk keamanan
                //     ->orderByDesc('total_qty')
                //     ->get();

                // return response()->json($details);
                $details = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->join('users', 'orders.created_by', '=', 'users.id')
                    ->where('users.name', $request->sales_name)
                    ->where('orders.payment_status', 'paid')
                    ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->select(
                        'order_items.product_name',
                        'order_items.quantity',
                        'order_items.subtotal',
                        'orders.order_number',   // Tambah Nomor Invoice
                        'orders.created_at'      // Tambah Waktu Transaksi
                    )
                    ->orderByDesc('orders.created_at') // Urutkan dari yang terbaru
                    ->get();

                return response()->json($details);
            }

            return response()->json([
                'stats' => [
                    'filteredRevenue' => number_format($filteredRevenue, 0, ',', '.'),
                    'globalRevenue' => number_format($globalRevenue, 0, ',', '.'),
                    'totalOrders' => $totalOrders,
                    'pendingOrders' => $pendingOrders,
                ],
                'charts' => [
                    'revenue' => ['labels' => $revenueTrend->pluck('label'), 'data' => $revenueTrend->pluck('revenue')],
                    'topSelling' => ['labels' => $topSelling->pluck('product_name'), 'data' => $topSelling->pluck('total_sold')],
                    'pie' => ['labels' => $categoryDistribution->pluck('name'), 'data' => $categoryDistribution->pluck('total')],
                    'deadStock_detail' => $deadStock->map(fn($p) => [
                        'nama_produk' => $p->nama_produk,
                        'kategori' => $p->category->name ?? '-',
                        'harga' => $p->harga,
                        'stok' => $p->stok
                    ]),
                    'salesPerformance' => [
                        'labels' => $salesPerformance->pluck('name'),
                        'data' => $salesPerformance->pluck('total_revenue'),
                        'deals' => $salesPerformance->pluck('total_deals')
                    ],
                ],
                'table' => $restockPriority
            ]);
        }

        $dashboardItems = DashboardItem::latest()->get();
        return view('pages.admin.dashboard', compact(
            'globalRevenue',
            'filteredRevenue',
            'revenueTrend',
            'totalOrders',
            'pendingOrders',
            'topSelling',
            'deadStock',
            'salesPerformance',
            'categoryDistribution',
            'restockPriority',
            'dashboardItems',
            'startDate',
            'endDate',
            'trendType'
        ));
    }

    // --- LOGIKA CRUD KONTEN DASHBOARD ---
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('dashboard', 'public');
        }

        DashboardItem::create($data);
        return redirect()->route('admin.dashboard')->with('success', 'Konten berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $item = DashboardItem::findOrFail($id);
        if ($item->gambar) {
            Storage::disk('public')->delete($item->gambar);
        }
        $item->delete();
        return back()->with('success', 'Konten berhasil dihapus.');
    }

    // --- LOGIKA EXPORT ---
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        // 1. Query untuk mengambil Item (Untuk List Tabel)
        $data = OrderItem::with('order')
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->where('payment_status', 'paid')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->get();

        // 2. Query untuk mengambil Order (Untuk Hitung Asuransi & Total Global)
        $orders = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate]);

        // 3. Kalkulasi Angka
        $productRevenue = $data->sum('subtotal');       // Total Harga Barang Saja
        $insuranceRevenue = $orders->sum('insurance_fee'); // Total Asuransi Saja
        $totalRevenue = $orders->sum('total_price');    // Total Keseluruhan (Barang + Asurnasi)
        $totalItems = $data->sum('quantity');

        // 4. Kirim semua variabel ke View
        $pdf = Pdf::loadView('pages.admin.reports.pdf', compact(
            'data',
            'productRevenue',
            'insuranceRevenue',
            'totalRevenue',
            'totalItems',
            'startDate',
            'endDate'
        ));

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Penjualan.pdf');
    }

    public function exportExcel(Request $request)
    {
        // Ubah startOfYear ke startOfMonth
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        return Excel::download(new SalesExport($startDate, $endDate), 'Laporan-Penjualan.xlsx');
    }
}
