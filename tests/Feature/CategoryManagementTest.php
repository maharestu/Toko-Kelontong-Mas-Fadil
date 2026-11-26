<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
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

    protected function createAdmin(): User
    {
        return User::factory()->create(['role' => UserRole::Admin]);
    }

    public function test_category_routes_redirect_unauthenticated_users_to_login(): void
    {
        $category = Category::create(['name' => 'Sembako']);

        $this->get(route('categories.index'))->assertRedirect(route('login'));
        $this->post(route('categories.store'), ['name' => 'Minuman'])->assertRedirect(route('login'));
        $this->put(route('categories.update', $category), ['name' => 'Diubah'])->assertRedirect(route('login'));
        $this->delete(route('categories.destroy', $category))->assertRedirect(route('login'));

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Sembako']);
        $this->assertDatabaseMissing('categories', ['name' => 'Minuman']);
    }

    public function test_non_admin_users_cannot_access_or_modify_categories(): void
    {
        $category = Category::create(['name' => 'Sembako']);

        foreach ([UserRole::Kasir, UserRole::Kurir] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)->get(route('categories.index'))->assertForbidden();
            $this->actingAs($user)->post(route('categories.store'), ['name' => 'Baru'])->assertForbidden();
            $this->actingAs($user)->put(route('categories.update', $category), ['name' => 'Ubah'])->assertForbidden();
            $this->actingAs($user)->delete(route('categories.destroy', $category))->assertForbidden();
        }

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Sembako']);
        $this->assertDatabaseMissing('categories', ['name' => 'Baru']);
    }

    public function test_renders_category_index_page_successfully(): void
    {
        $response = $this->actingAs($this->createAdmin())->get(route('categories.index'));

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

        $response = $this->actingAs($this->createAdmin())->get(route('categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Sembako');
        $response->assertSee('1 Produk terhubung');
    }

    public function test_stores_new_category_successfully(): void
    {
        $response = $this->actingAs($this->createAdmin())->post(route('categories.store'), [
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
        $admin = $this->createAdmin();

        // Name wajib diisi
        $emptyResponse = $this->actingAs($admin)->post(route('categories.store'), [
            'name' => '',
        ]);
        $emptyResponse->assertSessionHasErrors('name');

        // Name harus unik
        Category::create(['name' => 'Kopi']);
        $duplicateResponse = $this->actingAs($admin)->post(route('categories.store'), [
            'name' => 'Kopi',
        ]);
        $duplicateResponse->assertSessionHasErrors('name');
    }

    public function test_updates_category_name_successfully(): void
    {
        $category = Category::create(['name' => 'Bumbu Masak']);

        $response = $this->actingAs($this->createAdmin())->put(route('categories.update', $category), [
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

        $response = $this->actingAs($this->createAdmin())->delete(route('categories.destroy', $category));

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

        $response = $this->actingAs($this->createAdmin())->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);
    }
}
