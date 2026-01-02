<?php

namespace Database\Seeders;

use App\Enums\MatingStatusEnum;
use App\Enums\MatingTypeEnum;
use App\Models\MatingHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MatingHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $histories = [
            [
                'id' => '1a2b3c4d-5e6f-7g8h-9i10-jk11lm12no13',
                'male_id' => 'SPI-001',
                'female_id' => 'SPI-002',
                'mating_type' => MatingTypeEnum::NATURAL->value,
                'status' => MatingStatusEnum::PREGNANT->value,
                'mating_date' => '2023-05-15',
                'remark' => 'First mating attempt',
            ]
        ];

        foreach ($histories as $history) {
           MatingHistory::create($history);
        }
    }
}
