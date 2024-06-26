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

    $start = $request->start;
    $end = $request->end;

    if (! $start || ! $end) {
        return redirect(route('customer.map.index') . '?start=' . now()->subHour(3)->format('Y-m-d H:i:s') . '&end=' . now()->format('Y-m-d H:i:s'));
    }

    // Ambil data perangkat yang dimiliki oleh pengguna yang saat ini masuk dan memiliki riwayat
    $devicesWithUniqueHistory = Device::where('user_id', Auth::id())
        ->whereHas('history')
        ->orderByDesc(function($query) {
            $query->select('date_time')
                ->from('history')
                ->whereColumn('history.device_id', 'device.id_device')
                ->latest()
                ->limit(1);
        })
        ->get();

    $history = DB::table('history')
        ->whereIn('device_id', $devicesWithUniqueHistory->pluck('id_device'))
        ->where('date_time', '>=', $start)
        ->where('date_time', '<=', $end)
        ->get();

    // Ambil semua perangkat dengan batasan jumlah
    $devices = Device::where('user_id', Auth::id())
        ->limit(10) // Batasan jumlah perangkat
        ->get();

    // Ambil perangkat dengan riwayat terbaru
    $latestDevice = $devicesWithUniqueHistory->first();

    // Buat array untuk menyimpan nama perangkat berdasarkan ID perangkat
    $deviceNames = $devices->pluck('name', 'id_device')->toArray();

    // Melewatkan data ke view menggunakan compact
    return view('customer.map.index', compact('devicesWithUniqueHistory', 'history', 'devices', 'deviceNames', 'start', 'end', 'latestDevice'));
}



    public function filter(Request $request)
    {
        $user = Auth::user();
        // Ambil data yang diperlukan dari permintaan
        $selectedDevice = $request->selectedDevice;
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        // Pastikan tanggal-tanggal yang diterima adalah dalam format yang tepat
        $startDate = date('Y-m-d H:i:s', strtotime($startDate));
        $endDate = date('Y-m-d H:i:s', strtotime($endDate));

    // Ambil riwayat yang sesuai dengan rentang tanggal dan perangkat yang dipilih,
    // sertakan juga nama perangkat dari tabel device
     $historyData = History::with(['device' => function ($query) {
                    $query->select('id_device', 'name');
                }])
                ->whereHas('device', function ($query) use ($user) {
                    $query->where('user_id', $user->id); // Filter berdasarkan id pengguna
                })
                ->select('id_history', 'device_id', 'date_time', 'latitude', 'longitude', 'speeds', 'accuracy')
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->where('device_id', $selectedDevice);
                })
                ->whereBetween('date_time', [$startDate, $endDate])
                ->get(['speeds', 'accuracy']);// Mengambil langsung kolom speeds dan accuracy dari database
$polylinePoints = []; // Inisialisasi array untuk menyimpan titik polyline

        foreach ($historyData as $history) {
            $deviceName = $history->device->name;
            $speed = $history->speeds;
            $accuracy = $history->accuracy;

            // Lakukan apa pun yang Anda butuhkan dengan $deviceName, $speed, dan $accuracy di sini

            // Misalnya, tambahkan titik polyline menggunakan data latitude dan longitude
            $lat = $history->latitude;
            $lng = $history->longitude;
            $polylinePoints[] = ['lat' => $lat, 'lng' => $lng, 'speed' => $speed, 'accuracy' => $accuracy];
        }

        // Sekarang Anda memiliki semua titik polyline dalam $polylinePoints yang dapat Anda gunakan dalam JavaScript untuk membuat polyline
        // Anda bisa melewatkan $polylinePoints ke frontend Anda, kemudian gunakan dalam JavaScript seperti yang Anda lakukan sebelumnya


        // Kembalikan data dalam format JSON
        return response()->json($historyData);
    }
    public function filterHistory(Request $request)
    {
        // Ambil data yang diperlukan dari permintaan
        $selectedDevice = $request->selectedDevice;
        // $selectedUserId = $request->selectedUserId;
        $startDate = $request->startDate;
        $endDate = $request->endDate;


        // Pastikan tanggal-tanggal yang diterima adalah dalam format yang tepat
        $startDate = date('Y-m-d H:i:s', strtotime($startDate));
        $endDate = date('Y-m-d H:i:s', strtotime($endDate));

        $historyData = History::with(['device.user' => function ($query) {
            $query->select('id', 'name'); // Ambil hanya id dan name dari tabel users
        }])
            ->when($selectedDevice, function ($query) use ($selectedDevice) {
                $query->where('device_id', $selectedDevice);
            })
            // ->when($selectedUserId, function ($query) use ($selectedUserId) {
            //     $query->where('id', $selectedUserId);
            // })
            ->whereBetween('date_time', [$startDate, $endDate])
            ->get();
        // dd($historyData);
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



 public function showMap(Request $request)
{
    $start = $request->start;
    $end = $request->end;

    if (!$start || !$end) {
        // Jika $start atau $end kosong atau tidak valid, redirect ke halaman admin.map dengan waktu default
        $defaultEnd = now()->format('Y-m-d H:i:s');
        $defaultStart = now()->subHours(3)->format('Y-m-d H:i:s');
        return redirect(route('admin.map') . '?start=' . $defaultStart . '&end=' . $defaultEnd);
    }

    // Mengambil daftar pengguna
    $users = User::all();

    // Mengambil daftar perangkat beserta pengguna
    $devices = Device::with('user')->get();

    // Mengambil daftar riwayat
    $startOfDay = Carbon::parse($start)->startOfDay(); // Gunakan waktu awal dari permintaan
    $endOfDay = Carbon::parse($end)->endOfDay(); // Gunakan waktu akhir dari permintaan

    $history = History::where('date_time', '>=', $startOfDay)
                    ->where('date_time', '<=', $endOfDay)
                    ->get();

    // Membuat array nama perangkat yang berisi id perangkat sebagai kunci dan nama perangkat sebagai nilai
    $deviceNames = $devices->pluck('name', 'id_device')->toArray();
    $userNames = $devices->pluck('user.name', 'id_device')->toArray();

    // Dapatkan perangkat dengan data history terbaru
    $latestHistory = History::orderBy('date_time', 'desc')->first();
    $latestDeviceId = $latestHistory ? $latestHistory->device_id : null;
    $latestUserId = $latestHistory && $latestHistory->device && $latestHistory->device->user ? $latestHistory->device->user->id : null;

    // If device and user are not provided, use the latest ones
    $device = $request->device ?? $latestDeviceId;
    $user = $request->user ?? $latestUserId;

    return view('admin.map.index', [
        'users' => $users, // Mengirim data pengguna ke tampilan
        'devices' => $devices, // Mengirim data perangkat ke tampilan
        'history' => $history, // Mengirim data riwayat ke tampilan
        'deviceNames' => $deviceNames, // Mengirim data nama perangkat ke tampilan
        'userNames' => $userNames, // Mengirim data nama pengguna ke tampilan
        'latestDeviceId' => $latestDeviceId, // Mengirim ID perangkat terbaru ke tampilan
        'latestUserId' => $latestUserId, // Mengirim ID pengguna terbaru ke tampilan
        'selectedDevice' => $device,
        'selectedUser' => $user,
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
