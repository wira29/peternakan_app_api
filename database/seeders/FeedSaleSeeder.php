<?php

namespace Database\Seeders;

use App\Models\Feed;
use App\Models\FeedSale;
use App\Models\FeedSaleDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feeds = Feed::all()->pluck('id');
        $staticSaleIds = [
            '7c9c8e3c-6c7d-4f3a-9d8f-4b1b6a4f9c11',
            'f1c2b0a7-3d45-4e28-9b73-0c8df4e1a622', 
            'a54e9d12-8f0c-4baf-92ce-3e7d5c0b44d0',
        ];
        $staticDetailIds= collect([
            'e3b71f4a-9c2d-4a8e-91c0-8fa34b77d5e4',
            '4f0c92ab-1d67-4b3f-8c1e-2e7ad3fbc915',
            'c87a1d33-52ee-4f79-b6c0-9d4f0a2e1d88'
        ]);

        foreach ($staticSaleIds as $staticId) {
            $feedSale = FeedSale::create([
                'id'      => $staticId,             
                'sale_date'    => now(),
            ]);

            $detail = FeedSaleDetail::create(
                [
                    'id' => $staticDetailIds->shift(),
                    'feed_sale_id' => $feedSale->id,
                    'feed_id' => $feeds->random(),
                    'qty'     => rand(1, 30),
                    'price_per_unit' => rand(2000, 100000),
                ]);
            $detail->total = $detail->qty * $detail->price_per_unit;
            $detail->save();
            $feedSale->total = $detail->total;
            $feedSale->save();
        }


    }
}
