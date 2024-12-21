<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Order;
use Illuminate\Support\Facades\Redis;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function testPlaceOrderWithEmptyCart()
    {
        // Ensure the Redis cart is empty
        Redis::del('cart');

        $response = $this->postJson('/api/orders');

        $response->assertStatus(400)
                 ->assertJson(['message' => 'Cart is empty']);
    }

    public function testPlaceOrderWithItemsInCart()
    {
        Redis::hincrby('cart', 1, 2);
        Redis::hincrby('cart', 2, 3);

        $response = $this->postJson('/api/orders');

        $response->assertStatus(201)
                 ->assertJson(['message' => 'Order placed successfully']);

        $this->assertDatabaseHas('orders', [
            'items' => json_encode(['1' => '2', '2' => '3'])
        ]);

        $cart = Redis::hgetall('cart');
        $this->assertEmpty($cart);
    }

    public function testViewOrders()
    {
        $order = Order::create([
            'items' => json_encode(['1' => '2', '2' => '3'])
        ]);

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200)
                 ->assertJson([
                     [
                         'id' => $order->id,
                         'items' => json_encode(['1' => '2', '2' => '3']),
                     ]
                 ]);
    }
}
