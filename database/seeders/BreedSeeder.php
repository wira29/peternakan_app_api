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
                'name' => 'Kambing Kacang',
                'remarks' => 'Ras lokal Indonesia, dikenal sangat tahan banting dan mudah beradaptasi.'
            ],
            [
                'name' => 'Boer',
                'remarks' => 'Ras pedaging unggul dari Afrika Selatan. Pertumbuhan cepat.'
            ],
            [
                'name' => 'Etawa (PE)',
                'remarks' => 'Peranakan Etawa. Ras dwi-guna (susu dan daging) yang populer di Indonesia.'
            ],
            [
                'name' => 'Saanen',
                'remarks' => 'Ras perah dari Swiss. Produksi susu sangat tinggi, biasanya berwarna putih.'
            ],
            [
                'name' => 'Jawarandu',
                'remarks' => 'Persilangan antara Peranakan Etawa (PE) dan Kambing Kacang. Populer di Jawa.'
            ],
            [
                'name' => 'Nubian',
                'remarks' => 'Ras dwi-guna dengan telinga panjang dan hidung melengkung. Susu berkualitas tinggi.'
            ],
            [
                'name' => 'Alpine',
                'remarks' => 'Ras perah dari Pegunungan Alpen, sangat tangguh dan produksi susu baik.'
            ],
            [
                'name' => 'LaMancha',
                'remarks' => 'Ras perah yang unik karena telinganya sangat kecil atau hampir tidak ada.'
            ],
            [
                'name' => 'Kambing Gembrong',
                'remarks' => 'Ras lokal langka dari Bali, dikenal karena bulunya yang panjang.'
            ],
        ];

        foreach ($breeds as $breed) {
            Breed::create($breed);
        }
    }
}
