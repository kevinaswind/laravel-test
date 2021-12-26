<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_page_contains_empty_table(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $response = $this->get('/products');
        $response->assertSee('No products found.');
        $response->assertStatus(200);
    }

    public function test_products_page_contains_none_empty_table(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $product = Product::create([
            'name' => 'iPhone 13',
            'price' => '8789',
        ]);

        $response = $this->get('/products');
        $response->assertDontSee('No products found.');
        $view_products = $response->viewData('products');

        $this->assertEquals($product->name, $view_products->first()->name);
        $response->assertStatus(200);
    }
}
