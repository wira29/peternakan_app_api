<?php

namespace Database\Seeders;

use App\Enums\FemaleConditionEnum;
use App\Enums\GoatGender;
use App\Enums\GoatOriginEnum;
use App\Models\Goat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GoatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $goats = [
            [
                'code'             => 'SPI-001',
                'breed_id'         => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5fe',
                'cage_id'          => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f8',
                'location_id'      => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f8',
                'father_id'        => null,
                'mother_id'        => null,
                'origin'           => GoatOriginEnum::BUY->value,
                'color'            => 'Merah Bata',
                'gender'           => GoatGender::MALE->value,
                'date'             => '2022-06-15', 
                'price'            => 22500000, 
                'female_condition' => null,
                'is_breeder'       => 0,
                'is_qurbani'       => 1,
                'remarks'          => 'Sapi sehat, bobot 450kg, siap qurban',
            ],

            [
                'code'             => 'SPI-002',
                'breed_id'         => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5fe',
                'cage_id'          => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f7',
                'location_id'      => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f7',
                'father_id'        => null,
                'mother_id'        => null,
                'origin'           => GoatOriginEnum::BIRTH->value,
                'color'            => 'Putih',
                'gender'           => GoatGender::FEMALE->value,
                'date'             => '2021-02-10',
                'price'            => 0,
                'female_condition' => FemaleConditionEnum::PREGNANT->value,
                'is_breeder'       => 1,
                'is_qurbani'       => 0,
                'remarks'          => 'Indukan PO, sedang bunting 4 bulan',
            ],

            [
                'code'             => 'SPI-003',
                'breed_id'         => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5fe',
                'cage_id'          => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f7',
                'location_id'      => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f7',
                'father_id'        => 'SPI-001', 
                'mother_id'        => 'SPI-002', 
                'origin'           => GoatOriginEnum::BIRTH->value,
                'color'            => 'Merah Putih',
                'gender'           => GoatGender::MALE->value,
                'date'             => '2024-05-01',
                'price'            => 0,
                'female_condition' => null,
                'is_breeder'       => 0,
                'is_qurbani'       => 0,
                'remarks'          => 'Pedet jantan, masih menyusu',
            ],
        ];

        foreach ($goats as $goat) {
            Goat::create($goat);
        }
    }
}
