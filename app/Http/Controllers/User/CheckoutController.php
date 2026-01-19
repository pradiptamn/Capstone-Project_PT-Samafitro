<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    /**
     * Tampilkan Halaman Input Alamat & Ringkasan Biaya
     */
    public function showShippingForm()
    {
        $user = Auth::user();

        $cartItems = CartItem::where('user_id', $user->id)->with(['product' => function ($q) {
            $q->withTrashed();
        }])->get();

        foreach ($cartItems as $item) {
            if (!$item->product || $item->product->trashed()) {
                return back()->with('error', "Produk '{$item->product->nama_produk}' sudah tidak tersedia. Silakan hapus dari keranjang untuk melanjutkan.");
            }
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('produk.user')->with('error', 'Keranjang belanja kosong.');
        }

        // Hitung Subtotal
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->harga * $item->quantity;
        }

        // PANGGIL DARI CONFIG (Bukan ditulis manual 20000 lagi)
        $insuranceFee = config('samafitro.insurance_fee');

        $total = $subtotal + $insuranceFee;

        return view('pages.user.checkout', compact('cartItems', 'subtotal', 'insuranceFee', 'total', 'user'));
    }

    /**
     * Proses Simpan Order ke Database
     */
    public function processCheckout(Request $request)
    {
        // 1. Validasi Input Alamat dari Form
        $request->validate([
            'address' => 'required|string|max:500',
            'phone'   => 'required|string|max:20',
            'note'    => 'nullable|string|max:200',
        ]);

        $user = Auth::user();

        try {
            DB::beginTransaction();

            $cartItems = CartItem::where('user_id', $user->id)->with(['product' => function ($q) {
                $q->withTrashed();
            }])->get();

            foreach ($cartItems as $item) {
                if (!$item->product || $item->product->trashed()) {
                    return back()->with('error', "Produk '{$item->product->nama_produk}' sudah tidak tersedia. Silakan hapus dari keranjang untuk melanjutkan.");
                }
            }

            if ($cartItems->isEmpty()) return back()->with('error', 'Keranjang kosong');

            // --- VALIDASI & LOCKING STOK ---
            foreach ($cartItems as $item) {
                // lockForUpdate memastikan tidak ada proses lain yang mengubah baris produk ini sampai transaksi selesai
                $product = Product::where('id', $item->product_id)->lockForUpdate()->first();

                if (!$product || $product->stok < $item->quantity) {
                    DB::rollBack();
                    return back()->with('error', "Maaf, stok '{$item->product->nama_produk}' tidak mencukupi. Sisa: {$product->stok}");
                }

                $product->decrement('stok', $item->quantity);
            }

            // --- LANJUT BUAT ORDER JIKA STOK AMAN ---
            $subtotal = $cartItems->sum(fn($item) => $item->product->harga * $item->quantity);
            $appliedInsuranceFee = $request->has('use_insurance') ? config('samafitro.insurance_fee') : 0;
            $grandTotal = $subtotal + $appliedInsuranceFee;

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'shipping_address' => $request->address,
                'shipping_phone' => $request->phone,
                'note' => $request->note,
                'subtotal' => $subtotal,
                'insurance_fee' => $appliedInsuranceFee,
                'total_price' => $grandTotal,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->nama_produk,
                    'price' => $item->product->harga,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->product->harga * $item->quantity,
                ]);
            }

            CartItem::where('user_id', $user->id)->delete();

            // 1. Konfigurasi Midtrans
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

            // --- BARU: SIAPKAN ITEM DETAILS ---
            $item_details = [];

            // A. Masukkan Produk
            foreach ($order->items as $item) { // Kita ambil dari relasi order->items yang baru dibuat
                $item_details[] = [
                    'id'       => $item->product_id,
                    'price'    => (int) $item->price, // Wajib Integer
                    'quantity' => (int) $item->quantity,
                    'name'     => substr($item->product_name, 0, 50), // Batasi nama max 50 karakter agar aman
                ];
            }

            // B. Konfigurasi Midtrans Item Details
            if ($order->insurance_fee > 0) {
                $item_details[] = [
                    'id'       => 'INSURANCE-FEE',
                    'price'    => (int) $order->insurance_fee, // Pakai kolom baru
                    'quantity' => 1,
                    'name'     => 'Biaya Layanan & Asuransi',
                ];
            }

            // 2. Siapkan Parameter Request
            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) $order->total_price,
                ],
                'item_details' => $item_details, // <--- MASUKKAN ARRAY ITEM DISINI
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $request->phone,
                    'shipping_address' => [
                        'address' => $request->address,
                    ],
                ],
                'enabled_payments' => [
                    'credit_card', // Kartu Kredit
                    'bca_va',      // BCA Virtual Account
                    'bni_va',      // BNI Virtual Account
                    'bri_va',      // BRI Virtual Account
                    'echannel',    // Mandiri Bill Payment (Mandiri VA)
                    'permata_va',  // Permata VA
                    'cimb_va',     // CIMB Niaga VA
                    'other_va'     // Bank Lainnya (ATM Bersama/Prima)
                ],
                // Konfigurasi tambahan agar Kartu Kredit Aman (3D Secure)
                'credit_card' => [
                    'secure' => true
                ],
            ];

            // 3. Minta Snap Token
            try {
                $snapToken = Snap::getSnapToken($params);
            } catch (\Exception $e) {
                // Tangkap error jika total harga tidak match (Debug)
                DB::rollback();
                return back()->with('error', 'Midtrans Error: ' . $e->getMessage());
            }

            // 4. Simpan Token
            $order->snap_token = $snapToken;
            $order->save();

            DB::commit();

            return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }
}
