<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        // dd($user);  // Check if user data is as expected

        $userDevices = $user->devices ?? collect();

        return view('customer.device.index', ['device' => $userDevices]);
    }


    public function search(Request $request)
    {
        $query = $request->get('q');
        $devices = Device::where('name', 'LIKE', "%{$query}%")->paginate(10);

        $data = [
            'items' => $devices->items(),
            'pagination' => [
                'more' => $devices->hasMorePages()
            ]
        ];

        return response()->json($data);
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
        $user = Auth::user();
        $device = new Device([
            'name' => $request->input('name'),
            'serial_number' => $request->input('serial_number'),
        ]);
        $user->devices()->save($device);  // Use 'devices()' instead of 'device()'

        return redirect()->route('customer.device.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $device = Device::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'serial_number' => 'required|unique:device,serial_number,' . $id . ',id_device',
        ]);

        $device->update($request->all());

        return redirect()->route('customer.device.index')->with('success', 'Device updated successfully.');
    }



    /** 
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $device->delete();

        return redirect()->route('customer.device.index')->with('success', 'Device deleted successfully.');
    }


    public function indexadmin(Request $request)
    {
        $users = User::all();
        $userId = $request->input('user');

        if ($userId) {
            $device = Device::whereHas('user', function ($query) use ($userId) {
                $query->where('id', $userId);
            })->get();
        } else {
            $device = Device::all();
        }

        return view('admin.device.index', compact('device', 'users'));
    }
}
