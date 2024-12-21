<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        Redis::hincrby('cart', $productId, $quantity);

        return response()->json(['message' => 'Product added to cart']);
    }

    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');

        Redis::hdel('cart', $productId);

        return response()->json(['message' => 'Product removed from cart']);
    }

    public function viewCart()
    {
        $cart = Redis::hgetall('cart');

        return response()->json($cart);
    }
}
