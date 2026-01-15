<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MilkingHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $histories = [
            [
                'id'          => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f3',
                'goat_code'     => 'SPI-002',
                'milked_at'        => '2024-06-01',
                'qty'         => 10,
            ],
        ];

        foreach ($histories as $item) {
            \App\Models\MilkingHistory::create($item);
        }
    }
}
