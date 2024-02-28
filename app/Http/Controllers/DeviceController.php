<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;



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

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'regex:/^[A-Za-z0-9\s]+$/', 'max:255'],
            'serial_number' => ['required', 'regex:/^[A-Za-z0-9\s]+$/', 'string', 'max:50', 'unique:device,serial_number'],
        ], [
            'name.regex' => 'Name can only contain letters, numbers, and spaces.',
            'serial_number.regex' => 'Serial Number can only contain letters, numbers, and spaces.',
        ]);

        $validator->after(function ($validator) use ($request) {
            $existingDevice = Device::where('serial_number', $request->input('serial_number'))->first();
            if ($existingDevice) {
                $validator->errors()->add('serial_number', 'Serial number sudah digunakan di device lain.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        $device = new Device([
            'name' => $request->input('name'),
            'serial_number' => $request->input('serial_number'),
        ]);

        $user->devices()->save($device);

        Session::flash('success', 'Berhasil Input Data.');

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
        $user = Auth::user();

        // Validasi input seperti pada metode store

        $device = Device::findOrFail($id);
        $device->name = $request->input('name');
        $device->save();

        Session::flash('success', 'Data berhasil diupdate.');

        return redirect()->route('customer.device.index');
    }


    /** 
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $device->delete();

        Session::flash('success', 'Data berhasil dihapus.');

        return redirect()->route('customer.device.index');
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

    public function filter(Request $request)
    {
        $userId = $request->input('userId');
    
        // If $userId is empty, retrieve all devices with user information
        if (empty($userId)) {
            $devices = Device::with('user')->get();
        } else {
            // Retrieve devices based on the selected user ID with user information
            $devices = Device::where('user_id', $userId)->with('user')->get();
        }
    
        return response()->json($devices);
    }
    
}
