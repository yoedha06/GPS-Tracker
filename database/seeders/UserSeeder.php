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
            "name" => "user",
            "username" => "user@gmail.com",
            "email" => "user@gmail.com",
            "password" => bcrypt('1sampai8'),
            "role" => "customer",
        ]);

        User::create([
            "name" => "admin",
            "username" => "admin@gmail.com",
            "email" => "admin@gmail.com",
            "password" => bcrypt('1234'),
            "role" => "admin",
        ]);
    }
}
