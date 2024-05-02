<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::create([
            'informasi' => 'ORANG ORANG SUKSES DAN BERIMAN',
            'username_1' => 'Dzaki Ahmad Fauzan',
            'username_2' => 'Yudha Hidayat',
            'username_3' => 'Chepy Syaehbudien Basil',
            'username_4' => 'Ryan Rahma Bakti',
            'posisi_1' => 'CIGS',
            'posisi_2' => 'CIGS',
            'posisi_3' => 'CIGS',
            'posisi_4' => 'CIGS',
            'deskripsi_1' => 'Belajarlah Dengan Giat Raihlah Gelar Sampai Dapat Dan Jangan Lupakan Gelar Sejadah',
            'deskripsi_2' => 'Ketika Hujan Tak Kunjung Berhenti Masi Ada Yuda Yang Siap Menemani',
            'deskripsi_3' => 'ORANG ORANG APA YANG KEREN? RAFII',
            'deskripsi_4' => 'ORANG ORANG APA YANG MANIS? RAFII',
        ]);
    }
}
