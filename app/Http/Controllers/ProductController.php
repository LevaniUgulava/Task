<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\PayseraService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        Product::create([
            'product_name' => $request->product_name,
            'price' => $request->price,
            'user_id' => auth()->user()->id
        ]);


        return response()->json([
            'success' => 'product created successfully!'
        ]);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete-product', $product);
        $product->delete();
        return response()->json([
            'success' => 'product removed successfully!'
        ]);
    }

    public function checkOut()
    {
        $user = auth()->user();
        $cartItems = $user->cartItems;

        $totalPrice = 0;
        foreach ($cartItems as $cartItem) {
            $totalPrice += $cartItem->quantity * $cartItem->product->price;
        }

        // Create an order
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $totalPrice,
            'status' => 'pending', // You can set initial status here
            'payment_status' => 'unpaid', // Initial payment status
            // Add more fields as needed for your Order model
        ]);

        // Initiate transaction with Paysera API
        $payseraResponse = PayseraService::initiateTransaction($order);

        // Delete cart items after successful transaction
        foreach ($cartItems as $cartItem) {
            $cartItem->delete();
        }

        return response()->json([
            'order' => $order,
            'paysera_response' => $payseraResponse,
        ]);
    }
}
