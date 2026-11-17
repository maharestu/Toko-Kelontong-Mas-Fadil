<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoriesWithProducts = [
            'Sembako' => [
                [
                    'name' => 'Beras Rojolele 5kg',
                    'unit' => 'karung',
                    'stock' => 20,
                    'current_purchase_price' => 65000,
                    'current_selling_price' => 72000,
                    'is_active' => true,
                    'image' => null,
                ],
                [
                    'name' => 'Minyak Goreng Bimoli 1L',
                    'unit' => 'pouch',
                    'stock' => 35,
                    'current_purchase_price' => 17500,
                    'current_selling_price' => 20000,
                    'is_active' => true,
                    'image' => null,
                ],
                [
                    'name' => 'Gula Pasir Gulaku 1kg',
                    'unit' => 'pack',
                    'stock' => 40,
                    'current_purchase_price' => 14500,
                    'current_selling_price' => 17000,
                    'is_active' => true,
                    'image' => null,
                ],
                [
                    'name' => 'Telur Ayam 1kg',
                    'unit' => 'kg',
                    'stock' => 25,
                    'current_purchase_price' => 26000,
                    'current_selling_price' => 29000,
                    'is_active' => true,
                    'image' => null,
                ],
            ],
            'Mie & Makanan Instan' => [
                [
                    'name' => 'Indomie Goreng',
                    'unit' => 'bungkus',
                    'stock' => 120,
                    'current_purchase_price' => 2800,
                    'current_selling_price' => 3500,
                    'is_active' => true,
                    'image' => null,
                ],
                [
                    'name' => 'Indomie Ayam Bawang',
                    'unit' => 'bungkus',
                    'stock' => 100,
                    'current_purchase_price' => 2700,
                    'current_selling_price' => 3500,
                    'is_active' => true,
                    'image' => null,
                ],
            ],
            'Minuman' => [
                [
                    'name' => 'Le Minerale 600ml',
                    'unit' => 'botol',
                    'stock' => 50,
                    'current_purchase_price' => 2500,
                    'current_selling_price' => 3500,
                    'is_active' => true,
                    'image' => null,
                ],
                [
                    'name' => 'Teh Botol Sosro Kotak 250ml',
                    'unit' => 'kotak',
                    'stock' => 45,
                    'current_purchase_price' => 3200,
                    'current_selling_price' => 4500,
                    'is_active' => true,
                    'image' => null,
                ],
                [
                    'name' => 'Kopi Kapal Api Spesial Mix',
                    'unit' => 'sachet',
                    'stock' => 80,
                    'current_purchase_price' => 1500,
                    'current_selling_price' => 2000,
                    'is_active' => true,
                    'image' => null,
                ],
            ],
            'Kebersihan & Rumah Tangga' => [
                [
                    'name' => 'Sabun Mandi Lifebuoy Total 10',
                    'unit' => 'batang',
                    'stock' => 30,
                    'current_purchase_price' => 3500,
                    'current_selling_price' => 4500,
                    'is_active' => true,
                    'image' => null,
                ],
                [
                    'name' => 'Deterjen Daia 850g',
                    'unit' => 'bungkus',
                    'stock' => 20,
                    'current_purchase_price' => 16000,
                    'current_selling_price' => 19000,
                    'is_active' => true,
                    'image' => null,
                ],
            ],
        ];

        foreach ($categoriesWithProducts as $catName => $products) {
            $category = Category::firstOrCreate(['name' => $catName]);

            foreach ($products as $prodData) {
                Product::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'name' => $prodData['name'],
                    ],
                    $prodData
                );
            }
        }
    }
}
