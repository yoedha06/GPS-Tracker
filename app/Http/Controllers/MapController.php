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
    
    // public function createLastLocation(Request $request)
    // {
        
    //     // Ambil data lokasi terakhir dari permintaan yang dikirim (latitude dan longitude)
    //     $latitude = $request->input('latitude');
    //     $longitude = $request->input('longitude');
    //     dd($request->all());
    //     // Buat entri baru di tabel History
    //     $history = new History([
    //         'latitude' => $latitude,
    //         'longitude' => $longitude,
    //         'date_time' => Carbon::now(), // Set waktu saat ini sebagai waktu pembuatan entri
    //     ]);
    //     $history->save();

    //     return response()->json(['message' => 'Data lokasi terakhir berhasil disimpan'], 200);
    // }
}
