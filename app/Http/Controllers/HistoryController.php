<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Device;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
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
        ->orderBy('date_time', 'desc')    // Order by date_time in descending order first
        ->orderBy('device.name', 'asc')   // Then order by device name in ascending order
        ->paginate(20);

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






    public function map(Request $request)
    {
        $user = Auth::user();

         $startTime = $request->star_date ?? now()->format('Y-m-d') . ' 00:00:00';
         $endTime = $request->end_date ?? now()->format('Y-m-d') . ' 23:59:59';

        // Ambil data perangkat yang dimiliki oleh pengguna yang saat ini masuk dan memiliki riwayat
        $devicesWithUniqueHistory = Device::where('user_id', Auth::id())
            ->whereHas('history')
            ->get();

        // Ambil semua riwayat dari basis data dengan batasan 100 riwayat
        $history = DB::table('history')
        ->whereIn('device_id', $devicesWithUniqueHistory->pluck('id_device'))
        ->where('date_time', '>=', $startTime)
        ->where('date_time', '<=', $endTime)
        ->get();

        // Ambil semua perangkat dengan batasan jumlah
        $devices = Device::where('user_id', Auth::id())
            ->limit(10) // Batasan jumlah perangkat
            ->get();

        // Buat array untuk menyimpan nama perangkat berdasarkan ID perangkat
        $deviceNames = $devices->pluck('name', 'id_device')->toArray();


        // Melewatkan data ke view menggunakan compact
       return view('customer.map.index', compact('devicesWithUniqueHistory', 'history', 'devices', 'deviceNames', 'startTime', 'endTime'));
    }


public function filter(Request $request)
{
    // Ambil data yang diperlukan dari permintaan
    $selectedDevice = $request->selectedDevice;
    $startDate = $request->startDate;
    $endDate = $request->endDate;

    // Pastikan tanggal-tanggal yang diterima adalah dalam format yang tepat
    $startDate = date('Y-m-d H:i:s', strtotime($startDate));
    $endDate = date('Y-m-d H:i:s', strtotime($endDate));

    // Ambil riwayat yang sesuai dengan rentang tanggal dan perangkat yang dipilih
    $historyData = History::with('device')
                    ->when($selectedDevice, function ($query) use ($selectedDevice) {
                        $query->where('device_id', $selectedDevice);
                    })
                    ->whereBetween('date_time', [$startDate, $endDate])
                    ->get();

    // Iterasi melalui data riwayat dan ambil nama perangkat untuk setiap entri
    foreach ($historyData as $history) {
        $deviceName = $history->device->name;

    }

    // Kembalikan data dalam format JSON
    return response()->json($historyData);
}



public function filterByDeviceAndUser(Request $request)
{
    // Ambil input rentang tanggal, deviceId, dan userId dari permintaan
    $start = $request->input('start');
    $end = $request->input('end');
    $deviceId = $request->input('deviceId');
    $userId = $request->input('userId');

    // Panggil metode untuk memproses data dengan rentang tanggal, deviceId, dan userId
    $filteredData = $this->processDataByDeviceAndUser($start, $end, $deviceId, $userId);

    // Kembalikan respons JSON dengan data yang difilter
    return response()->json(['filteredData' => $filteredData]);
}

private function processDataByDeviceAndUser($start, $end, $deviceId)
{
    // Ambil data dari model History berdasarkan rentang tanggal dan id perangkat
    $filteredData = History::join('device', 'history.device_id', '=', 'device.id_device')
        ->where('history.device_id', $deviceId)
        ->whereBetween('history.date_time', [$start, $end])
        ->select('history.*') // Memilih semua kolom dari tabel history
        ->addSelect('device.user_id') // Menambahkan kolom user_id dari tabel device
        ->get();

    // Return data yang telah difilter
    return $filteredData;
}



// public function pollData(Request $request)
// {
//     // Ambil lastPollTime dari session jika ada, atau set ke null jika belum diset
//     $lastPollTime = $request->session()->get('lastPollTime');

//     dd($request->last);

