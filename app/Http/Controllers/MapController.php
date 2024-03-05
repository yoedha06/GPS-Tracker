<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

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

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        return response()->json([
            'name' => $device->name,
            'latitude' => $device->latitude,
            'longitude' => $device->longitude,
            'plat_nomor' => $device->plat_nomor,
            'photo' => asset('storage/' . $device->photo),
        ]);
    }
}
