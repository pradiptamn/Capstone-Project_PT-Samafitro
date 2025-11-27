<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
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
        $quantity = $request->quantity ?? 1;

        // Check if product already in cart
        $existingItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingItem) {
            // Update quantity if already exists
            $existingItem->update([
                'quantity' => $existingItem->quantity + $quantity
            ]);
            $message = 'Quantity updated in cart';
        } else {
            // Create new cart item
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
            $message = 'Product added to cart';
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
        $quantity = $request->quantity;

        if ($quantity <= 0) {
            // Remove item if quantity is 0 or negative
            return $this->removeFromCart($request);
        }

        CartItem::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->update(['quantity' => $quantity]);

        return response()->json([
            'success' => true,
            'message' => 'Quantity updated',
            'cart_count' => $user->cartItems()->sum('quantity')
        ]);
    }

    /**
     * Get user's cart items
     */
    public function getCart()
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product')->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity;
        });

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
