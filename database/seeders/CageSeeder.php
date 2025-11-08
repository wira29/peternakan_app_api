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
        $locations = Location::all()->pluck('id')->toArray();
        $location1 = $locations[0]; 
        $location2 = $locations[1];
        $location3 = $locations[2];

        $cages = [
            [
                'id' => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f6',
                'location_id' => $location1,
                'name' => 'Kandang A1',
                'capacity' => 15,
                'remarks' => 'Kandang kambing betina dewasa',
            ],
            [
                'id' => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f7',
                'location_id' => $location2,
                'name' => 'Kandang A2',
                'capacity' => 15,
                'remarks' => 'Kandang kambing betina dewasa',
            ],
            [
                'id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f8',
                'location_id' => $location3,
                'name' => 'Kandang B1',
                'capacity' => 10,
                'remarks' => 'Kandang kambing jantan pejantan',
            ],
        ];
        foreach ($cages as $cage) {
            Cage::create($cage);
        }
    }
}
