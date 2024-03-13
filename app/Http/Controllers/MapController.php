<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class MapController extends Controller
{
    public function lastloc()
    {
        $user = Auth::user();
        $userDevices = $user->devices ?? collect();
        $latestHistories = collect();

        foreach ($userDevices as $device) {
            $latestHistory = $device->history()->latest('date_time')->first();
            $latestHistories->push($latestHistory);
        }

        return view('customer.map.lastlocation', compact('latestHistories', 'userDevices', 'user'));
    }

    public function deviceuser($id_device)
    {
        $device = Device::find($id_device);
        $history = History::where('device_id', $id_device)->orderBy('date_time', 'desc')->first();

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        if (!$history) {
            return response()->json(['error' => 'Device history not found'], 404);
        }

        return response()->json([
            'name' => $device->name,
            'latitude' => $history->latitude,
            'longitude' => $history->longitude,
            'plat_nomor' => $device->plat_nomor,
            'date_time' => $history->date_time,
            'photo' => asset('storage/' . $device->photo),
        ]);
    }

    public function getDeviceLocations($id_device)
    {
        $first_location = History::where('device_id', $id_device)
                                ->orderBy('date_time', 'asc')
                                ->first();

        $latest_location = History::where('device_id', $id_device)
                                ->orderBy('date_time', 'desc')
                                ->first();

        if ($first_location && $latest_location) {
            $locations = History::where('device_id', $id_device)
                                ->orderBy('date_time', 'asc')
                                ->orWhere('date_time', $latest_location->date_time)
                                ->get();
    
        $device = Device::find($id_device);

        return response()->json([
                'first_location' => $first_location,
                'latest_location' => $latest_location,
                'device' => $device,
                'locations' => $locations
        ]);
        } else {
            return response()->json(['error' => 'No locations found for the device'], 404);
        }
    }

}
