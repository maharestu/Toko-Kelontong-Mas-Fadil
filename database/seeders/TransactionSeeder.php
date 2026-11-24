<?php

namespace Database\Seeders;

use App\Enums\DeliveryStatus;
use App\Enums\MovementType;
use App\Enums\OrderType;
use App\Enums\PaymentMethod;
use App\Enums\PaymentType;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\StockMovement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kasir = User::where('role', 'kasir')->first() ?? User::first();
        $kurir = User::where('role', 'kurir')->first() ?? User::first();
        $customers = Customer::all()->keyBy('name');
        $products = Product::all()->keyBy('name');

        if (! $kasir || $products->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($kasir, $kurir, $customers, $products) {
            // Helper function to create sale & details & stock movement
            $createSaleWithItems = function (array $saleData, array $items, ?array $deliveryData = null, ?array $paymentData = null) use ($kasir, $products) {
                $saleTime = $saleData['created_at'];

                // Hitung total
                $totalAmount = 0;
                $detailsToInsert = [];

                foreach ($items as $productName => $qty) {
                    if (! isset($products[$productName])) {
                        continue;
                    }
                    $product = $products[$productName];
                    $sellPrice = (float) $product->current_selling_price;
                    $costPrice = (float) $product->current_purchase_price;
                    $subtotal = $qty * $sellPrice;
                    $totalAmount += $subtotal;

                    $detailsToInsert[] = [
                        'product' => $product,
                        'qty' => $qty,
                        'sellPrice' => $sellPrice,
                        'costPrice' => $costPrice,
                        'subtotal' => $subtotal,
                    ];
                }

                $sale = Sale::forceCreate([
                    'user_id' => $kasir->id,
                    'customer_id' => $saleData['customer_id'] ?? null,
                    'customer_name' => $saleData['customer_name'] ?? null,
                    'order_type' => $saleData['order_type'] ?? OrderType::Direct,
                    'total_amount' => $totalAmount,
                    'payment_type' => $saleData['payment_type'] ?? PaymentType::Langsung,
                    'due_date' => $saleData['due_date'] ?? null,
                    'created_at' => $saleTime,
                    'updated_at' => $saleTime,
                ]);

                // Insert Details, Stock Movement Out, & Decrement Product Stock
                foreach ($detailsToInsert as $item) {
                    SaleDetail::forceCreate([
                        'sale_id' => $sale->id,
                        'product_id' => $item['product']->id,
                        'quantity' => $item['qty'],
                        'selling_price_snapshot' => $item['sellPrice'],
                        'cost_price_snapshot' => $item['costPrice'],
                        'subtotal' => $item['subtotal'],
                        'created_at' => $saleTime,
                        'updated_at' => $saleTime,
                    ]);

                    StockMovement::forceCreate([
                        'product_id' => $item['product']->id,
                        'user_id' => $kasir->id,
                        'movement_type' => MovementType::Sale,
                        'quantity_change' => -$item['qty'],
                        'reference_type' => Sale::class,
                        'reference_id' => $sale->id,
                        'created_at' => $saleTime,
                        'updated_at' => $saleTime,
                    ]);

                    // Kurangi stok di tabel products
                    $item['product']->decrement('stock', $item['qty']);
                }

                // Insert Delivery jika ada
                if ($deliveryData) {
                    Delivery::forceCreate([
                        'sale_id' => $sale->id,
                        'delivery_address' => $deliveryData['address'],
                        'scheduled_at' => $deliveryData['scheduled_at'] ?? $saleTime,
                        'status' => $deliveryData['status'] ?? DeliveryStatus::Menunggu,
                        'started_at' => $deliveryData['started_at'] ?? null,
                        'completed_at' => $deliveryData['completed_at'] ?? null,
                        'courier_id' => $deliveryData['courier_id'] ?? null,
                        'created_at' => $saleTime,
                        'updated_at' => $deliveryData['completed_at'] ?? $saleTime,
                    ]);
                }

                // Insert Payment jika ada
                if ($paymentData) {
                    Payment::forceCreate([
                        'sale_id' => $sale->id,
                        'user_id' => $paymentData['user_id'] ?? $kasir->id,
                        'amount' => $paymentData['amount'] ?? $totalAmount,
                        'payment_method' => $paymentData['payment_method'] ?? PaymentMethod::Cash,
                        'paid_at' => $paymentData['paid_at'] ?? $saleTime,
                        'notes' => $paymentData['notes'] ?? null,
                        'created_at' => $paymentData['paid_at'] ?? $saleTime,
                    ]);
                }

                return $sale;
            };

            // ==========================================
            // KELOMPOK 1: TRANSAKSI BULAN LALU (H-35 s/d H-30)
            // ==========================================

            // Transaksi 1 (35 hari lalu): Pembeli Langsung Tunai (Walk-in)
            $time1 = Carbon::now()->subDays(35)->setTime(10, 15);
            $createSaleWithItems(
                [
                    'customer_id' => null,
                    'customer_name' => 'Pak Joko (Walk-in)',
                    'order_type' => OrderType::Direct,
                    'payment_type' => PaymentType::Langsung,
                    'created_at' => $time1,
                ],
                [
                    'Beras Rojolele 5kg' => 1,
                    'Minyak Goreng Bimoli 1L' => 2,
                    'Gula Pasir Gulaku 1kg' => 1,
                ],
                null,
                [
                    'payment_method' => PaymentMethod::Cash,
                    'paid_at' => $time1,
                    'notes' => 'Pembayaran lunas tunai di meja kasir',
                ]
            );

            // Transaksi 2 (32 hari lalu): Delivery Pak Slamet (Selesai Bulan Lalu)
            $time2 = Carbon::now()->subDays(32)->setTime(14, 0);
            $createSaleWithItems(
                [
                    'customer_id' => $customers['Pak Slamet']->id ?? null,
                    'customer_name' => 'Pak Slamet',
                    'order_type' => OrderType::Delivery,
                    'payment_type' => PaymentType::Langsung,
                    'created_at' => $time2,
                ],
                [
                    'Beras Rojolele 5kg' => 2,
                    'Telur Ayam 1kg' => 2,
                    'Le Minerale 600ml' => 6,
                ],
                [
                    'address' => 'Jl. Mawar No. 5',
                    'scheduled_at' => $time2,
                    'status' => DeliveryStatus::Selesai,
                    'started_at' => $time2->copy()->addMinutes(15),
                    'completed_at' => $time2->copy()->addMinutes(45),
                    'courier_id' => $kurir->id,
                ],
                [
                    'payment_method' => PaymentMethod::Cash,
                    'paid_at' => $time2->copy()->addMinutes(45),
                    'notes' => 'COD titip uang ke kurir Budi Santoso',
                ]
            );

            // Transaksi 3 (30 hari lalu): Kasbon Ibu RT Endang (Sudah Dilunasi 3 hari setelahnya)
            $time3 = Carbon::now()->subDays(30)->setTime(16, 30);
            $createSaleWithItems(
                [
                    'customer_id' => $customers['Ibu RT Endang']->id ?? null,
                    'customer_name' => 'Ibu RT Endang',
                    'order_type' => OrderType::Direct,
                    'payment_type' => PaymentType::Kredit,
                    'due_date' => $time3->copy()->addDays(14)->toDateString(),
                    'created_at' => $time3,
                ],
                [
                    'Minyak Goreng Bimoli 1L' => 2,
                    'Telur Ayam 1kg' => 2,
                    'Indomie Goreng' => 10,
                ],
                null,
                [
                    'payment_method' => PaymentMethod::Cash,
                    'paid_at' => $time3->copy()->addDays(3),
                    'notes' => 'Pelunasan kasbon sembako bulan lalu',
                ]
            );

            // ==========================================
            // KELOMPOK 2: TRANSAKSI 7 HARI TERAKHIR (H-6 s/d Hari Ini)
            // ==========================================

            // Transaksi 4 (H-6): Pembeli Umum Beli Kebutuhan Sarapan
            $time4 = Carbon::now()->subDays(6)->setTime(8, 45);
            $createSaleWithItems(
                [
                    'customer_name' => 'Mbak Maya',
                    'order_type' => OrderType::Direct,
                    'payment_type' => PaymentType::Langsung,
                    'created_at' => $time4,
                ],
                [
                    'Indomie Ayam Bawang' => 5,
                    'Telur Ayam 1kg' => 1,
                    'Kopi Kapal Api Spesial Mix' => 5,
                ],
                null,
                [
                    'payment_method' => PaymentMethod::Qris,
                    'paid_at' => $time4,
                    'notes' => 'Pembayaran via QRIS Kasir',
                ]
            );

            // Transaksi 5 (H-5): Kasbon Mbak Dewi (BELUM LUNAS / Aktif Kasbon)
            $time5 = Carbon::now()->subDays(5)->setTime(17, 20);
            $createSaleWithItems(
                [
                    'customer_id' => $customers['Mbak Dewi']->id ?? null,
                    'customer_name' => 'Mbak Dewi',
                    'order_type' => OrderType::Direct,
                    'payment_type' => PaymentType::Kredit,
                    'due_date' => Carbon::now()->addDays(5)->toDateString(), // Jatuh tempo 5 hari lagi
                    'created_at' => $time5,
                ],
                [
                    'Beras Rojolele 5kg' => 1,
                    'Gula Pasir Gulaku 1kg' => 1,
                    'Sabun Mandi Lifebuoy Total 10' => 2,
                ],
                null,
                null // Belum ada pembayaran sama sekali
            );

            // Transaksi 6 (H-4): Kasbon Warung Kopi Mas Yono (DICICIL SEBAGIAN)
            $time6 = Carbon::now()->subDays(4)->setTime(11, 10);
            $sale6 = $createSaleWithItems(
                [
                    'customer_id' => $customers['Warung Kopi Mas Yono']->id ?? null,
                    'customer_name' => 'Warung Kopi Mas Yono',
                    'order_type' => OrderType::Direct,
                    'payment_type' => PaymentType::Kredit,
                    'due_date' => Carbon::now()->addDays(7)->toDateString(),
                    'created_at' => $time6,
                ],
                [
                    'Indomie Goreng' => 40,
                    'Indomie Ayam Bawang' => 30,
                    'Kopi Kapal Api Spesial Mix' => 40,
                    'Teh Botol Sosro Kotak 250ml' => 10,
                ],
                null,
                null
            );
            // Dicicil Rp 200.000 via Transfer kemarin (H-2)
            Payment::forceCreate([
                'sale_id' => $sale6->id,
                'user_id' => $kasir->id,
                'amount' => 200000,
                'payment_method' => PaymentMethod::Transfer,
                'paid_at' => Carbon::now()->subDays(2)->setTime(15, 30),
                'notes' => 'Angsuran 1 cicilan kasbon kulakan warkop (Transfer BCA)',
                'created_at' => Carbon::now()->subDays(2)->setTime(15, 30),
            ]);

            // Transaksi 7 (H-3): Delivery Selesai Pak Slamet
            $time7 = Carbon::now()->subDays(3)->setTime(13, 0);
            $createSaleWithItems(
                [
                    'customer_id' => $customers['Pak Slamet']->id ?? null,
                    'customer_name' => 'Pak Slamet',
                    'order_type' => OrderType::Delivery,
                    'payment_type' => PaymentType::Langsung,
                    'created_at' => $time7,
                ],
                [
                    'Beras Rojolele 5kg' => 1,
                    'Minyak Goreng Bimoli 1L' => 2,
                    'Deterjen Daia 850g' => 2,
                ],
                [
                    'address' => 'Jl. Mawar No. 5',
                    'scheduled_at' => $time7->copy()->addMinutes(30),
                    'status' => DeliveryStatus::Selesai,
                    'started_at' => $time7->copy()->addMinutes(30),
                    'completed_at' => $time7->copy()->addMinutes(50),
                    'courier_id' => $kurir->id,
                ],
                [
                    'payment_method' => PaymentMethod::Cash,
                    'paid_at' => $time7->copy()->addMinutes(50),
                    'notes' => 'Lunas diterima kurir Budi Santoso',
                ]
            );

            // Transaksi 8 (H-1): Delivery Sedang Dikirim (Delivering)
            $time8 = Carbon::now()->subDays(1)->setTime(15, 0);
            $createSaleWithItems(
                [
                    'customer_id' => $customers['Ibu RT Endang']->id ?? null,
                    'customer_name' => 'Ibu RT Endang',
                    'order_type' => OrderType::Delivery,
                    'payment_type' => PaymentType::Langsung,
                    'created_at' => $time8,
                ],
                [
                    'Telur Ayam 1kg' => 2,
                    'Deterjen Daia 850g' => 1,
                    'Sabun Mandi Lifebuoy Total 10' => 3,
                ],
                [
                    'address' => 'Jl. Melati No. 12, RT 02/03',
                    'scheduled_at' => $time8,
                    'status' => DeliveryStatus::Diantar,
                    'started_at' => $time8->copy()->addMinutes(20),
                    'completed_at' => null,
                    'courier_id' => $kurir->id,
                ],
                null // Belum lunas (menunggu kurir serahkan barang & uang)
            );

            // Transaksi 9 (Hari Ini): Transaksi Pagi Kasir Toko
            $time9 = Carbon::now()->setTime(8, 30);
            $createSaleWithItems(
                [
                    'customer_name' => 'Ibu Halimah',
                    'order_type' => OrderType::Direct,
                    'payment_type' => PaymentType::Langsung,
                    'created_at' => $time9,
                ],
                [
                    'Minyak Goreng Bimoli 1L' => 1,
                    'Gula Pasir Gulaku 1kg' => 1,
                    'Indomie Goreng' => 5,
                    'Teh Botol Sosro Kotak 250ml' => 2,
                ],
                null,
                [
                    'payment_method' => PaymentMethod::Cash,
                    'paid_at' => $time9,
                    'notes' => 'Transaksi tunai pagi hari',
                ]
            );

            // Transaksi 10 (Hari Ini): Transaksi Siang Kasir Toko
            $time10 = Carbon::now()->setTime(12, 15);
            $createSaleWithItems(
                [
                    'customer_name' => 'Mas Doni',
                    'order_type' => OrderType::Direct,
                    'payment_type' => PaymentType::Langsung,
                    'created_at' => $time10,
                ],
                [
                    'Le Minerale 600ml' => 2,
                    'Indomie Goreng' => 3,
                    'Kopi Kapal Api Spesial Mix' => 2,
                ],
                null,
                [
                    'payment_method' => PaymentMethod::Cash,
                    'paid_at' => $time10,
                    'notes' => 'Pembeli jajan siang',
                ]
            );
        });
    }
}
