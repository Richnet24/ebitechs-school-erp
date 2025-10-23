<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AcademicDataSeeder::class,
            FinancialDataSeeder::class,
            StockDataSeeder::class,
            WellbeingDataSeeder::class,
        ]);
    }
}