<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::create(['id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f6','location' => 'Dau, Malang', 'image' => 'dau_malang.jpg']);
        Location::create(['id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f7','location' => 'Kota Batu', 'image' => 'kota_batu.jpg']);
        Location::create(['id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f8','location' => 'Jombang', 'image' => 'jombang.jpg']);
    }
}
