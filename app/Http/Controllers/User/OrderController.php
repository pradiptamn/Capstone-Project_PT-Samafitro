<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Transaction;
use Midtrans\Config;

class OrderController extends Controller
{
    public function index()
    {
        // Ambil order milik user yang sedang login, urutkan dari yang terbaru
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pages.user.orders.index', compact('orders'));
    }

    /**
     * Menampilkan Detail Pesanan & Tombol Bayar
     */
    public function show(Order $order)
    {
        // 1. Keamanan: Pastikan user hanya bisa melihat order miliknya sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Load relasi item produk agar bisa ditampilkan
        $order->load(['items.product' => function ($query) {
            $query->withTrashed();
        }]);

        // 3. Tampilkan view detail
        return view('pages.user.orders.show', compact('order'));
    }

    public function paymentFinish(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = Order::where('order_number', $orderId)->with('items.product')->firstOrFail();

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        try {
            $status = Transaction::status($orderId);
            $this->syncOrderAndStock($order, $status); // Gunakan helper agar logika sama

            return redirect()->route('orders.show', $order->id)->with('success', 'Status pembayaran berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('orders.show', $order->id)->with('error', 'Gagal memverifikasi: ' . $e->getMessage());
        }
    }

    public function checkStatus(Order $order)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        try {
            $order->load('items.product');
            $status = Transaction::status($order->order_number);
            $this->syncOrderAndStock($order, $status);

            if ($order->payment_status == 'paid') {
                return back()->with('success', 'Status berhasil diperbarui! Pembayaran Lunas.');
            } else {
                return back()->with('info', 'Status diperbarui: Belum ada pembayaran masuk.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Belum ada transaksi ditemukan.');
        }
    }

    /**
     * HELPER LOGIKA: Sinkronisasi Status Pembayaran & Stok Fisik
     * Dibuat private agar tidak bisa diakses dari Route, hanya internal Controller
     */
    private function syncOrderAndStock($order, $status)
    {
        $isPaid = ($status->transaction_status == 'settlement' || ($status->transaction_status == 'capture' && $status->fraud_status != 'challenge'));

        if ($isPaid && $order->payment_status != 'paid') {
            // Cukup update status saja, karena stok sudah dipotong saat checkout
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'payment_type' => $status->payment_type
            ]);
        } else if (in_array($status->transaction_status, ['deny', 'expire', 'cancel'])) {
            $order->update(['payment_status' => 'failed', 'status' => 'cancelled']);
        }
    }

    public function cancel(Order $order)
    {
        if ($order->user_id != auth()->id()) abort(403);
        if ($order->status != 'pending') return back()->with('error', 'Pesanan sudah diproses dan tidak dapat dibatalkan.');

        DB::transaction(function () use ($order) {
            // KEMBALIKAN STOK
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->increment('stok', $item->quantity);
            }
            $order->update(['status' => 'cancelled']);
        });

        return back()->with('success', 'Pesanan dibatalkan & stok dikembalikan.');
    }

    public function downloadInvoice(Order $order)
    {
        // 1. Security Check: Pastikan order milik user yang login
        if ($order->user_id != auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Load View PDF
        $pdf = Pdf::loadView('pages.user.orders.invoice', compact('order'));

        // 3. Set ukuran kertas (A4 Portrait standar surat)
        $pdf->setPaper('a4', 'portrait');

        // 4. Download file dengan nama dinamis
        return $pdf->download('Invoice-' . $order->order_number . '.pdf');
    }
}
