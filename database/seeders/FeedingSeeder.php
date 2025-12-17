<?php

namespace Database\Seeders;

use App\Models\Cage;
use App\Models\Feed;
use App\Models\Feeding;
use App\Models\FeedLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staticUuids = [
            '00000000-0000-0000-0000-000000000001',
            '00000000-0000-0000-0000-000000000002',
            '00000000-0000-0000-0000-000000000003',
            '00000000-0000-0000-0000-000000000004',
            '00000000-0000-0000-0000-000000000005',
            '00000000-0000-0000-0000-000000000006',
            '00000000-0000-0000-0000-000000000007',
            '00000000-0000-0000-0000-000000000008',
            '00000000-0000-0000-0000-000000000009',
            '00000000-0000-0000-0000-000000000010',
        ];

        $cages = Cage::all();
        foreach ($cages as $index => $cage) {
            if (!isset($staticUuids[$index])) {
                break; 
            }
            $feed = FeedLocation::all()->random();
            if ($feed) {
                Feeding::create([
                    'id'      => $staticUuids[$index], 
                    'cage_id' => $cage->id,
                    'feed_location_id' => $feed->id,
                    'qty'     => rand(5, 15),
                    'date'    => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
