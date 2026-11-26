<?php

namespace Tests\Feature;

use App\Enums\MovementType;
use App\Models\Category;
use App\Models\Product;
use App\Models\Restock;
use App\Models\RestockDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestockManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_restock_routes_redirect_unauthenticated_users_to_login(): void
    {
        $this->get(route('restocks.index'))->assertRedirect(route('login'));
        $this->get(route('restocks.create'))->assertRedirect(route('login'));
        $this->post(route('restocks.store'), [])->assertRedirect(route('login'));
        $this->get(route('restocks.show', 1))->assertRedirect(route('login'));
    }

    public function test_restock_index_displays_zero_monthly_stats_when_database_is_empty(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('restocks.index'));

        $response->assertStatus(200);
        $response->assertSee('Barang Masuk');
        $response->assertSee('0 Restock');
        $response->assertSee('Rp 0');
        $response->assertDontSee('18 Restock');
        $response->assertDontSee('14.850.000');
    }

    public function test_restock_index_displays_real_database_records_and_monthly_totals(): void
    {
        $user = User::factory()->create(['name' => 'Mas Adil']);
        $category = Category::create(['name' => 'Sembako']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Beras Ramos 5kg',
            'unit' => 'karung',
            'stock' => 10,
            'current_purchase_price' => 60000,
            'current_selling_price' => 70000,
            'is_active' => true,
        ]);

        $restock = Restock::create([
            'user_id' => $user->id,
            'restock_date' => now()->toDateString(),
            'notes' => 'Kulakan Mingguan Agen',
            'total_cost' => 600000,
        ]);

        RestockDetail::create([
            'restock_id' => $restock->id,
            'product_id' => $product->id,
            'quantity' => 10,
            'purchase_price' => 60000,
            'selling_price' => 70000,
            'subtotal' => 600000,
        ]);

        $response = $this->actingAs($user)->get(route('restocks.index'));

        $response->assertStatus(200);
        $response->assertSee('1 Restock');
        $response->assertSee('600.000');
        $response->assertSee('Beras Ramos 5kg');
        $response->assertSee('Mas Adil');
    }

    public function test_restock_create_page_renders_active_products_only(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Sembako']);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Minyak Goreng 2L',
            'unit' => 'pouch',
            'stock' => 12,
            'current_purchase_price' => 30000,
            'current_selling_price' => 35000,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Produk Nonaktif',
            'unit' => 'pcs',
            'stock' => 5,
            'current_purchase_price' => 5000,
            'current_selling_price' => 7000,
            'is_active' => false,
        ]);

        $response = $this->actingAs($user)->get(route('restocks.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Restock');
        $response->assertSee('Minyak Goreng 2L');
        $response->assertDontSee('Produk Nonaktif');
    }

    public function test_restock_store_creates_records_updates_stock_prices_and_logs_stock_movement(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Sembako']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Gula Pasir 1kg',
            'unit' => 'bks',
            'stock' => 5,
            'current_purchase_price' => 14000,
            'current_selling_price' => 16500,
            'is_active' => true,
        ]);

        $payload = [
            'restock_date' => now()->toDateString(),
            'notes' => 'Faktur #AG-001',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 15,
                    'purchase_price' => 14500,
                    'selling_price' => 17500,
                ],
            ],
        ];

        $response = $this->actingAs($user)->post(route('restocks.store'), $payload);

        $response->assertRedirect(route('restocks.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('restocks', [
            'user_id' => $user->id,
            'notes' => 'Faktur #AG-001',
            'total_cost' => 217500,
        ]);

        $restock = Restock::firstOrFail();

        $this->assertDatabaseHas('restock_details', [
            'restock_id' => $restock->id,
            'product_id' => $product->id,
            'quantity' => 15,
            'purchase_price' => 14500,
            'selling_price' => 17500,
            'subtotal' => 217500,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 20,
            'current_purchase_price' => 14500,
            'current_selling_price' => 17500,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'movement_type' => MovementType::Restock->value,
            'quantity_change' => 15,
            'reference_type' => Restock::class,
            'reference_id' => $restock->id,
        ]);
    }

    public function test_restock_store_keeps_existing_selling_price_when_selling_price_is_omitted(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Minuman']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Teh Botol',
            'unit' => 'btl',
            'stock' => 10,
            'current_purchase_price' => 3000,
            'current_selling_price' => 4500,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(route('restocks.store'), [
            'restock_date' => now()->toDateString(),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                    'purchase_price' => 3200,
                    'selling_price' => null,
                ],
            ],
        ]);

        $response->assertRedirect(route('restocks.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 20,
            'current_purchase_price' => 3200,
            'current_selling_price' => 4500,
        ]);
    }

    public function test_restock_store_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('restocks.store'), [
            'restock_date' => '',
            'items' => [],
        ]);

        $response->assertSessionHasErrors(['restock_date', 'items']);
    }

    public function test_restock_show_displays_detail_from_database_and_returns_404_when_missing(): void
    {
        $user = User::factory()->create(['name' => 'Siti Kasir']);
        $category = Category::create(['name' => 'Sembako']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Telur Ayam 1kg',
            'unit' => 'kg',
            'stock' => 25,
            'current_purchase_price' => 26000,
            'current_selling_price' => 29000,
            'is_active' => true,
        ]);

        $restock = Restock::create([
            'user_id' => $user->id,
            'restock_date' => now()->toDateString(),
            'notes' => 'Pengiriman Peternak',
            'total_cost' => 520000,
        ]);

        RestockDetail::create([
            'restock_id' => $restock->id,
            'product_id' => $product->id,
            'quantity' => 20,
            'purchase_price' => 26000,
            'selling_price' => 29000,
            'subtotal' => 520000,
        ]);

        $foundResponse = $this->actingAs($user)->get(route('restocks.show', $restock->id));
        $foundResponse->assertStatus(200);
        $foundResponse->assertSee('Telur Ayam 1kg');
        $foundResponse->assertSee('Pengiriman Peternak');
        $foundResponse->assertSee('520.000');

        $missingResponse = $this->actingAs($user)->get(route('restocks.show', 88));
        $missingResponse->assertStatus(404);
    }
}
