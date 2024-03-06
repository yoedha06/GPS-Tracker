<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\User;
use App\Models\History;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $devices = Device::with('latestHistory')->get();
        $users = User::all(); // Assuming you have a User model

        return view('admin.map.lastlocation', compact('devices', 'users'));
    }

    public function filterLocations(Request $request)
    {
        $filter = $request->input('filter');
        list($userId, $deviceId) = explode('_', $filter);

        $filteredData = History::where('user_id', $userId)
            ->where('device_id', $deviceId)
            ->with(['device', 'user'])
            ->get();

        return response()->json($filteredData);
    }
}
