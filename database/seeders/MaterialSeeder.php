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
                'name'  => 'Jagung Giling',
                'stock' => 500,
                'unit'  => 'kg',
            ],
            [
                'name'  => 'Dedak Halus',
                'stock' => 300,
                'unit'  => 'kg',
            ],
            [
                'name'  => 'Bungkil Kedelai',
                'stock' => 200,
                'unit'  => 'kg',
            ],
            [
                'name'  => 'Tepung Ikan',
                'stock' => 100,
                'unit'  => 'kg',
            ],
            [
                'name'  => 'Mineral Mix',
                'stock' => 50,
                'unit'  => 'kg',
            ],
            [
                'name'  => 'Molasses (Tetes Tebu)',
                'stock' => 75,
                'unit'  => 'liter',
            ],
            [
                'name'  => 'Kalsium Karbonat',
                'stock' => 60,
                'unit'  => 'kg',
            ],
            [
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
