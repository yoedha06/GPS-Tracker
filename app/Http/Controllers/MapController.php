<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MapController extends Controller
{
    public function lastloc()
    {
    
        // Mengambil semua perangkat
    $devices = Device::all();

    // Mengambil data history terbaru untuk setiap perangkat
    $latestHistories = collect();
    foreach ($devices as $device) {
        $latestHistory = $device->history()->latest('date_time')->first();
        $latestHistories->push($latestHistory);
    }

    return view('customer.map.lastlocation', compact('latestHistories','devices'));
        
    }
}
