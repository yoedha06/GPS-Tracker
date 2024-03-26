<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => History::all()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        // Validasi tambahan untuk memeriksa nilai "none"
        if ($requestData['latitude'] == 'none' || $requestData['longitude'] == 'none' || $requestData['altitude'] == 'none' || $requestData['speeds'] == 'none') {
            return response()->json(['error' => 'Nilai latitude, longitude, dan altitude tidak boleh bernilai none'], 401);
        } elseif ($requestData['latitude'] == null || $requestData['longitude'] == null || $requestData['altitude'] == null || $requestData['speeds'] == null) {
            return response()->json(['error' => 'Nilai latitude, longitude, dan altitude tidak boleh bernilai null'], 402);
        }

        // Validasi input}
        $request->validate([
            'serial_number' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'altitude' => 'required',
            'speeds' => 'required',
            'date_time' => 'required|date',
        ]);

        // Setelah validasi, Anda bisa melanjutkan dengan logika asli Anda
        $device = Device::where('serial_number', $request->serial_number)->first();

        if ($device == null) {
            return response()->json([
                'message' => 'Serial number tidak ditemukan',
            ], 404);
        }


        $existingHistory = History::where('device_id', $device->id_device)
            ->where('date_time', $request->date_time)
            ->first();

        if ($existingHistory) {
            if ($existingHistory->device_id == $device->id_device) {
                return response()->json([
                    'message' => 'Data dengan tanggal waktu yang sama sudah ada untuk perangkat yang sama',
                ], 403);
            }
        }

        $date_time = Carbon::parse($request->date_time)->format('Y-m-d H:i:s');

        $accuracy = floatval($request->accuracy);
        if ($accuracy >= 100) {
            $accuracy  /= 100;
        } elseif ($accuracy <= 99) {
            $accuracy /= 10;
        }

        $history = History::create([
            'device_id' => $device->id_device,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            // 'bounds' => $request->bounds,
            'accuracy' => $accuracy,
            'altitude' => floatval($request->altitude),
            'altitude_acuracy' => $request->altitude_acuracy,
            'heading' => floatval($request->heading),
            'speeds' => floatval($request->speeds),
            'date_time' => $date_time,
            'original' => json_encode($request->all())
        ]);


        return response()->json([
            'message' => true,
            'status' => $history,
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
