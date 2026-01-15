<?php

namespace Database\Seeders;

use App\Models\MilkStock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MilkStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stock = [
            [
                'id'          => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f0',
                'location_id' => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f8',
                'qty'         => 500,
            ],
            [
                'id'          => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f1',
                'location_id' => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f7',
                'qty'         => 300,
            ],
        ];

        foreach ($stock as $item) {
            MilkStock::create($item);
        }
    }
}
