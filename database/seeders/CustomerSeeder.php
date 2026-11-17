<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Ibu RT Endang',
                'phone' => '081234567801',
                'address' => 'Jl. Melati No. 12, RT 02/03',
                'notes' => 'Langganan kasbon terpercaya, batas limit aman Rp 300.000',
            ],
            [
                'name' => 'Pak Slamet',
                'phone' => '081234567802',
                'address' => 'Jl. Mawar No. 5',
                'notes' => 'Sering pesan antar / delivery kebutuhan mingguan',
            ],
            [
                'name' => 'Mbak Dewi',
                'phone' => '081234567803',
                'address' => 'Gang Musholla No. 3',
                'notes' => 'Catatan kasbon aktif bulanan',
            ],
            [
                'name' => 'Warung Kopi Mas Yono',
                'phone' => '081234567804',
                'address' => 'Jl. Raya Pasar No. 8',
                'notes' => 'Langganan grosir mie instan & kopi sachet, tempo per minggu',
            ],
        ];

        foreach ($customers as $custData) {
            Customer::updateOrCreate(
                ['name' => $custData['name']],
                $custData
            );
        }
    }
}
