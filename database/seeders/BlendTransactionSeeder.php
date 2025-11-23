<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feed;
use App\Models\Material;
use App\Models\BlendTransaction;

class BlendTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feeds = Feed::all()->pluck('id');

        $staticBlendIds = [
            '019a6288-46cf-7069-986a-8ddc109bb24c',
            '019a6299-aaaa-bbbb-cccc-ddddeeeeffff', 
            '019a62aa-1111-2222-3333-444455556666',
        ];

        foreach ($staticBlendIds as $staticId) {
            $blend = BlendTransaction::create([
                'id'      => $staticId,         
                'feed_id' => $feeds->random(),  
                'qty'     => rand(10, 100),     
                'date'    => now(),
            ]);
        }

    }
}
