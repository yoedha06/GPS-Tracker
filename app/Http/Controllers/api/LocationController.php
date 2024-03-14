<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => Location::all(),
        ]);
    }

    public function store(Request $request)
    {
        $location = $request->validate([
            'lat' => 'required',
            'lon' => 'required',
        ]);

        $location = Location::create([
            'lat' => $request->lat,
            'lon' => $request->lon,
            'original' => json_encode($request->all())
        ]);

        return response()->json([
            'message' => true,
            'data' => $location
        ], 201);
    }
}
