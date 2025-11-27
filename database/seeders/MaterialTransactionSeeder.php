<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialTransaction;
use App\Models\MaterialTransactionDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = Material::all();
        $uuidTransactions = [
            '8f2e4c93-6b0c-4cd0-a1fb-2b94f2d7a81e',
            'c1d7a0b4-3f29-41b7-8c51-9e0ebc3f4f66',
            'e94db5c1-7f43-4ac5-9f3f-12a7e0c8c2bd',
        ];
        $uuidDetails = collect([
            '4b2c7fa1-9d34-47d1-8b3e-2f0c5c9167aa',
            'e1f59c42-0d7d-4c6c-b2b4-0ac92d8f431e',
            'a7d3bcf9-52e1-4970-a3de-1f8b4ce29cc4',
        ]);

        foreach($uuidTransactions as $uuid){
            $material = $materials->random();

            $transaction = MaterialTransaction::create(
                [
                    'id' => $uuid,
                    'supplier' => 'Supplier AB',
                    'transaction_date' => now()
                ]
            );

            $detail = MaterialTransactionDetail::create(
                [
                    'id' => $uuidDetails->shift(),
                    'material_transaction_id' => $transaction->id,
                    'material_id' => $material->id,
                    'qty'     => rand(1, 30),
                    'price' => rand(2000, 100000),
                ]
                );

            $detail->total = $detail->qty * $detail->price;
            $detail->save();
            $transaction->total = $detail->total;
            $transaction->save();
        }
    }
}
