<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Redis;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $cart = Redis::hgetall('cart');

        if (empty($cart)) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $order = Order::create([
            'items' => json_encode($cart)
        ]);

        Redis::del('cart');

        return response()->json(['message' => 'Order placed successfully'], 201);
    }

    public function viewOrders()
    {
        $orders = Order::all();
        return response()->json($orders);
    }
}
