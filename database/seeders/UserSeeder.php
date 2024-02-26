<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name" => "Admin",
            "username" => "yuda.hidayat2005@gmail.com",
            "email" => "yuda.hidayat2005@gmail.com",
            "password" => bcrypt('admin123'),
            "role" => "admin",
        ]);

        User::create([
            "name" => "Admin",
            "username" => "chepisyahbudienbasil@gmail.com",
            "email" => "chepisyahbudienbasil@gmail.com",
            "password" => bcrypt('1234'),
            "role" => "admin",
        ]);
    }
}
