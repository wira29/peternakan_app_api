<?php

namespace Database\Seeders;

use App\Models\WeightHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WeightHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $history = [
            [
                'id' => 'd1e2f3a4-5b6c-7d8e-9f0a-b1c2d3e4f5a6',
                'goat_code' => 'SPI-001',
                'weight' => 45,
                'height' => 90,
                'date' => '2023-01-15',
            ],
            [
                'id' => 'e2f3a4b5-6c7d-8e9f-0a1b-c2d3e4f5a6b7',
                'goat_code' => 'SPI-002',
                'weight' => 50,
                'height' => 95,
                'date' => '2023-01-16',
            ],
            [
                'id' => 'f3a4b5c6-7d8e-9f0a-1b2c-d3e4f5a6b7c8',
                'goat_code' => 'SPI-003',
                'weight' => 55,
                'height' => 100,
                'date' => '2023-01-17',
            ],
        ];

        foreach ($history as $record) {
            WeightHistory::create($record);
        }
    }
}
