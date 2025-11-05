<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cage;
use App\Models\Location;

class CageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $location = Location::select("id")->first();
        $cages = [
            [
                'location_id' => $location,
                'name' => 'Kandang A1',
                'capacity' => 15,
                'remarks' => 'Kandang kambing betina dewasa',
            ],
            [
                'location_id' => $location,
                'name' => 'Kandang A2',
                'capacity' => 15,
                'remarks' => 'Kandang kambing betina dewasa',
            ],
            [
                'location_id' => $location,
                'name' => 'Kandang B1',
                'capacity' => 10,
                'remarks' => 'Kandang kambing jantan pejantan',
            ],
        ];
    }
}
