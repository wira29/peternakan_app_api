<?php

namespace Database\Seeders;

use App\Models\Feed;
use App\Models\FeedLocation;
use App\Models\FeedPurchase;
use App\Models\FeedPurchaseDetail;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedPurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feeds = Feed::all();
        $locations = Location::all()->pluck('id');

        $staticPurchaseIds = [
            'f3a9c2b0-8d4e-4e3d-bc7a-12a4f9cd8e11',
            '9b7d1f24-3c55-4a2a-9fd0-4e8a1b6c0b77',
            '6c41dd3e-2a5b-4bf2-9f1c-889ff3b2ac32',
        ];
        $staticDetailIds = collect([
            'd8a90ef1-6b49-4f2e-8cd0-2b49ee71cb58',
            'a4c7dd24-8e08-4e62-b4cf-1f9f894d3a90',
            'e92bbf1c-55a1-4db6-8b61-cf8a9c43e611',
        ]);


        foreach ($staticPurchaseIds as $staticId) {
            $location = $locations->shift();
            $feed_rand = $feeds->random();
            $qty = rand(1, 30);
            $feed_location = $feed_rand->syncToLocation($location, $qty);

            $feedPurchase = FeedPurchase::create([
                'id'      => $staticId,
                'location_id' => $location,
                'supplier_name' => "Supplier ABC",
                'purchase_date'    => now(),
            ]);


            $detail = FeedPurchaseDetail::create(
                [
                    'id' => $staticDetailIds->shift(),
                    'feed_purchase_id' => $feedPurchase->id,
                    'feed_location_id' => $feed_location->id,
                    'qty'     => $qty,
                    'price_per_unit' => rand(2000, 100000),
                ]
            );
            $detail->total = $detail->qty * $detail->price_per_unit;
            $detail->save();
            $feedPurchase->total_amount = $detail->total;
            $feedPurchase->save();
        }
    }
}
