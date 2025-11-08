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
                'price'=> 10010
            ],
            
            [
                'name' => 'Pakan Domba Konsentrat',
                'stock' => 140,
                'unit' => 'kg',
                'price'=> 2300000
            ],
        ];

        foreach ($feeds as $feed) {
            Feed::create($feed);
        }
    }
}
