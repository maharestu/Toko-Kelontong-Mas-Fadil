<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    /**
     * Helper membuat dummy gambar valid tanpa membutuhkan ekstensi GD PHP.
     */
    protected function createFakeImage(string $name = 'product.png'): UploadedFile
    {
        $pngBase64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        return UploadedFile::fake()->createWithContent($name, base64_decode($pngBase64));
    }

    public function test_renders_product_index_page_successfully(): void
    {
        $category = Category::create(['name' => 'Sembako']);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Beras Pandan Wangi 5kg',
            'unit' => 'karung',
            'stock' => 15,
            'current_purchase_price' => 70000,
            'current_selling_price' => 80000,
            'is_active' => true,
        ]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Produk');
        $response->assertSee('Beras Pandan Wangi 5kg');
    }

    public function test_renders_product_create_page_successfully(): void
    {
        Category::create(['name' => 'Sembako']);

        $response = $this->get(route('products.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Produk Baru');
        $response->assertSee('Satuan Kemasan');
    }

    public function test_renders_product_edit_page_successfully(): void
    {
        $category = Category::create(['name' => 'Sembako']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Minyak Goreng Bimoli',
            'unit' => 'pouch',
            'stock' => 20,
            'current_purchase_price' => 17000,
            'current_selling_price' => 20000,
            'is_active' => true,
        ]);

        $response = $this->get(route('products.edit', $product));

        $response->assertStatus(200);
        $response->assertSee('Edit Produk');
        $response->assertSee('Minyak Goreng Bimoli');
    }

    public function test_filters_products_by_search_keyword(): void
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
        Product::create([
            'category_id' => $category->id,
            'name' => 'Minyak Goreng Bimoli 1L',
            'unit' => 'pouch',
            'stock' => 20,
            'current_purchase_price' => 18000,
            'current_selling_price' => 20000,
            'is_active' => true,
        ]);

        $response = $this->get(route('products.index', ['search' => 'Beras']));

        $response->assertStatus(200);
        $response->assertSee('Beras Rojolele 5kg');
        $response->assertDontSee('Minyak Goreng Bimoli 1L');
    }

    public function test_filters_products_by_category(): void
    {
        $category1 = Category::create(['name' => 'Minuman']);
        $category2 = Category::create(['name' => 'Sembako']);

        Product::create([
            'category_id' => $category1->id,
            'name' => 'Teh Botol Sosro',
            'unit' => 'botol',
            'stock' => 30,
            'current_purchase_price' => 3000,
            'current_selling_price' => 4500,
            'is_active' => true,
        ]);
        Product::create([
            'category_id' => $category2->id,
            'name' => 'Gula Pasir Gulaku',
            'unit' => 'pack',
            'stock' => 25,
            'current_purchase_price' => 15000,
            'current_selling_price' => 17000,
            'is_active' => true,
        ]);

        $response = $this->get(route('products.index', ['category_id' => $category1->id]));

        $response->assertStatus(200);
        $response->assertSee('Teh Botol Sosro');
        $response->assertDontSee('Gula Pasir Gulaku');
    }

    public function test_filters_products_by_stock_status(): void
    {
        $category = Category::create(['name' => 'Sembako']);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Beras Habis',
            'unit' => 'karung',
            'stock' => 0,
            'current_purchase_price' => 60000,
            'current_selling_price' => 70000,
            'is_active' => true,
        ]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Beras Banyak',
            'unit' => 'karung',
            'stock' => 50,
            'current_purchase_price' => 60000,
            'current_selling_price' => 70000,
            'is_active' => true,
        ]);

        $response = $this->get(route('products.index', ['stock_status' => 'out']));

        $response->assertStatus(200);
        $response->assertSee('Beras Habis');
        $response->assertDontSee('Beras Banyak');
    }

    public function test_stores_new_product_with_image_upload_successfully(): void
    {
        Storage::fake('public');

        $category = Category::create(['name' => 'Mie Instan']);
        $fakeImage = $this->createFakeImage('indomie.png');

        $payload = [
            'category_id' => $category->id,
            'name' => 'Indomie Goreng Spesial',
            'unit' => 'bungkus',
            'stock' => 100,
            'current_purchase_price' => 2800,
            'current_selling_price' => 3500,
            'is_active' => true,
            'image' => $fakeImage,
        ];

        $response = $this->post(route('products.store'), $payload);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Indomie Goreng Spesial',
            'stock' => 100,
        ]);

        $product = Product::where('name', 'Indomie Goreng Spesial')->first();
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_validates_required_fields_when_storing_product(): void
    {
        $response = $this->post(route('products.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'category_id',
            'unit',
            'stock',
            'current_purchase_price',
            'current_selling_price',
        ]);
    }

    public function test_updates_product_and_replaces_old_image(): void
    {
        Storage::fake('public');

        $category = Category::create(['name' => 'Minuman']);
        $oldImage = $this->createFakeImage('old_drink.png');
        $initialImagePath = $oldImage->store('products', 'public');

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Kopi Tubruk',
            'unit' => 'sachet',
            'stock' => 40,
            'current_purchase_price' => 1200,
            'current_selling_price' => 2000,
            'is_active' => true,
            'image' => $initialImagePath,
        ]);

        Storage::disk('public')->assertExists($initialImagePath);

        $newImage = $this->createFakeImage('new_drink.png');

        $updatePayload = [
            'category_id' => $category->id,
            'name' => 'Kopi Tubruk Manis',
            'unit' => 'sachet',
            'stock' => 50,
            'current_purchase_price' => 1300,
            'current_selling_price' => 2200,
            'is_active' => true,
            'image' => $newImage,
        ];

        $response = $this->put(route('products.update', $product), $updatePayload);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $product->refresh();
        $this->assertEquals('Kopi Tubruk Manis', $product->name);
        $this->assertNotEquals($initialImagePath, $product->image);

        // Pastikan gambar lama terhapus dan gambar baru tersimpan
        Storage::disk('public')->assertMissing($initialImagePath);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_deletes_product_without_transaction_history_and_cleans_up_image(): void
    {
        Storage::fake('public');

        $category = Category::create(['name' => 'Lain-lain']);
        $fakeImage = $this->createFakeImage('item.png');
        $imagePath = $fakeImage->store('products', 'public');

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Lilin Putih',
            'unit' => 'batang',
            'stock' => 15,
            'current_purchase_price' => 2000,
            'current_selling_price' => 3000,
            'is_active' => true,
            'image' => $imagePath,
        ]);

        $response = $this->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_prevents_deleting_product_with_sale_history(): void
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $category = Category::create(['name' => 'Sembako']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Beras Ramos',
            'unit' => 'kg',
            'stock' => 20,
            'current_purchase_price' => 13000,
            'current_selling_price' => 15000,
            'is_active' => true,
        ]);

        $sale = Sale::create([
            'user_id' => $user->id,
            'order_type' => 'direct',
            'total_amount' => 30000,
            'payment_type' => 'langsung',
        ]);

        SaleDetail::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'selling_price_snapshot' => 15000,
            'cost_price_snapshot' => 13000,
            'subtotal' => 30000,
        ]);

        $response = $this->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
    }
}
