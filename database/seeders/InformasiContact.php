<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Informasi_Contact;

class InformasiContact extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Informasi_Contact::create([
            'name_location' => 'Disana Jauh Pake Helm',
            'email_informasi' => 'geexeskplorer@gmail.com',
            'call_informasi' => '08000000',
        ]);
    }
}
