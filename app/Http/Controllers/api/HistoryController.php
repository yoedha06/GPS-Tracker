<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $history =  History::all();
        return response()->json([
            'status' => true,
            'masage' => 'success',
            'data' => $history
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $history = [
            'device_id' => $request->device_id,
            'latlng' => $request->latlng,
            'bounds' => $request->bounds,
            'accuracy' => $request->accuracy,
            'altitude' => $request->altitude,
            'altitude_acuracy' => $request->altitude_acuracy,
            'heading' => $request->heading,
            'speeds' => $request->speeds
        ];

        $history = new History();
        $history->device_id = $request->device_id;
        $history->latlng = $request->latlng;
        $history->bounds = $request->bounds;
        $history->accuracy = $request->accuracy;
        $history->altitude = $request->altitude;
        $history->altitude_acuracy = $request->altitude_acuracy;
        $history->heading = $request->heading;
        $history->speeds = $request->speeds;
        $history->save();

        return response()->json([
            'status' => true,
            'massage' => 'success',
            'data' => $history
        ]);
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
