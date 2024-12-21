<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Redis;

class CartTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Redis::flushall(); // Clear Redis before each test
    }

    public function testAddToCart()
    {
        $response = $this->postJson('/api/cart/add', ['product_id' => 1, 'quantity' => 2]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Product added to cart']);

        $cart = Redis::hgetall('cart');
        $this->assertEquals(2, $cart[1]);
    }

    public function testRemoveFromCart()
    {
        Redis::hincrby('cart', 1, 2);

        $response = $this->postJson('/api/cart/remove', ['product_id' => 1]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Product removed from cart']);

        $cart = Redis::hgetall('cart');
        $this->assertEmpty($cart);
    }

    public function testViewCart()
    {
        Redis::hincrby('cart', 1, 2);
        Redis::hincrby('cart', 2, 3);

        $response = $this->getJson('/api/cart');

        $response->assertStatus(200)
                 ->assertJson([
                     1 => '2',
                     2 => '3',
                 ]);
    }
}
