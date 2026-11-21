<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_renders_category_index_page_successfully(): void
    {
        $response = $this->get(route('categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Kategori');
        $response->assertSee('DAFTAR KATEGORI');
        $response->assertSee('Tips Warung Rapi');
    }

    public function test_displays_existing_categories_with_product_counts(): void
    {
        $category = Category::create(['name' => 'Sembako']);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Beras Rojolele 5kg',
            'unit' => 'karung',
            'stock' => 10,
            'current_purchase_price' => 60000,
            'current_selling_price' => 70000,
            'is_active' => true,
        ]);

        $response = $this->get(route('categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Sembako');
        $response->assertSee('1 Produk terhubung');
        $response->assertSee('UTAMA');
    }

    public function test_stores_new_category_successfully(): void
    {
        $response = $this->post(route('categories.store'), [
            'name' => 'Minuman Segar',
        ]);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('categories', [
            'name' => 'Minuman Segar',
        ]);
    }

    public function test_validates_category_name_when_storing(): void
    {
        // Name wajib diisi
        $emptyResponse = $this->post(route('categories.store'), [
            'name' => '',
        ]);
        $emptyResponse->assertSessionHasErrors('name');

        // Name harus unik
        Category::create(['name' => 'Kopi']);
        $duplicateResponse = $this->post(route('categories.store'), [
            'name' => 'Kopi',
        ]);
        $duplicateResponse->assertSessionHasErrors('name');
    }

    public function test_updates_category_name_successfully(): void
    {
        $category = Category::create(['name' => 'Bumbu Masak']);

        $response = $this->put(route('categories.update', $category), [
            'name' => 'Bumbu & Rempah',
        ]);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Bumbu & Rempah',
        ]);
    }

    public function test_deletes_category_without_products_successfully(): void
    {
        $category = Category::create(['name' => 'Peralatan Listrik']);

        $response = $this->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_prevents_deleting_category_that_has_products(): void
    {
        $category = Category::create(['name' => 'Makanan Ringan']);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Keripik Singkong',
            'unit' => 'bungkus',
            'stock' => 20,
            'current_purchase_price' => 3000,
            'current_selling_price' => 5000,
            'is_active' => true,
        ]);

        $response = $this->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);
    }
}
