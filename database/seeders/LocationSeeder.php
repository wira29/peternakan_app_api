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
        Location::create(['location' => 'Dau, Malang', 'image' => 'dau_malang.jpg']);
        Location::create(['location' => 'Kota Batu', 'image' => 'kota_batu.jpg']);
        Location::create(['location' => 'Jombang', 'image' => 'jombang.jpg']);
    }
}
