<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Seed data for the Device model
        Device::create([
            'user_id' => 1,
            'name' => 'Sample Device 1',
            'serial_number' => 'SN123456',
            'plat_nomor' => 'ABC123',
        ]);

        Device::create([
            'user_id' => 2,
            'name' => 'Sample Device 2',
            'serial_number' => 'SN789012',
            'plat_nomor' => 'XYZ789',
        ]);
    }
}
