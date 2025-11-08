<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $material = [
            [
                'id'   => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f6',
                'name'  => 'Jagung Giling',
                'stock' => 500,
                'unit'  => 'kg',
            ],
            [
                'id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f7',
                'name'  => 'Dedak Halus',
                'stock' => 300,
                'unit'  => 'kg',
            ],
            [
                'id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f8',
                'name'  => 'Bungkil Kedelai',
                'stock' => 200,
                'unit'  => 'kg',
            ],
            [
                'id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f9',
                'name'  => 'Tepung Ikan',
                'stock' => 100,
                'unit'  => 'kg',
            ],
            [
                'id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5fa',
                'name'  => 'Mineral Mix',
                'stock' => 50,
                'unit'  => 'kg',
            ],
            [
                'id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5fb',
                'name'  => 'Molasses (Tetes Tebu)',
                'stock' => 75,
                'unit'  => 'liter',
            ],
            [
                'id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5fc',
                'name'  => 'Kalsium Karbonat',
                'stock' => 60,
                'unit'  => 'kg',
            ],
            [
                'id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5fd',
                'name'  => 'Garam',
                'stock' => 40,
                'unit'  => 'kg',
            ],
            [
                'name'  => 'Bekatul',
                'stock' => 250,
                'unit'  => 'kg',
            ],
            [
                'name'  => 'Konsentrat Sapi',
                'stock' => 150,
                'unit'  => 'kg',
            ],
        ];
        foreach ($material as $data) {
            Material::create($data);
        }
    }
}
