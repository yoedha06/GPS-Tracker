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
        Location::create(['original' => json_encode($request->all())]);

        return response()->json([
            'message' => true,
        ], 201);
    }
}
