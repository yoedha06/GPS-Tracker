<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Device;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {

        $devices = Device::all();

        // Get the authenticated user
        $user = Auth::user();

        // Fetch all devices associated with the authenticated user
        $devices = $user->devices ?? collect();

        $deviceIds = $devices->pluck('id_device')->toArray(); // Convert to array

        $history = History::whereIn('device_id', $deviceIds)
            ->join('device', 'history.device_id', '=', 'device.id_device')
            ->orderBy('device.name', 'asc') // Order by device name in ascending order
            ->orderBy('date_time', 'desc')    // Then order by date_time in descending order
            ->paginate(10);


        return view('customer.history.index', ['history' => $history, 'devices' => $devices]);
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
    $user = Auth::user();

       // Ambil data perangkat yang dimiliki oleh pengguna yang saat ini masuk
       $devices = Device::where('user_id', Auth::id())->get();

        // Ambil riwayat dari basis data atau dari sumber lain jika diperlukan
        $history = DB::table('history')->get();

        // Melewatkan data ke view menggunakan compact
        return view('customer.map.index', compact('devices', 'history'));
    }
    public function getHistoryByDevice($deviceId)
    {
        logger('Request for device history. Device ID: ' . $deviceId);

        // Ensure $deviceId is valid and exists in the devices associated with the authenticated user
        $user = Auth::user();
        $device = $user->devices()->where('id_device', $deviceId)->first();

        if (!$device) {
            return response()->json(['error' => 'Invalid device ID'], 404);
        }

        // Fetch history records for the specified device
        $history = History::where('device_id', $deviceId)->get();

        logger('History data retrieved:', $history->toArray()); // Convert collection to array

        // Include device information in the JSON response
        $response = [
            'device_name' => $device->name,
            'history' => $history,
        ];

        // Log device name directly or convert it to an array
        logger('Device name:', $device->toArray()); // or logger('Device name: ' . $device->name);

        return response()->json($response);
    }


    public function getRelatedData($userId)

    {
        $devices = Device::where('user_id', $userId)
            ->with('history') // Memuat data history untuk setiap perangkat
            ->get();

        return response()->json([
            'devices' => $devices
        ]);
    }

    public function fetchData($deviceId)
    {
        // Ambil data terkait berdasarkan deviceId
        $relatedData = History::where('device_id', $deviceId)->get();

        // Sesuaikan respons JSON sesuai dengan kebutuhan Anda
        return response()->json([
            'related_data' => $relatedData
        ]);
    }



    public function showMap()
    {
        $user = Auth::user();

    // Mendapatkan daftar pengguna
    $users = DB::table('users')->get(); //Anda perlu mengimpor model User jika belum melakukannya

    $devices = Device::all();
    $history = DB::table('history')->get();

        // Mendapatkan daftar pengguna
        $users = User::all(); // Anda perlu mengimpor model User jika belum melakukannya

        $devices = DB::table('device')->get();
        $history = DB::table('history')->get();


        return view('admin.map.index', [
            'users' => $users, // Mengirim data pengguna ke tampilan
            'devices' => $devices,
            'history' => $history
        ]);
    }


    public function getHistoryData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $deviceId = $request->input('deviceId'); // Menambahkan input untuk device_id

        // Ambil data history dari database berdasarkan tanggal dan device_id
        $query = History::whereBetween('date_time', [$startDate, $endDate]);
        if ($deviceId) {
            $query->where('device_id', $deviceId);
        }
        $historyData = $query->get();

        // Mengembalikan data dalam bentuk JSON
        return response()->json(['historyData' => $historyData]);
    }


    public function filterByDate(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $deviceId = $request->input('deviceId');

        $filteredData = History::where('device_id', $deviceId)
            ->whereBetween('date_time', [$startDate, $endDate])
            ->get();

        return response()->json($filteredData);
    }
    public function getDeviceHistory(Request $request, $deviceId)
    {
        // Ambil riwayat perangkat dari database
        $startDate = $request->input('startDate', null);
        $endDate = $request->input('endDate', null);

        $deviceHistory = History::where('device_id', $deviceId);

        if ($startDate && $endDate) {
            $deviceHistory->whereBetween('date_time', [$startDate, $endDate]);
        }

        $deviceHistory = $deviceHistory->with(['device', 'device.user'])->get();

        // Ubah data riwayat perangkat ke format yang sesuai
        $formattedData = $deviceHistory->map(function ($history) {
            return [
                'latitude' => $history->latitude,
                'longitude' => $history->longitude,
                'deviceName' => $history->device->name, // Mengambil nama perangkat dari relasi device
                'userName' => $history->device->user->name, // Mengambil nama pengguna dari relasi user
                'dateTime' => $history->date_time->format('Y-m-d H:i:s') // Format tanggal sesuai kebutuhan Anda
            ];
        });

        return response()->json($formattedData);
    }
}