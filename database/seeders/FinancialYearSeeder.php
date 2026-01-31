<?php

namespace Database\Seeders;

use App\Models\FinancialYear;
use Illuminate\Database\Seeder;

class FinancialYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FinancialYear::insert([
            [
                'name' => '2025-26',
                'start_date' => '2025-04-01',
                'end_date' => '2026-03-31',
                'is_active' => true,
            ],
        ]);
    }
}
