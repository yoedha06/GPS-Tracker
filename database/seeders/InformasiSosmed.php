<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Informasi_Sosmed;

class InformasiSosmed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Informasi_Sosmed::create([
            'title_sosmed' => 'GOAT KING IDOL RAFI',
            'street_name' => 'jalan',
            'subdistrict' => 'jalan',
            'ward' => 'jalan',
            'call' => 'jalan',
            'email' => 'jalan',
        ]);
    }
}
