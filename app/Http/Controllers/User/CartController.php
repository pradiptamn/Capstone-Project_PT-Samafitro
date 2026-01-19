<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string|exists:products,id',
            'quantity' => 'integer|min:1|max:99'
        ]);

        $user = Auth::user();
        $productId = $request->product_id;
        $quantityToAdd = $request->quantity ?? 1;

        // 1. Cek stok produk di database
        $product = Product::findOrFail($productId);

        // 2. Cek jumlah yang sudah ada di keranjang user
        $existingItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        $currentInCart = $existingItem ? $existingItem->quantity : 0;
        $totalRequested = $currentInCart + $quantityToAdd;

        // 3. Validasi: Apakah total yang diminta melebihi stok?
        if ($totalRequested > $product->stok) {
            return response()->json([
                'success' => false,
                'message' => "Stok terbatas! Anda sudah memiliki $currentInCart di keranjang, dan sisa stok hanya {$product->stok}."
            ], 422); // Gunakan status 422 (Unprocessable Entity)
        }

        if ($existingItem) {
            $existingItem->update(['quantity' => $totalRequested]);
            $message = 'Jumlah di keranjang diperbarui';
        } else {
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'quantity' => $quantityToAdd
            ]);
            $message = 'Produk berhasil ditambah ke keranjang';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'cart_count' => $user->cartItems()->sum('quantity')
        ]);
    }

    /**
     * Remove product from cart
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string|exists:products,id'
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        CartItem::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart',
            'cart_count' => $user->cartItems()->sum('quantity')
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        $user = Auth::user();
        $productId = $request->product_id;
        $newQuantity = $request->quantity;

        // 1. Cari item di keranjang dan stok produk
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->firstOrFail();

        $product = Product::findOrFail($productId);
        $oldQuantity = $cartItem->quantity;

        // 2. Validasi Stok Pintar:
        // Hanya blokir jika user MENAMBAH (new > old) DAN melebihi stok.
        // Jika user MENGURANGI (new < old), izinkan saja agar user bisa keluar dari jebakan stok.
        if ($newQuantity > $oldQuantity && $newQuantity > $product->stok) {
            return response()->json([
                'success' => false,
                'message' => "Gagal. Stok fisik hanya tersedia {$product->stok} unit."
            ], 422);
        }

        // 3. Update jumlah
        $cartItem->update(['quantity' => $newQuantity]);

        return response()->json([
            'success' => true,
            'message' => 'Jumlah berhasil diperbarui',
            'cart_count' => $user->cartItems()->sum('quantity')
        ]);
    }

    /**
     * Get user's cart items
     */
    public function getCart()
    {
        $user = Auth::user();
        // Gunakan withTrashed() pada relasi product
        $cartItems = $user->cartItems()->with(['product' => function ($query) {
            $query->withTrashed();
        }])->get();

        $total = $cartItems->sum('quantity');

        return response()->json([
            'cart_items' => $cartItems,
            'total_items' => $total
        ]);
    }

    /**
     * Clear user's cart
     */
    public function clearCart()
    {
        $user = Auth::user();
        $user->cartItems()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
            'cart_count' => 0
        ]);
    }
}
