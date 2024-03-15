<?php


namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => location::all(),
        ]);
    }

    public function store(Request $request)
    {

        $location = location::create([
            'original' => json_encode($request->all())
        ]);

        return response()->json([
            'message' => true,
            'data' => $location
        ], 201);
    }
}
