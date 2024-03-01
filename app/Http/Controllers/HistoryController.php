<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /** 
     * Display a listing of the resource.
     */

    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch all devices associated with the authenticated user
        $devices = $user->devices ?? collect();

        // Fetch history records associated with the authenticated user's devices
        $deviceIds = $devices->pluck('id_device')->toArray(); // Convert to array

        // Use whereIn instead of where, as you're dealing with an array of device IDs
        $history = History::whereIn('device_id', $deviceIds)->get();

        return view('customer.history.index', ['history' => $history, 'devices' => $devices]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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

    public function getHistoryByDevice($deviceId)
    {
        logger('Request for device history. Device ID: ' . $deviceId);

        // Ensure $deviceId is valid and exists in the devices associated with the authenticated user
        $user = Auth::user();
        $device = $user->devices()->where('id_device', $deviceId)->first();

        if (!$device) {
            return response()->json(['error' => 'Invalid device ID'], 404);
        }

        // Fetch history records for the specified device
        $history = History::where('device_id', $deviceId)->get();

        logger('History data retrieved:', $history);

        // Include device information in the JSON response
        $response = [
            'device_name' => $device->name,
            'history' => $history,
        ];

        logger('Response sent:', $response);

        return response()->json($response);
    }
}
