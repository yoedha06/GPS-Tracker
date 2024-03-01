<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;



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
            'plat_nomor' => ['required', 'max:255'],
            'photo' => ['nullable', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
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
            'plat_nomor' => $request->input('plat_nomor'), // Add plat_nomor to the Device instance
        ]);

        // Check if the 'photo' input is provided
        if ($request->hasFile('photo')) {
            // Handle file upload and store in the 'photos' folder
            $photoPath = $request->file('photo')->store('photos', 'public');
            $device->photo = $photoPath;
        } else {
            // 'photo' input is not provided, set it to null
            $device->photo = null;
        }
        $user->devices()->save($device);

        Session::flash('success', 'Berhasil Input Data.');

        return redirect()->route('customer.device.index');
    }
    public function deletePhoto($id)
    {
        $device = Device::findOrFail($id);

        // Delete the photo from storage
        if ($device->photo) {
            Storage::delete($device->photo);
            $device->photo = null;
            $device->save();
        }

        return response()->json(['message' => 'Photo deleted successfully']);
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

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'regex:/^[A-Za-z0-9\s]+$/', 'max:255'],
            'plat_nomor' => ['required', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ], [
            'name.regex' => 'Name can only contain letters, numbers, and spaces.',
            'photo.mimes' => 'The photo must be a valid image file (jpeg, png, jpg, gif).',
        ]);        

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        $device = Device::findOrFail($id);
        $device->name = $request->input('name');
        $device->plat_nomor = $request->input('plat_nomor');

        // Check if a new photo is provided
        if ($request->hasFile('photo')) {
            // Handle file upload and store in the 'photos' folder
            $photoPath = $request->file('photo')->store('photos', 'public');
            $device->photo = $photoPath;
        }

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

        // Hapus data history terkait dengan perangkat
        $device->history()->delete();

        // Hapus perangkat
        $device->delete();

        Session::flash('success', 'Data berhasil dihapus Beserta Device Historynya.');

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
