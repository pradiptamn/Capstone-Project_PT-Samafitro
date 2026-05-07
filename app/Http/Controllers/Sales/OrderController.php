<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Menampilkan Form Input Pesanan oleh Sales
     */
    public function create()
    {
        // Ambil produk yang masih memiliki stok
        $products = Product::where('stok', '>', 0)->get();
        return view('pages.sales.create_order', compact('products'));
    }

    /**
     * API untuk Fitur Suggestion/Autofill Pelanggan
     */
    public function searchUsers(Request $request)
    {
        $search = $request->get('q');
        $users = User::where('role', 'user')
            ->where('email', 'like', "%$search%")
            ->select('id', 'name', 'email', 'phone')
            ->limit(5)
            ->get();

        return response()->json($users);
    }

    /**
     * Proses Simpan Pesanan (Langsung Lunas)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email' => 'required|email',
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.qty' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // 2. LOGIKA QUICK REGISTER / FIND USER
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                $user = User::create([
                    'id' => (string) Str::uuid(),
                    'name' => $request->customer_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'role' => 'user',
                    'password' => Hash::make('samafitro123'),
                ]);
            }

            // 3. HITUNG TOTAL & LOCKING STOK (Mirip CheckoutController)
            $totalPrice = 0;
            $orderItemsData = [];

            foreach ($request->products as $item) {
                // Gunakan lockForUpdate agar stok tidak bentrok dengan pembeli online
                $product = Product::where('id', $item['id'])->lockForUpdate()->first();

                if (!$product || $product->stok < $item['qty']) {
                    throw new \Exception("Maaf, stok '{$product->nama_produk}' tidak mencukupi. Sisa: " . ($product->stok ?? 0));
                }

                $subtotalItem = $product->harga * $item['qty'];
                $totalPrice += $subtotalItem;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->nama_produk,
                    'price' => $product->harga,
                    'quantity' => $item['qty'],
                    'subtotal' => $subtotalItem,
                ];

                // Kurangi stok
                $product->decrement('stok', $item['qty']);
            }

            // 4. SIMPAN ORDER (Sesuai Kolom Database: image_b5202e.jpg)
            $order = Order::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user->id,
                'created_by' => auth()->id(), // Mencatat Sales yang bertugas
                'order_number' => Order::generateOrderNumber(),
                'shipping_address' => 'In-Store Purchase (Input by Sales)',
                'shipping_phone' => $request->phone,
                'subtotal' => $totalPrice,
                'total_price' => $totalPrice,
                'status' => 'paid',            // Skenario: Langsung bayar
                'payment_status' => 'paid',    // Skenario: Langsung bayar
                'payment_type' => 'cash',      // Pembayaran Tunai/On-the-spot
            ]);

            // 5. SIMPAN ITEM PESANAN
            foreach ($orderItemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }

            DB::commit();

            return redirect()->route('sales.orders.index')->with('success', "Pesanan Berhasil! Akun untuk {$user->name} telah aktif.");
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage())->withInput();
        }
    }
}