//     // Lakukan query untuk m    endapatkan data baru dari tabel history dengan date_time lebih baru dari $lastPollTime
// $newData = History::where('date_time', '>', $lastPollTime)->get();


//     // Perbarui lastPollTime ke waktu server saat ini
//     $lastPollTime = Carbon::now();

//     // Simpan lastPollTime ke session untuk digunakan pada polling selanjutnya
//     $request->session()->put('lastPollTime', $lastPollTime);

//     // Kirim respons dengan data baru ke klien
//     return response()->json(['newData' => $newData]);
// }


  public function getHistoryByDevice(Request $request, $deviceId)
{
    logger('Request for device history. Device ID: ' . $deviceId);

    // Ensure $deviceId is valid and exists in the devices associated with the authenticated user
    $user = Auth::user();
    $device = $user->devices()->where('id_device', $deviceId)->first();

    if (!$device) {
        return response()->json(['error' => 'Invalid device ID'], 404);
    }

    // Fetch history records for the specified device with pagination
    $perPage = $request->query('perPage', 20); // Jumlah data per halaman
    $history = History::where('device_id', $deviceId)
        ->orderBy('date_time', 'desc') // Urutkan berdasarkan tanggal secara descending
        ->paginate($perPage);

    // Transform history data
    $history->transform(function ($item, $key) {
        $itemArray = $item->toArray(); // Convert object to array
        $itemArray['altitude_accuracy'] = $item->altitude_acuracy;
        unset($itemArray['altitude_acuracy']); // Remove altitude_acuracy attribute
        return $itemArray;
    });

    // Include device information and history data in the response
    $response = [
        'device_name' => $device->name,
        'history' => $history->items(), // Get items on that page
        'pagination' => [
            'total' => $history->total(),
            'per_page' => $history->perPage(),
            'current_page' => $history->currentPage(),
            'last_page' => $history->lastPage(),
            'from' => $history->firstItem(),
            'to' => $history->lastItem(),
        ],
    ];

    // Log device name
    logger('Device name: ' . $device->name);

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
    // Mengambil daftar pengguna
    $users = User::all();

    // Mengambil daftar perangkat beserta relasi riwayat terakhir dan pengguna
    $devices = Device::with('latestHistory', 'user')->get();

    // Mengambil daftar riwayat
    $startOfDay = Carbon::now()->startOfDay();
    $endOfDay = Carbon::now()->endOfDay();

    $history = History::where('date_time', '>=', $startOfDay)
                        ->where('date_time', '<=', $endOfDay)
                        ->get();

    // Membuat array serial number yang berisi id perangkat sebagai kunci dan serial number sebagai nilai
    $serialNumbers = $devices->pluck('serial_number', 'id_device');

    // Membuat array nama perangkat yang berisi id perangkat sebagai kunci dan nama perangkat sebagai nilai
    $deviceNames = $devices->pluck('name', 'id_device')->toArray();

    // Membuat array nama pengguna yang berisi id perangkat sebagai kunci dan nama pengguna sebagai nilai
    // Menggunakan relasi 'user' untuk mengambil nama pengguna
    $userNames = $devices->pluck('user.name', 'id_device')->toArray();

    return view('admin.map.index', [
        'users' => $users, // Mengirim data pengguna ke tampilan
        'devices' => $devices, // Mengirim data perangkat ke tampilan
        'history' => $history, // Mengirim data riwayat ke tampilan
        'serialNumbers' => $serialNumbers, // Mengirim data serial number ke tampilan
        'deviceNames' => $deviceNames, // Mengirim data nama perangkat ke tampilan
        'userNames' => $userNames, // Mengirim data nama pengguna ke tampilan
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

    public function fetchLatestData()
   {
    try {
        // Ambil data terbaru dari tabel history
        $latestData = History::latest()->get(); // Misalnya, mengambil semua data terbaru

        // Kembalikan data sebagai respons JSON
        return response()->json(['data' => $latestData]);
    } catch (\Exception $e) {
        // Tangani jika terjadi kesalahan saat mengambil data dari tabel history
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
