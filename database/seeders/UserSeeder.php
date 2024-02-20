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
            "username" => "admin@example.com",
            "email" => "admin@example.com",
            "password" => bcrypt('admin123'),
        ]);

        User::create([
            "name" => "User",
            "username" => "user@example.com",
            "email" => "user@example.com",
            "password" => bcrypt('user123'),
            "role" => "Customer",
        ]);
    }
}
