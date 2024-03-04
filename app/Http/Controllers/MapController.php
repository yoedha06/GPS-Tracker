<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function lastloc()
    {
        $devices = Device::all();
        
        $lastLocations = History::select('device_id', DB::raw('MAX(date_time) as latest'))
            ->groupBy('device_id')
            ->pluck('latest', 'device_id');

        $locations = History::whereIn('date_time', $lastLocations)->get();
        

        return view('customer.map.lastlocation', compact('devices', 'locations'));
    }

    // public function getLastLocation($deviceId)
    // {
    //     $lastLocation = History::where('device_id', $deviceId)->latest('date_time')->first();
    //     return response()->json($lastLocation);
    // }
}
