<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\History;
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
        $device = Device::where('serial_number', $request->serial_number)->first();
    
        if ($device == null) {
            return response()->json([
                'massage' => 'Serial number tidak ditemukan',
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
    
        $history = new History();
        $history->device_id = $device->id_device;
        $history->latitude = $request->latitude;
        $history->longitude = $request->longitude;
        $history->bounds = $request->bounds;
        $history->accuracy = $request->accuracy;
        $history->altitude = $request->altitude;
        $history->altitude_acuracy = $request->altitude_acuracy;
        $history->heading = $request->heading;
        $history->speeds = $request->speeds;
        $history->date_time = $request->date_time;
        $history->save();
    
        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $history
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
