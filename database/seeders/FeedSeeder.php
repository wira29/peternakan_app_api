<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feed;

class FeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feeds = [
            [
                'name' => 'Pakan Kambing Fermentasi',
                'stock' => 100,
                'unit' => 'kg',
            ],
            
            [
                'name' => 'Pakan Domba Konsentrat',
                'stock' => 140,
                'unit' => 'kg',
            ],
        ];

        foreach ($feeds as $feed) {
            Feed::create($feed);
        }
    }
}
