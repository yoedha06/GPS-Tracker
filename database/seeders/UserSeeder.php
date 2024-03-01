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
            "name" => "yuda",
            "username" => "yuda.hidayat2005@gmail.com",
            "email" => "yuda.hidayat2005@gmail.com",
            "password" => bcrypt('1sampai8'),
            "role" => "admin",
        ]);

        User::create([
            "name" => "chepi",
            "username" => "chepisyahbudienbasil@gmail.com",
            "email" => "chepisyahbudienbasil@gmail.com",
            "password" => bcrypt('1234'),
            "role" => "admin",
        ]);

        User::create([
            "name" => "dzaki",
            "username" => "dzakijekjek@gmail.com",
            "email" => "dzakijekjek@gmail.com",
            "password" => bcrypt('12345'),
            "role" => "admin",
        ]);

        User::create([
            "name" => "Ryan",
            "username" => "baktiryan182@gmail.com",
            "email" => "baktiryan182@gmail.com",
            "password" => bcrypt('1234'),
            "role" => "admin",
        ]);
    }
}
