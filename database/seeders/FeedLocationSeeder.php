<?php

namespace Database\Seeders;

use App\Models\Feed;
use App\Models\FeedLocation;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feeds = Feed::all();
        $locations = Location::all();

        $staticFeeds = collect([
            'a4f1d8c2-3e5b-4a9f-b7d6-8c0e1f2a3b4c',
            'b9e2f5a1-7c8d-4e0b-9a3f-6d5c2e1b4a8f',
            'c3d6e9f2-0b1a-4d7c-8e5f-9a2b3c4d5e6f',
            'd8a1c4e7-2f3b-46d9-0c5e-1f8a2b3c4d7e',
            'e5b2d9f4-6a8c-41e7-3b0d-5f9c2e1a3b4d',
            'f1c3e6a9-4d7b-40f2-8e5a-9c1d2b3e4f5a',
            '0a2b3c4d-5e6f-47a8-9b0c-1d2e3f4a5b6c',
            '1e2f3a4b-5c6d-48e9-0f1a-2b3c4d5e6f7a',
            '2b3c4d5e-6f7a-49b0-1c2d-3e4f5a6b7c8d',
            '3c4d5e6f-7a8b-40c1-2d3e-4f5a6b7c8d9e',
        ]);

        foreach($locations as $location) {
            foreach($feeds as $feed) {
               FeedLocation::create([
                    'id' => $staticFeeds->shift(),
                    'feed_id' => $feed->id,
                    'location_id' => $location->id,
                    'name' => $feed->name,
                    'unit' => $feed->unit,
                    'stock' => rand(50, 200),
                ]); 
            }
        }
    }
}
