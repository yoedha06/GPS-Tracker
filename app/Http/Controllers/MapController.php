<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        // Fetch devices from the database
        $devices = Device::all();

        // Pass the $devices variable to the view
        return view('customer.map.index', compact('devices'));
    }

    public function selectDevice(Request $request)
    {
        // Ambil data perangkat berdasarkan input pengguna
        $searchTerm = $request->input('term');
        $devices = Device::where('name', 'LIKE', "%$searchTerm%")->get();

        // Format data dalam bentuk yang diperlukan oleh Select2
        $formattedDevices = [];
        foreach ($devices as $device) {
            $formattedDevices[] = ['id_device' => $device->id_device, 'text' => $device->name];
        }

        // Kembalikan data dalam format JSON
        return response()->json($formattedDevices);
    }
}
