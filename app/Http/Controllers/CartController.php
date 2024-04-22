<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Product $product)
    {
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'user_id' =>  auth()->id(),
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }
        return response()->json([
            'success' => 'added successfully!'
        ]);
    }
    public function delete(Product $product)
    {
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'error' => 'Product not found in cart.',
            ], 404);
        }

        if ($cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
        } else {
            $cartItem->delete();
        }

        return response()->json([
            'success' => 'Product removed from cart successfully!',
        ]);
    }
}
