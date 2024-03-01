<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Fetch all device IDs associated with the authenticated user
        $deviceIds = $user->devices->pluck('id_device');

        // Fetch history records associated with the authenticated user's devices
        $history = History::whereIn('device_id', $deviceIds)->get();

        return view('customer.history.index', ['history' => $history]);
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

    public function map()
    {
        $history = DB::table('history')->get();

        return view('customer.map.index', compact('history'));
    }
}
