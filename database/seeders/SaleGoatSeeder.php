<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaleGoatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sales = [
            [
                'id' => '7jdkfdh3-4f5g-6h7i-8j9k-l0mnopqrstuv',
                'goat_code' => 'SPI-001',
                'price' => 25000000,
                'date' => '2023-12-01',
            ],
            [
                'id' => '8eklfjd4-5g6h-7i8j-9k0l-mnopqrstuvwx',
                'goat_code' => 'SPI-003',
                'price' => 18000000,
                'date' => '2023-12-05',
            ],
        ];

        foreach ($sales as $sale) {
            \App\Models\SaleGoat::create($sale);
        }
    }
}
