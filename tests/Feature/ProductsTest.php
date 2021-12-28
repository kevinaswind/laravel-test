<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    private $user;

    private function createUser($is_admin = false): void
    {
        $this->user = $user = User::factory()->create(['is_admin' => $is_admin]);
    }

    public function test_products_page_contains_empty_table(): void
    {
        $this->createUser();
        $response = $this->actingAs($this->user)->get('/products');
        $response->assertSee('No products found.');
        $response->assertStatus(200);
    }

    public function test_products_page_contains_none_empty_table(): void
    {
        $product = Product::create([
            'name' => 'iPhone 13',
            'price' => '8789',
        ]);

        $this->createUser();
        $response = $this->actingAs($this->user)->get('/products');
        $response->assertDontSee('No products found.');
        $view_products = $response->viewData('products');

        $this->assertEquals($product->name, $view_products->first()->name);
        $response->assertStatus(200);
    }

    public function test_can_not_see_11th_product(): void
    {
        $products = Product::factory(11)->create();

        $this->createUser();
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertDontSee($products->last()->name);
    }

    public function test_admin_user_can_see_add_product_button(): void
    {
        $this->createUser(true);

        $response = $this->actingAs($this->user)->get('/products');
        $response->assertStatus(200);
        $response->assertSee('Add a product');
    }

    public function test_non_admin_user_cannot_see_add_product_button(): void
    {
        $this->createUser();

        $response = $this->actingAs($this->user)->get('/products');
        $response->assertStatus(200);
        $response->assertDontSee('Add a product');
    }

    public function test_admin_user_can_access_add_product_page(): void
    {
        $this->createUser(true);

        $response = $this->actingAs($this->user)->get('/products/create');
        $response->assertStatus(200);
    }

    public function test_non_admin_user_cannot_access_add_product_page(): void
    {
        $this->createUser();

        $response = $this->actingAs($this->user)->get('/products/create');
        $response->assertStatus(403);
    }

    public function test_store_product_exists_in_database(): void
    {
        $this->createUser(true);

        $response = $this->actingAs($this->user)->post('/products', [
            'name' => 'iPad Pro',
            'price' => 12360,
        ]);

        $response->assertRedirect('/products');

        $this->assertDatabaseHas(Product::class, [
            'name' => 'iPad Pro',
            'price' => 12360,
        ]);

        $product = Product::orderByDesc('id')->first();

        $this->assertEquals('iPad Pro', $product->name);
        $this->assertEquals(12360, $product->price);
    }

    public function test_edit_product_form_contains_name_and_price(): void
    {
        $this->createUser(true);

        $product = Product::factory()->create();

        $this
            ->actingAs($this->user)
            ->get('/products/' . $product->id)
            ->assertStatus(200)
            ->assertSee($product->name)
            ->assertSee($product->price);
    }

    public function test_update_product_correct_validation_error(): void
    {
        $this->createUser(true);

        $product = Product::factory()->create();

        $this
            ->actingAs($this->user)
            ->put('/products/' . $product->id, ['name' => 'test', 'price' => 1234])
            ->assertStatus(302)
            ->assertSessionHasErrors(['name']);
    }

    public function test_update_product_json_correct_validation_error(): void
    {
        $this->createUser(true);

        $product = Product::factory()->create();

        $this
            ->actingAs($this->user)
            ->put('/products/' . $product->id, ['name' => 'test', 'price' => 1234], ['Accept' => 'Application/json'])
            ->assertStatus(422);
    }
}
