<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
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

        // Ambil keranjang
        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('produk.user')->with('error', 'Keranjang belanja kosong.');
        }

        // Hitung Subtotal
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->harga * $item->quantity;
        }

        // Ongkir Flat (Bisa diubah logikanya nanti)
        $shippingPrice = 20000;

        $total = $subtotal + $shippingPrice;

        return view('pages.user.checkout', compact('cartItems', 'subtotal', 'shippingPrice', 'total', 'user'));
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
        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang kosong');
        }

        // Hitung Ulang (Security: Jangan percaya input harga dari frontend)
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->harga * $item->quantity;
        }
        $shippingPrice = 20000; // Flat Rate
        $grandTotal = $subtotal + $shippingPrice;

        try {
            DB::beginTransaction();

            // A. Buat Order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5)),

                'shipping_address' => $request->address,
                'shipping_phone' => $request->phone,
                'note' => $request->note,

                'subtotal' => $subtotal,
                'shipping_price' => $shippingPrice,
                'total_price' => $grandTotal,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // B. Pindahkan Item
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id, // UUID String
                    'product_id' => $item->product_id, // String ID
                    'product_name' => $item->product->nama_produk,
                    'price' => $item->product->harga,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->product->harga * $item->quantity,
                ]);
            }

            // C. Hapus Keranjang
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

            // B. Masukkan Ongkir sebagai "Item" tambahan
            if ($order->shipping_price > 0) {
                $item_details[] = [
                    'id'       => 'SHIPPING',
                    'price'    => (int) $order->shipping_price,
                    'quantity' => 1,
                    'name'     => 'Biaya Pengiriman (Kurir Internal)',
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
