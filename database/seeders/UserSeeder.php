<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'sadiquedeveloper@gmail.com'],
            [
                'name' => 'Sadique',
                'role' => 'admin',
                'mobile' => '9999999999',
                'image' => null,
                'email_verified_at' => null,
                'password' => Hash::make('123'), // change if needed
                'remember_token' => null,
                'status' => 1,
                'paid_status' => 0, // unpaid
                'dark_mode' => 0,
                'discontinue_date' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'yaqubsiddiqui5@gmail.com'],
            [
                'name' => 'Yaqub',
                'role' => 'admin',
                'mobile' => '9999999899',
                'image' => null,
                'email_verified_at' => null,
                'password' => Hash::make('123'),
                'remember_token' => null,
                'status' => 1,
                'paid_status' => 0,
                'dark_mode' => 0,
                'discontinue_date' => null,
            ]
        );
    }
}
