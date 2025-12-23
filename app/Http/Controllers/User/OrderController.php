<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $order->load('items');

        // 3. Tampilkan view detail
        return view('pages.user.orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        // 1. Validasi Kepemilikan (Security)
        if ($order->user_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // 2. Validasi Status (Hanya boleh batal jika belum diproses/dikirim)
        // Status yang BOLEH dibatalkan: 'pending' (belum bayar) atau 'unpaid'
        if ($order->status == 'shipped' || $order->status == 'completed' || $order->status == 'processing') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses/dikirim.');
        }

        // 3. Update Status
        $order->status = 'cancelled';
        $order->save();

        // (Opsional) Jika Anda pakai stok management, kembalikan stok produk disini
        // foreach($order->items as $item) {
        //    $product = $item->product;
        //    $product->stok += $item->quantity;
        //    $product->save();
        // }

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }


    public function paymentFinish(Request $request)
    {
        // 1. Ambil Order ID dari URL (dikirim oleh Midtrans / JS kita)
        $orderId = $request->query('order_id');

        $order = Order::where('order_number', $orderId)->firstOrFail();

        // 2. Konfigurasi Midtrans (Wajib di-set ulang disini)
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // 3. Cek Status Transaksi ke Midtrans (Bukan percaya data browser)
        try {
            $status = Transaction::status($orderId);
            $transactionStatus = $status->transaction_status;
            $fraudStatus = $status->fraud_status;

            // 4. Logika Update Status (Mirip Webhook)
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $order->update(['payment_status' => 'challenge']);
                } else {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                        'payment_type' => $status->payment_type // Simpan jenis pembayaran
                    ]);
                }
            } else if ($transactionStatus == 'settlement') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'payment_type' => $status->payment_type
                ]);
            } else if ($transactionStatus == 'pending') {
                $order->update(['payment_status' => 'unpaid']);
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $order->update(['payment_status' => 'failed', 'status' => 'cancelled']);
            }

            return redirect()->route('orders.show', $order->id)->with('success', 'Status pembayaran berhasil diperbarui!');
        } catch (\Exception $e) {
            // Jika error (misal order id ga ketemu di midtrans), kembalikan saja
            return redirect()->route('orders.show', $order->id)->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    public function checkStatus(Order $order)
    {
        // 1. Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        try {
            // 2. Cek Status ke Midtrans berdasarkan Order Number (INV-...)
            $status = Transaction::status($order->order_number);

            $transactionStatus = $status->transaction_status;
            $fraudStatus = $status->fraud_status;

            // 3. Logika Update Database (Sama seperti sebelumnya)
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $order->update(['payment_status' => 'challenge']);
                } else {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                        'payment_type' => $status->payment_type
                    ]);
                }
            } else if ($transactionStatus == 'settlement') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'payment_type' => $status->payment_type
                ]);
            } else if ($transactionStatus == 'pending') {
                $order->update(['payment_status' => 'unpaid']);
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $order->update(['payment_status' => 'failed', 'status' => 'cancelled']);
            }

            // 4. Redirect kembali dengan pesan
            if ($order->payment_status == 'paid') {
                return back()->with('success', 'Status berhasil diperbarui! Pembayaran Lunas.');
            } else {
                return back()->with('info', 'Status diperbarui: Belum ada pembayaran masuk.');
            }
        } catch (\Exception $e) {
            // Jika error (misal belum ada transaksi sama sekali di Midtrans)
            return back()->with('error', 'Belum ada transaksi yang ditemukan untuk pesanan ini.');
        }
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
