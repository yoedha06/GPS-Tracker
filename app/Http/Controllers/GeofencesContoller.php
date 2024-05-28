<?php

namespace App\Http\Controllers;

use App\Models\geofences;
use Illuminate\Http\Request;

class GeofencesContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_id = $request->user()->id; // Assuming you have authentication in place and user is logged in
        $geofences = geofences::where('user_id', $user_id)->select('id', 'name', 'type', 'coordinates', 'radius')->get();
        return view('customer.map.geofences', ['geofences' => $geofences]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'type' => 'required|in:circle,polygon',
            'coordinates' => 'required',
            'radius' => 'required_if:type,circle|nullable|numeric',
        ]);

        $geofences = new geofences([
            'user_id' => auth()->id(),
            'name' => $request->get('name'),
            'type' => $request->get('type'),
            'coordinates' => $request->get('coordinates'),
            'radius' => $request->get('type') == 'circle' ? $request->get('radius') : null,
        ]);

        $geofences->save();

        return redirect()->route('customer.geofences.index')->with('success', 'Geofence created successfully.');
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
    // public function getNames(Request $request)
    // {
    //     $user_id = $request->user()->id; // Assuming you have authentication in place and user is logged in
    //     $names = geofences::where('user_id', $user_id)->select('id', 'name')->get();
    //     return response()->json($names);
    // }
}
