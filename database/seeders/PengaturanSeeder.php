<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengaturan;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Masukkan data pengaturan ke dalam database
        Pengaturan::create([
            'title_pengaturan' => 'RAFI TAMPAN DAN PEMBERANI',
            'name_pengaturan' => 'SELEBEWKEN BROKK',
        ]);
    }
}
