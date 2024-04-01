<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\History;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TampilanController extends Controller
{
    public function index()
    {
        // Dapatkan pengguna yang saat ini masuk
        $user = Auth::user();

        // Hitung jumlah history keseluruhan
        $historyTotal = History::count();

        // Hitung jumlah perangkat yang dimiliki oleh pengguna tersebut
        $deviceCount = $user->devices()->count();

        // Ambil daftar perangkat dari data history
        $historyDevices = History::join('device', 'history.device_id', '=', 'device.id_device')
            ->where('device.user_id', $user->id) // Filter data berdasarkan user
            ->distinct()
            ->select('device.id_device', 'device.name')
            ->get();

        // Ambil data jumlah history dari setiap device beserta nama perangkat
        $historyData = History::join('device', 'history.device_id', '=', 'device.id_device')
            ->where('device.user_id', $user->id) // Filter data berdasarkan user
            ->select('device.name', DB::raw('count(*) as count'))
            ->groupBy('history.device_id', 'device.name')
            ->get();

        // Jumlahkan total history perangkat pengguna
        $totalHistoryPerDevice = $historyData->sum('count');

        // Ambil daftar perangkat yang dimiliki oleh pengguna
        $devices = $user->devices;

        return view('customer.index', compact('user', 'deviceCount', 'historyData', 'devices', 'historyTotal', 'totalHistoryPerDevice', 'historyDevices'));
    }


    public function admin()
    {
        // Get total counts
        $devices = Device::all();
        $usersCount = User::count();
        $deviceCount = Device::count();
        $history = History::count();
        // Get total history count across all devices
        $totalHistory = History::count();

        // Get all devices with their corresponding history count and user's name
        $historyData = Device::leftJoin('history', 'device.id_device', '=', 'history.device_id')
            ->join('users', 'device.user_id', '=', 'users.id')
            ->select('users.name as user_name', 'device.name as device_name', DB::raw('COUNT(history.id_history) as count'))
            ->groupBy('device.id_device', 'device.name', 'users.name')
            ->get();

        // Get authenticated user
        $user = auth()->user();

        return view('admin.index', compact('user', 'usersCount', 'deviceCount', 'totalHistory', 'historyData', 'history', 'devices'));
    }


    public function homepage()
    {
        //tampilan homepage
        return view('layouts.homepage');
    }

    public function customer(Request $request)
    {
        $selectedDate = $request->input('selected_date');
        $selectedDevice = $request->input('selected_device');
        $selectedChart = $request->input('selected_chart'); // Tambahkan input selected_chart

        // Dapatkan pengguna yang saat ini masuk
        $user = Auth::user();

        $query = History::query()->whereDate('date_time', $selectedDate);

        // Jika perangkat dipilih, tambahkan kondisi where untuk perangkat
        if ($selectedDevice) {
            $query->whereHas('device', function ($query) use ($selectedDevice) {
                $query->where('name', $selectedDevice)->where('user_id', Auth::id()); // tambahkan kondisi where untuk user_id
            });
        } else {
            // Jika tidak ada perangkat yang dipilih, tambahkan kondisi where untuk hanya menampilkan riwayat dari perangkat milik pengguna saat ini
            $query->whereHas('device', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        // Ambil data history sesuai dengan tanggal yang dipilih
        $historyData = $query->get();

        // Jika tidak ada data, kembalikan respons kosong
        if ($historyData->isEmpty()) {
            return response()->json([
                'data' => [],
                'deviceOptions' => [],
                'deviceCount' => 0
            ]);
        }

        // Persiapkan data untuk ditampilkan di chart berdasarkan pilihan chart yang dipilih
        $chartData = [];

        // Sesuaikan kueri untuk mengambil data berdasarkan jenis chart yang dipilih
        if ($selectedChart === 'speed') {
            $historyQuery = History::query()
                ->select('date_time', 'speeds as count')
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'accuracy') { // Tambahkan logika untuk opsi "Accuracy"
            $historyQuery = History::query()
                ->select('date_time', 'accuracy as count') // Memilih kolom date_time dan accuracy
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'heading') { // Tambahkan logika untuk opsi "Accuracy"
            $historyQuery = History::query()
                ->select('date_time', 'heading as count') // Memilih kolom date_time dan accuracy
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'altitude_acuracy') { // Tambahkan logika untuk opsi "Accuracy"
            $historyQuery = History::query()
                ->select('date_time', 'altitude_acuracy as count') // Memilih kolom date_time dan accuracy
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'latitude') { // Tambahkan logika untuk opsi "Accuracy"
            $historyQuery = History::query()
                ->select('date_time', 'latitude as count') // Memilih kolom date_time dan accuracy
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'longitude') { // Tambahkan logika untuk opsi "Accuracy"
            $historyQuery = History::query()
                ->select('date_time', 'longitude as count') // Memilih kolom date_time dan accuracy
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();

            $chartData = $historyQuery->toArray();
        } else {
            foreach ($historyData as $data) {
                $dateTime = is_string($data->date_time) ? new \DateTime($data->date_time) : $data->date_time;

                $date = $dateTime->format('Y-m-d H:i:s');
                $value = 1;

                if (isset($chartData[$date])) {
                    $chartData[$date]['count'] += $value;
                } else {
                    $chartData[$date] = [
                        'date_time' => $date,
                        'count' => $value
                    ];
                }
            }
        }

        // Ambil daftar perangkat yang tersedia untuk tanggal yang dipilih
        $deviceOptions = Device::whereExists(function ($query) use ($selectedDate) {
            $query->select(DB::raw(1))
                ->from('history')
                ->whereColumn('id_device', 'history.device_id')
                ->whereDate('history.date_time', $selectedDate);
        })
            ->where('user_id', Auth::id()) // tambahkan kondisi where untuk user_id
            ->whereNotNull('name') // pastikan hanya memasukkan perangkat dengan nama yang terdefinisi
            ->pluck('name') // Ambil nama perangkat
            ->unique() // Hapus duplikat
            ->values() // Re-indeks array
            ->toArray();

        return response()->json([
            'data' => array_values($chartData),
            'deviceOptions' => $deviceOptions,
            'deviceCount' => count($deviceOptions)
        ]);
    }


    public function grafikadmin(Request $request)
    {
        $selectedDate = $request->input('selected_date');
        $selectedDevice = $request->input('selected_device');
        $selectedChart = $request->input('selected_chart'); // Tambahkan input selected_chart

        $query = History::query()->whereDate('date_time', $selectedDate);

        // Jika perangkat dipilih, tambahkan kondisi where untuk perangkat
        if ($selectedDevice) {
            $query->whereHas('device', function ($query) use ($selectedDevice) {
                $query->where('name', $selectedDevice);
            });
        }

        // Ambil data history sesuai dengan tanggal yang dipilih
        $historyData = $query->get();

        // Jika tidak ada data, kembalikan respons kosong
        if ($historyData->isEmpty()) {
            return response()->json([
                'data' => [],
                'deviceOptions' => [], // Jangan lupa sertakan daftar perangkat yang tersedia
                'deviceCount' => 0
            ]);
        }

        // Persiapkan data untuk ditampilkan di chart berdasarkan pilihan chart yang dipilih
        $chartData = [];

        // Sesuaikan kueri untuk mengambil data berdasarkan jenis chart yang dipilih
        if ($selectedChart === 'speed') {
            $historyQuery = History::query()
                ->select('date_time', 'speeds as count')
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'accuracy') { // Tambahkan logika untuk opsi "Accuracy"
            $historyQuery = History::query()
                ->select('date_time', 'accuracy as count') // Memilih kolom date_time dan accuracy
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'heading') { // Tambahkan logika untuk opsi "Accuracy"
            $historyQuery = History::query()
                ->select('date_time', 'heading as count') // Memilih kolom date_time dan accuracy
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'altitude_acuracy') { // Tambahkan logika untuk opsi "Accuracy"
            $historyQuery = History::query()
                ->select('date_time', 'altitude_acuracy as count') // Memilih kolom date_time dan accuracy
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'latitude') { // Tambahkan logika untuk opsi "Accuracy"
            $historyQuery = History::query()
                ->select('date_time', 'latitude as count') // Memilih kolom date_time dan accuracy
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();
            $chartData = $historyQuery->toArray();
        } elseif ($selectedChart === 'longitude') { // Tambahkan logika untuk opsi "Accuracy"
            $historyQuery = History::query()
                ->select('date_time', 'longitude as count') // Memilih kolom date_time dan accuracy
                ->whereDate('date_time', $selectedDate)
                ->when($selectedDevice, function ($query) use ($selectedDevice) {
                    $query->whereHas('device', function ($query) use ($selectedDevice) {
                        $query->where('name', $selectedDevice);
                    });
                })
                ->get();

            $chartData = $historyQuery->toArray();
        } else {
            foreach ($historyData as $data) {
                $dateTime = is_string($data->date_time) ? new \DateTime($data->date_time) : $data->date_time;

                $date = $dateTime->format('Y-m-d H:i:s');
                $value = 1;

                if (isset($chartData[$date])) {
                    $chartData[$date]['count'] += $value;
                } else {
                    $chartData[$date] = [
                        'date_time' => $date,
                        'count' => $value
                    ];
                }
            }
        }

        // Ambil daftar perangkat yang tersedia untuk tanggal yang dipilih
        $deviceOptions = Device::whereExists(function ($query) use ($selectedDate) {
            $query->select(DB::raw(1))
                ->from('history')
                ->whereColumn('id_device', 'history.device_id')
                ->whereDate('history.date_time', $selectedDate);
        })
            ->whereNotNull('name') // pastikan hanya memasukkan perangkat dengan nama yang terdefinisi
            ->pluck('name') // Ambil nama perangkat
            ->unique() // Hapus duplikat
            ->values() // Re-indeks array
            ->toArray();

        return response()->json([
            'data' => array_values($chartData),
            'deviceOptions' => $deviceOptions, // Sertakan daftar perangkat yang tersedia
            'deviceCount' => count($deviceOptions)
        ]);
    }
}
