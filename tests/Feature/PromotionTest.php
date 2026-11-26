<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromotionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_promotion_page_redirects_unauthenticated_users_to_login(): void
    {
        $this->get(route('promosi.index'))->assertRedirect(route('login'));
    }

    public function test_promotion_page_renders_active_products_and_categories_only(): void
    {
        $user = User::factory()->create();
        $sembako = Category::create(['name' => 'Sembako']);
        $minuman = Category::create(['name' => 'Minuman']);

        $activeProduct = Product::create([
            'category_id' => $sembako->id,
            'name' => 'Beras Pandan Wangi 5kg',
            'unit' => 'krg',
            'stock' => 20,
            'current_purchase_price' => 62000,
            'current_selling_price' => 75000,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $minuman->id,
            'name' => 'Minuman Nonaktif',
            'unit' => 'btl',
            'stock' => 5,
            'current_purchase_price' => 3000,
            'current_selling_price' => 5000,
            'is_active' => false,
        ]);

        $response = $this->actingAs($user)->get(route('promosi.index'));

        $response->assertOk();
        $response->assertSee('Pembuat Poster Promosi');
        $response->assertSee('Beras Pandan Wangi 5kg');
        $response->assertDontSee('Minuman Nonaktif');

        $products = $response->viewData('products');
        $this->assertCount(1, $products);
        $this->assertTrue($products->first()->is($activeProduct));

        $categories = $response->viewData('categories');
        $this->assertCount(2, $categories);
        $this->assertSame('Minuman', $categories->first()->name);
    }

    public function test_promotion_page_displays_standardized_store_name(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('promosi.index'));

        $response->assertOk();
        $response->assertSee('Promosi - Toko Mas Adil');
        $response->assertSee('TOKO MAS ADIL');
        $response->assertDontSee('Mas Fadil');
    }
}
