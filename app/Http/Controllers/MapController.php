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

        $userDevices = $userDevices->sortBy('name');
    
        foreach ($userDevices as $device) {
            $latestHistory = $device->history()->latest('date_time')->first();
            $latestHistories->push($latestHistory);
        }
    
        $latestHistories = $latestHistories->filter(function ($history) {
            return $history !== null;
        });
    
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

    public function getLastLocation($deviceId)
    {
    
        $location = History::where('device_id', $deviceId)
                ->orderBy('date_time', 'desc')
                ->first();

        if ($location) {
            $device = Device::find($location->device_id);

            if ($device) {
                $location->name = $device->name;
                $location->plate_number = $device->plat_nomor;
                $location->photo = $device->photo;
            }

            return response()->json($location);

        } 
        else {
            return response()->json(['error' => 'Location not found'], 404);
        }
    }

    public function getLatestLocation($deviceId)
    {
        
        $location = History::where('device_id', $deviceId)
            ->orderBy('date_time', 'desc')
            ->first();

            if ($location) {
                $device = Device::find($location->device_id);
    
                if ($device) {
                    $location->name = $device->name;
                    $location->plate_number = $device->plat_nomor;
                    $location->photo = $device->photo;
                }
    
                return response()->json($location);
    
            } 
            else {
                return response()->json(['error' => 'Location not found'], 404);
            }
    }

}
