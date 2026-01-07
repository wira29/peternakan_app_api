<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vaccine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vaccines = [
            [
                'id'   => '9dae33fe-4b1b-4d3e-9e7c-8a9b2d3e4f5a',
                'name' => 'Vaksin A',
                'created_by' => User::first()->id,
            ],
            [
                'id'   => 'a2c4e6f8-1b3d-4e5f-9a7c-8b0d2e4f6a8c',
                'name' => 'Vaksin B',
                'created_by' => User::first()->id,
            ],
            [
                'id'   => 'c3d5e7a9-2b4d-4f6a-8c0e-1a3b5d7f9e0b',
                'name' => 'Vaksin C',
                'created_by' => User::first()->id,
            ]
        ];

        foreach ($vaccines as $vaccine) {
            Vaccine::create($vaccine);
        }
    }
}
