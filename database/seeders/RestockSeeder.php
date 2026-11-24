<?php

namespace Database\Seeders;

use App\Enums\MovementType;
use App\Models\Product;
use App\Models\Restock;
use App\Models\RestockDetail;
use App\Models\StockMovement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();
        if (!$admin) {
            return;
        }

        $allProducts = Product::all()->keyBy('name');
        if ($allProducts->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($admin, $allProducts) {
            // Batch 1: Kulakan Awal Toko (45 hari lalu)
            $dateBatch1 = Carbon::now()->subDays(45);
            $restock1 = Restock::forceCreate([
                'user_id' => $admin->id,
                'restock_date' => $dateBatch1->toDateString(),
                'notes' => 'Kulakan modal stok awal toko kelontong dari agen distributor pusat.',
                'total_cost' => 0,
                'created_at' => $dateBatch1,
                'updated_at' => $dateBatch1,
            ]);

            $itemsBatch1 = [
                'Beras Rojolele 5kg' => ['qty' => 30, 'buy' => 65000, 'sell' => 72000],
                'Minyak Goreng Bimoli 1L' => ['qty' => 50, 'buy' => 17500, 'sell' => 20000],
                'Gula Pasir Gulaku 1kg' => ['qty' => 60, 'buy' => 14500, 'sell' => 17000],
                'Telur Ayam 1kg' => ['qty' => 40, 'buy' => 26000, 'sell' => 29000],
                'Indomie Goreng' => ['qty' => 200, 'buy' => 2800, 'sell' => 3500],
                'Indomie Ayam Bawang' => ['qty' => 150, 'buy' => 2700, 'sell' => 3500],
                'Le Minerale 600ml' => ['qty' => 80, 'buy' => 2500, 'sell' => 3500],
                'Teh Botol Sosro Kotak 250ml' => ['qty' => 60, 'buy' => 3200, 'sell' => 4500],
                'Kopi Kapal Api Spesial Mix' => ['qty' => 120, 'buy' => 1500, 'sell' => 2000],
                'Sabun Mandi Lifebuoy Total 10' => ['qty' => 50, 'buy' => 3500, 'sell' => 4500],
                'Deterjen Daia 850g' => ['qty' => 30, 'buy' => 16000, 'sell' => 19000],
            ];

            $totalCost1 = 0;
            foreach ($itemsBatch1 as $productName => $item) {
                if (!isset($allProducts[$productName])) {
                    continue;
                }
                $product = $allProducts[$productName];
                $subtotal = $item['qty'] * $item['buy'];
                $totalCost1 += $subtotal;

                RestockDetail::forceCreate([
                    'restock_id' => $restock1->id,
                    'product_id' => $product->id,
                    'quantity' => $item['qty'],
                    'purchase_price' => $item['buy'],
                    'selling_price' => $item['sell'],
                    'subtotal' => $subtotal,
                    'created_at' => $dateBatch1,
                    'updated_at' => $dateBatch1,
                ]);

                StockMovement::forceCreate([
                    'product_id' => $product->id,
                    'user_id' => $admin->id,
                    'movement_type' => MovementType::Restock,
                    'quantity_change' => $item['qty'],
                    'reference_type' => Restock::class,
                    'reference_id' => $restock1->id,
                    'created_at' => $dateBatch1,
                    'updated_at' => $dateBatch1,
                ]);

                // Update kolom stok produk di tabel products
                $product->increment('stock', $item['qty']);
            }
            $restock1->update(['total_cost' => $totalCost1]);

            // Batch 2: Kulakan Tambahan (14 hari lalu)
            $dateBatch2 = Carbon::now()->subDays(14);
            $restock2 = Restock::forceCreate([
                'user_id' => $admin->id,
                'restock_date' => $dateBatch2->toDateString(),
                'notes' => 'Restock pengisian sembako cepat habis (Beras, Telur, Mie).',
                'total_cost' => 0,
                'created_at' => $dateBatch2,
                'updated_at' => $dateBatch2,
            ]);

            $itemsBatch2 = [
                'Beras Rojolele 5kg' => ['qty' => 15, 'buy' => 65000, 'sell' => 72000],
                'Telur Ayam 1kg' => ['qty' => 20, 'buy' => 26000, 'sell' => 29000],
                'Indomie Goreng' => ['qty' => 100, 'buy' => 2800, 'sell' => 3500],
            ];

            $totalCost2 = 0;
            foreach ($itemsBatch2 as $productName => $item) {
                if (!isset($allProducts[$productName])) {
                    continue;
                }
                $product = $allProducts[$productName];
                $subtotal = $item['qty'] * $item['buy'];
                $totalCost2 += $subtotal;

                RestockDetail::forceCreate([
                    'restock_id' => $restock2->id,
                    'product_id' => $product->id,
                    'quantity' => $item['qty'],
                    'purchase_price' => $item['buy'],
                    'selling_price' => $item['sell'],
                    'subtotal' => $subtotal,
                    'created_at' => $dateBatch2,
                    'updated_at' => $dateBatch2,
                ]);

                StockMovement::forceCreate([
                    'product_id' => $product->id,
                    'user_id' => $admin->id,
                    'movement_type' => MovementType::Restock,
                    'quantity_change' => $item['qty'],
                    'reference_type' => Restock::class,
                    'reference_id' => $restock2->id,
                    'created_at' => $dateBatch2,
                    'updated_at' => $dateBatch2,
                ]);

                // Update kolom stok produk di tabel products
                $product->increment('stock', $item['qty']);
            }
            $restock2->update(['total_cost' => $totalCost2]);
        });
    }
}
