<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MilkSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $histories = [
            [
                'id'          => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f4',
                'location_id'     => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f8',
                'sale_date'        => '2024-06-05',
                'qty'         => 100,
                'price_per_liter' => 8000,
                'total' => 800000,
            ],
            [
                'id'          => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f5',
                'location_id'     => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f7',
                'sale_date'        => '2024-06-10',
                'qty'         => 50,
                'price_per_liter' => 8500,
                'total' => 425000,
            ],
        ];

        foreach ($histories as $item) {
            \App\Models\MilkSale::create($item);
        }
    }
}
