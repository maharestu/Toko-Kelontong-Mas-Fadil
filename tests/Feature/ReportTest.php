<?php

namespace Tests\Feature;

use App\Enums\OrderType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentType;
use App\Models\Category;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
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

    public function test_report_page_redirects_unauthenticated_users_to_login(): void
    {
        $this->get(route('laporan'))->assertRedirect(route('login'));
    }

    public function test_report_page_displays_zero_metrics_without_static_dummy_data_when_database_is_empty(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 11, 26, 14, 0, 0));
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('laporan'));

        $response->assertOk();
        $response->assertViewHas('reportData');

        $reportData = $response->viewData('reportData');

        $this->assertSame(0.0, $reportData['today']['omzet']);
        $this->assertSame(0.0, $reportData['today']['laba']);
        $this->assertSame(0, $reportData['today']['trxCount']);
        $this->assertEmpty($reportData['today']['products']);
        $this->assertEmpty($reportData['today']['transactions']);
        $this->assertEmpty($reportData['today']['payments']);
        $this->assertEmpty($reportData['today']['chart']);

        $response->assertDontSee('1.920.000');
        $response->assertDontSee('448.000');
        $response->assertDontSee('#TRX-1042');
        $response->assertDontSee('24 Nov 2026');
    }

    public function test_report_page_aggregates_real_database_sales_profit_and_payments_across_periods(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 11, 26, 14, 0, 0));

        $user = User::factory()->create();
        $category = Category::create(['name' => 'Sembako']);
        $rice = Product::create([
            'category_id' => $category->id,
            'name' => 'Beras Mentik Wangi 5kg',
            'unit' => 'krg',
            'stock' => 20,
            'current_purchase_price' => 60000,
            'current_selling_price' => 75000,
            'is_active' => true,
        ]);
        $oil = Product::create([
            'category_id' => $category->id,
            'name' => 'Minyak Goreng 1L',
            'unit' => 'btl',
            'stock' => 30,
            'current_purchase_price' => 15000,
            'current_selling_price' => 20000,
            'is_active' => true,
        ]);

        // Transaksi Hari Ini (26 Nov 2026 10:15): 2x Beras = 150.000 (modal 120.000, laba 30.000) via QRIS
        $todayTime = Carbon::create(2026, 11, 26, 10, 15, 0);
        $todaySale = Sale::forceCreate([
            'user_id' => $user->id,
            'customer_name' => 'Ibu Ani',
            'order_type' => OrderType::Direct,
            'total_amount' => 150000,
            'payment_type' => PaymentType::Langsung,
            'created_at' => $todayTime,
            'updated_at' => $todayTime,
        ]);
        SaleDetail::forceCreate([
            'sale_id' => $todaySale->id,
            'product_id' => $rice->id,
            'quantity' => 2,
            'selling_price_snapshot' => 75000,
            'cost_price_snapshot' => 60000,
            'subtotal' => 150000,
            'created_at' => $todayTime,
            'updated_at' => $todayTime,
        ]);
        Payment::forceCreate([
            'sale_id' => $todaySale->id,
            'user_id' => $user->id,
            'amount' => 150000,
            'payment_method' => PaymentMethod::Qris,
            'paid_at' => $todayTime,
            'created_at' => $todayTime,
        ]);

        // Transaksi 3 Hari Lalu (23 Nov 2026 15:00): 3x Minyak = 60.000 (modal 45.000, laba 15.000) via Tunai
        $weekTime = Carbon::create(2026, 11, 23, 15, 0, 0);
        $weekSale = Sale::forceCreate([
            'user_id' => $user->id,
            'customer_name' => 'Pak Budi',
            'order_type' => OrderType::Direct,
            'total_amount' => 60000,
            'payment_type' => PaymentType::Langsung,
            'created_at' => $weekTime,
            'updated_at' => $weekTime,
        ]);
        SaleDetail::forceCreate([
            'sale_id' => $weekSale->id,
            'product_id' => $oil->id,
            'quantity' => 3,
            'selling_price_snapshot' => 20000,
            'cost_price_snapshot' => 15000,
            'subtotal' => 60000,
            'created_at' => $weekTime,
            'updated_at' => $weekTime,
        ]);
        Payment::forceCreate([
            'sale_id' => $weekSale->id,
            'user_id' => $user->id,
            'amount' => 60000,
            'payment_method' => PaymentMethod::Cash,
            'paid_at' => $weekTime,
            'created_at' => $weekTime,
        ]);

        // Transaksi Awal Bulan (2 Nov 2026 09:00): 1x Minyak = 20.000 (modal 15.000, laba 5.000) Kasbon
        $monthTime = Carbon::create(2026, 11, 2, 9, 0, 0);
        $monthSale = Sale::forceCreate([
            'user_id' => $user->id,
            'customer_name' => 'Bu Siti',
            'order_type' => OrderType::Direct,
            'total_amount' => 20000,
            'payment_type' => PaymentType::Kredit,
            'due_date' => '2026-11-30',
            'created_at' => $monthTime,
            'updated_at' => $monthTime,
        ]);
        SaleDetail::forceCreate([
            'sale_id' => $monthSale->id,
            'product_id' => $oil->id,
            'quantity' => 1,
            'selling_price_snapshot' => 20000,
            'cost_price_snapshot' => 15000,
            'subtotal' => 20000,
            'created_at' => $monthTime,
            'updated_at' => $monthTime,
        ]);

        $response = $this->actingAs($user)->get(route('laporan'));

        $response->assertOk();
        $reportData = $response->viewData('reportData');

        // Hari Ini
        $this->assertSame(150000.0, $reportData['today']['omzet']);
        $this->assertSame(30000.0, $reportData['today']['laba']);
        $this->assertSame(1, $reportData['today']['trxCount']);
        $this->assertSame('Beras Mentik Wangi 5kg', $reportData['today']['products'][0]['name']);
        $this->assertSame('QRIS', $reportData['today']['payments'][0]['method']);
        $this->assertCount(8, $reportData['today']['chart']);

        // Minggu Ini (20 Nov - 26 Nov)
        $this->assertSame(210000.0, $reportData['week']['omzet']);
        $this->assertSame(45000.0, $reportData['week']['laba']);
        $this->assertSame(2, $reportData['week']['trxCount']);
        $this->assertCount(7, $reportData['week']['chart']);

        // Bulan Ini (1 Nov - 26 Nov)
        $this->assertSame(230000.0, $reportData['month']['omzet']);
        $this->assertSame(50000.0, $reportData['month']['laba']);
        $this->assertSame(3, $reportData['month']['trxCount']);
        $this->assertSame('Kasbon', $reportData['month']['transactions'][2]['method']);
    }
}
