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
                'id' => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f6',
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
