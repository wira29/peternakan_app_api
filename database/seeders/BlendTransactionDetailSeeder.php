<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\BlendTransaction;
use App\Models\BlendTransactionDetail;
use Illuminate\Support\Str;

class BlendTransactionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blends = BlendTransaction::all()->pluck('id');
        $material = Material::all()->pluck('id');

        $staticBlendIds = [
            '7c8c9c62-1a5b-4c35-a8e4-6d7a9e4e912a',
            'f1b4b8c0-3e11-4eab-91fd-5c8f29ef2e77',
            '2a0df674-79f9-4ee6-8a22-41f2e45e3b90',
            '9c13fdd9-4eb2-4f51-9a6a-32c61b92fa4d',
            '43d5e2f4-8d10-4c14-99e6-71f5f045becc',
            'b8f7e4d0-25b8-4f67-9c12-81b0d4518ea7',
        ];

        foreach ($blends as $blend) {
            for ($i = 0; $i < 2; $i++) {
                BlendTransactionDetail::create([
                    'id' => Str::uuid(),
                    'blend_transaction_id' => $blend,
                    'material_id' => $material->random(),
                    'qty' => rand(1, 20),
                ]);
            }
        }

        foreach ($staticBlendIds as $staticId) {
            BlendTransactionDetail::create(
                [
                    'id' => $staticId,
                    'blend_transaction_id' => $blends->random(),
                    'material_id' => $material->random(),
                    'qty' => rand(1, 20)
                ]
            );
        }
    }
}
