<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_view_all_products(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_view_single_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson('/api/products/' . $product->id);

        $response->assertStatus(200);
        $response->assertJson(['id' => $product->id]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_view_non_existent_product(): void
    {
        $response = $this->getJson('/api/products/999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Product not found']);
    }
}
