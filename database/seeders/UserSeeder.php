<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Mas Adil (Owner)',
                'email' => 'admin@tokomasadil.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Siti Aminah (Kasir)',
                'email' => 'kasir@tokomasadil.com',
                'password' => Hash::make('password'),
                'role' => 'kasir',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Budi Santoso (Kurir)',
                'email' => 'kurir@tokomasadil.com',
                'password' => Hash::make('password'),
                'role' => 'kurir',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
