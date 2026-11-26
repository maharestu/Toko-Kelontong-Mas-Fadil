<?php

namespace Tests\Feature;

use App\Enums\OrderType;
use App\Enums\PaymentType;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_dashboard_redirects_unauthenticated_users_to_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_dashboard_displays_zero_metrics_without_dummy_fallback_when_database_is_empty(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('0 nota');
        $response->assertSee('Margin efektif 0%');
        $response->assertSee('Semua stok produk aman');
        $response->assertSee(route('cashier.index'), false);
        $response->assertSee(route('restocks.index'), false);

        $response->assertDontSee('3.482.500');
        $response->assertDontSee('68 nota');
        $response->assertDontSee('612.000');
        $response->assertDontSee('2.950.000');
        $response->assertDontSee('Beras Ramos Setra 5kg');
    }

    public function test_dashboard_calculates_real_sales_growth_and_counts_all_critical_products(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 11, 26, 10, 0, 0));

        $user = User::factory()->create();
        $category = Category::create(['name' => 'Sembako']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Beras Pandan 5kg',
            'unit' => 'krg',
            'stock' => 25,
            'current_purchase_price' => 55000,
            'current_selling_price' => 75000,
            'is_active' => true,
        ]);

        // 6 produk aktif stok kritis (<= 5) + 1 produk nonaktif (tidak boleh dihitung)
        for ($i = 1; $i <= 6; $i++) {
            Product::create([
                'category_id' => $category->id,
                'name' => "Produk Kritis {$i}",
                'unit' => 'pcs',
                'stock' => $i <= 5 ? $i : 5,
                'current_purchase_price' => 5000,
                'current_selling_price' => 7000,
                'is_active' => true,
            ]);
        }

        Product::create([
            'category_id' => $category->id,
            'name' => 'Produk Kritis Nonaktif',
            'unit' => 'pcs',
            'stock' => 1,
            'current_purchase_price' => 5000,
            'current_selling_price' => 7000,
            'is_active' => false,
        ]);

        // Penjualan kemarin: Rp 100.000
        $yesterdaySale = Sale::forceCreate([
            'user_id' => $user->id,
            'customer_name' => 'Pelanggan Kemarin',
            'order_type' => OrderType::Direct,
            'total_amount' => 100000,
            'payment_type' => PaymentType::Langsung,
            'created_at' => Carbon::yesterday()->setTime(14, 0),
            'updated_at' => Carbon::yesterday()->setTime(14, 0),
        ]);

        SaleDetail::forceCreate([
            'sale_id' => $yesterdaySale->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'selling_price_snapshot' => 100000,
            'cost_price_snapshot' => 80000,
            'subtotal' => 100000,
            'created_at' => Carbon::yesterday()->setTime(14, 0),
            'updated_at' => Carbon::yesterday()->setTime(14, 0),
        ]);

        // Penjualan hari ini: Rp 150.000 (2 nota), modal Rp 110.000 -> laba Rp 40.000, growth +50%
        $todaySale = Sale::forceCreate([
            'user_id' => $user->id,
            'customer_name' => 'Pelanggan Hari Ini',
            'order_type' => OrderType::Direct,
            'total_amount' => 150000,
            'payment_type' => PaymentType::Langsung,
            'created_at' => Carbon::today()->setTime(9, 30),
            'updated_at' => Carbon::today()->setTime(9, 30),
        ]);

        SaleDetail::forceCreate([
            'sale_id' => $todaySale->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'selling_price_snapshot' => 75000,
            'cost_price_snapshot' => 55000,
            'subtotal' => 150000,
            'created_at' => Carbon::today()->setTime(9, 30),
            'updated_at' => Carbon::today()->setTime(9, 30),
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('150.000');
        $response->assertSee('1 nota');
        $response->assertSee('40.000');
        $response->assertSee('+50% vs kemarin');
        $response->assertSee('6 Kritis');
        $response->assertDontSee('Produk Kritis Nonaktif');
    }
}
