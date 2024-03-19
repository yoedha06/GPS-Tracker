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
        $history = History::all();
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $history
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'serial_number' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'speeds' => 'required',
            'date_time' => 'required|date',
            // Tambahkan validasi untuk field lainnya jika diperlukan
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


        $history = History::create([
            'device_id' => $device->id_device,
            // 'latitude' => $request->latitude,
            // 'longitude' => $request->longitude,
            // 'bounds' => $request->bounds,
            // 'accuracy' => $request->accuracy,
            // 'altitude' => $request->altitude,
            // 'altitude_acuracy' => $request->altitude_acuracy,
            // 'heading' => $request->heading,
            // 'speeds' => $request->speeds,
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
