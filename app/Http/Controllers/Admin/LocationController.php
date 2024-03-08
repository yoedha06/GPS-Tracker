<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\History;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $devices = Device::with('latestHistory', 'user')->get();
        $users = User::with('devices')->where('role', 'customer')->get();
        $history = History::all();

        return view('admin.map.lastlocation', compact('devices', 'users','history'));
    }

    public function getDeviceHistory($deviceId)
    {
        $deviceHistory = History::where('device_id', $deviceId)
            ->with(['device.user', 'device.latestHistory'])
            ->latest()
            ->limit(1)
            ->get();


        if ($deviceHistory) {
            return response()->json($deviceHistory);
        } else {
            return response()->json(['error' => 'Error fetching device history'], 500);
        }
    }
}
