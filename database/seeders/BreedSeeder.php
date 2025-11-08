<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Breed;

class BreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $breeds = [
            [
                'id' => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f6',
                'name' => 'Kambing Kacang',
                'remarks' => 'Ras lokal Indonesia, dikenal sangat tahan banting dan mudah beradaptasi.'
            ],
            [
                'id' => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f7',
                'name' => 'Boer',
                'remarks' => 'Ras pedaging unggul dari Afrika Selatan. Pertumbuhan cepat.'
            ],
            [
                'id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f8',
                'name' => 'Etawa (PE)',
                'remarks' => 'Peranakan Etawa. Ras dwi-guna (susu dan daging) yang populer di Indonesia.'
            ],
            [
                'id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f9',
                'name' => 'Saanen',
                'remarks' => 'Ras perah dari Swiss. Produksi susu sangat tinggi, biasanya berwarna putih.'
            ],
            [
                'id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5fa',
                'name' => 'Jawarandu',
                'remarks' => 'Persilangan antara Peranakan Etawa (PE) dan Kambing Kacang. Populer di Jawa.'
            ],
            [
                'id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5fb',
                'name' => 'Nubian',
                'remarks' => 'Ras dwi-guna dengan telinga panjang dan hidung melengkung. Susu berkualitas tinggi.'
            ],
            [
                'id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5fc',
                'name' => 'Alpine',
                'remarks' => 'Ras perah dari Pegunungan Alpen, sangat tangguh dan produksi susu baik.'
            ],
            [
                'id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5fd',
                'name' => 'LaMancha',
                'remarks' => 'Ras perah yang unik karena telinganya sangat kecil atau hampir tidak ada.'
            ],
            [
                'id'=> 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5fe',
                'name' => 'Kambing Gembrong',
                'remarks' => 'Ras lokal langka dari Bali, dikenal karena bulunya yang panjang.'
            ],
        ];

        foreach ($breeds as $breed) {
            Breed::create($breed);
        }
    }
}
