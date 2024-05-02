<?php

namespace Database\Seeders;

use App\Models\About;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Masukkan data about ke dalam database
        About::create([
            'title_about' => 'GPS EXPLORER',
            'left_description' => 'GPS Explorer is a web-based application that simplifies the management of location-based data and navigation.',
            'right_description' => 'GPS Explorer provides a comprehensive platform for managing and exploring geographical data. It allows users to access and analyze location-related information efficiently. The digital mapping system aims to enhance traditional navigation methods and provide real-time insights into geographical data.',
            'feature_1' => 'gacor',
            'feature_2' => 'secepat kilat',
            'feature_3' => 'oke gutt lahhh',
        ]);
    }
}
