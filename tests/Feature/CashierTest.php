<?php

namespace Tests\Feature;

use App\Enums\MovementType;
use App\Enums\OrderType;
use App\Enums\PaymentType;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_cashier_page_redirects_unauthenticated_user_to_login(): void
    {
        $response = $this->get(route('cashier.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_cashier_index_page_can_be_rendered_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Beras & Sembako']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Beras Ramos Setra 5kg',
            'unit' => 'krg',
            'stock' => 15,
            'current_purchase_price' => 64000,
            'current_selling_price' => 72000,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('cashier.index'));

        $response->assertStatus(200);
        $response->assertSee('Kasir');
        $response->assertSee('Beras &amp; Sembako', false);
        $response->assertSee('Beras Ramos Setra 5kg');
        $response->assertSee('Tap Cepat');
    }

    public function test_cashier_can_process_direct_cash_transaction_and_decrements_stock(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Sembako']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Minyak Goreng Tropical 2L',
            'unit' => 'btl',
            'stock' => 8,
            'current_purchase_price' => 32000,
            'current_selling_price' => 38000,
            'is_active' => true,
        ]);

        $payload = [
            'customer_type' => 'umum',
            'customer_name' => 'Pak Rudi',
            'order_type' => 'direct',
            'payment_type' => 'langsung',
            'payment_method' => 'cash',
            'paid_amount' => 50000,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ],
            ],
        ];

        $response = $this->actingAs($user)->post(route('cashier.store'), $payload);

        $response->assertRedirect(route('cashier.index'));
        $response->assertSessionHas('success');
        $response->assertSessionHas('sale_receipt');

        $this->assertDatabaseHas('sales', [
            'customer_name' => 'Pak Rudi',
            'order_type' => OrderType::Direct->value,
            'payment_type' => PaymentType::Langsung->value,
            'total_amount' => 38000,
        ]);

        $this->assertDatabaseHas('sale_details', [
            'product_id' => $product->id,
            'quantity' => 1,
            'selling_price_snapshot' => 38000,
            'subtotal' => 38000,
        ]);

        // Pastikan stok produk berkurang dari 8 menjadi 7
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 7,
        ]);

        // Pastikan ada mutasi stok jenis sale
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'quantity_change' => -1,
            'movement_type' => MovementType::Sale->value,
        ]);
    }

    public function test_cashier_can_process_credit_transaction_for_registered_customer(): void
    {
        $user = User::factory()->create();
        $customer = Customer::create([
            'name' => 'Ibu RT Endang',
            'phone' => '081234567890',
        ]);
        $category = Category::create(['name' => 'Sembako']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Gula Pasir Gulaku 1kg',
            'unit' => 'bks',
            'stock' => 24,
            'current_purchase_price' => 14000,
            'current_selling_price' => 17500,
            'is_active' => true,
        ]);

        $payload = [
            'customer_type' => 'registered',
            'customer_id' => $customer->id,
            'order_type' => 'direct',
            'payment_type' => 'kredit',
            'due_date' => now()->addDays(7)->toDateString(),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ];

        $response = $this->actingAs($user)->post(route('cashier.store'), $payload);

        $response->assertRedirect(route('cashier.index'));
        $this->assertDatabaseHas('sales', [
            'customer_id' => $customer->id,
            'customer_name' => 'Ibu RT Endang',
            'order_type' => OrderType::Direct->value,
            'payment_type' => PaymentType::Kredit->value,
            'total_amount' => 35000,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 22,
        ]);
    }
}
