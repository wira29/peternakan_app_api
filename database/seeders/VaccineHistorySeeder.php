<?php

namespace Database\Seeders;

use App\Models\VaccineHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VaccineHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $history = [
            [
                'id' => 'd1e2f3a4-5b6c-7d8e-9f0a-b1c2d3e4f5a6',
                'goat_code' => 'SPI-002',
                'vaccine_id' => '9dae33fe-4b1b-4d3e-9e7c-8a9b2d3e4f5a',
                'date' => '2024-01-15',
            ],
            [
                'id' => 'e2f3a4b5-6c7d-8e9f-0a1b-c2d3e4f5a6b7',
                'goat_code' => 'SPI-003',
                'vaccine_id' => 'a2c4e6f8-1b3d-4e5f-9a7c-8b0d2e4f6a8c',
                'date' => '2024-02-20',
            ],
            [
                'id' => 'f3a4b5c6-7d8e-9f0a-1b2c-d3e4f5a6b7c8',
                'goat_code' => 'SPI-001',
                'vaccine_id' => 'c3d5e7a9-2b4d-4f6a-8c0e-1a3b5d7f9e0b',
                'date' => '2024-03-10',
            ],
            
        ];

        foreach ($history as $record) {
            VaccineHistory::create($record);
        }
    }
}
