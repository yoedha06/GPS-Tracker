<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Device;
use App\Models\History;
use App\Models\Informasi_Contact;
use App\Models\Informasi_Sosmed;
use App\Models\Pengaturan;
use App\Models\User;
use App\Models\Team;

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

        $lastLocationsCount = History::groupBy('device_id')
            ->selectRaw('count(*)')
            ->get()
            ->count();

        // Jumlahkan total history perangkat pengguna
        $totalHistoryPerDevice = $historyData->sum('count');

        // Ambil daftar perangkat yang dimiliki oleh pengguna
        $devices = $user->devices;

        return view('customer.index', compact('user', 'deviceCount', 'historyData', 'devices', 'historyTotal', 'totalHistoryPerDevice', 'historyDevices', 'lastLocationsCount'));
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
        $lastLocationsCount = History::groupBy('device_id')
            ->selectRaw('count(*)')
            ->get()
            ->count();

        // Get authenticated user
        $user = auth()->user();

        return view('admin.index', compact('user', 'usersCount', 'deviceCount', 'totalHistory', 'historyData', 'history', 'devices', 'lastLocationsCount'));
    }

    public function homepage()
    {
        // Pengaturan
        $pengaturan = Pengaturan::first(); // Ambil data pengaturan sekali saja
        $title_pengaturan = $pengaturan ? $pengaturan->title_pengaturan : null;
        $name_pengaturan =  $pengaturan ? $pengaturan->name_pengaturan : null;
        $background =  $pengaturan ? $pengaturan->background : null;
        $logo =  $pengaturan ? $pengaturan->logo : null;

        // About
        $about = About::first(); // Ambil data tentang sekali saja
        $title_about = $about ? $about->title_about : null;
        $left_description = $about ? $about->left_description : null;
        $right_description = $about ? $about->right_description : null;
        $feature_1 = $about ? $about->feature_1 : null;
        $feature_2 = $about ? $about->feature_2 : null;
        $feature_3 = $about ? $about->feature_3 : null;

        //team
        $team = Team::first();
        $informasi = $team ? $team->informasi : null;
        $username_1 = $team ? $team->username_1 : null;
        $posisi_1 = $team ? $team->posisi_1 : null;
        $deskripsi_1 = $team ? $team->deskripsi_1 : null;
        $photo_1 = $team ? $team->photo_1 : null;

        $username_2 = $team ? $team->username_2 : null;
        $posisi_2 = $team ? $team->posisi_2 : null;
        $deskripsi_2 = $team ? $team->deskripsi_2 : null;
        $photo_2 = $team ? $team->photo_2 : null;

        $username_3 = $team ? $team->username_3 : null;
        $posisi_3 = $team ? $team->posisi_3 : null;
        $deskripsi_3 = $team ? $team->deskripsi_3 : null;
        $photo_3 = $team ? $team->photo_3 : null;

        $username_4 = $team ? $team->username_4 : null;
        $posisi_4 = $team ? $team->posisi_4 : null;
        $deskripsi_4 = $team ? $team->deskripsi_4 : null;
        $photo_4 = $team ? $team->photo_4 : null;

        //informasi contact
        $informasi_contact = Informasi_Contact::first();
        $name_location = $informasi_contact ? $informasi_contact->name_location : null;
        $email_informasi = $informasi_contact ? $informasi_contact->email_informasi : null;
        $call_informasi = $informasi_contact ? $informasi_contact->call_informasi : null;

        //informasi sosmed
        $informasi_sosmed = Informasi_Sosmed::first();
        $title_sosmed = $informasi_sosmed ? $informasi_sosmed->title_sosmed : null;
        $street_name = $informasi_sosmed ? $informasi_sosmed->street_name : null;
        $subdistrict = $informasi_sosmed ? $informasi_sosmed->subdistrict : null;
        $ward = $informasi_sosmed ? $informasi_sosmed->ward : null;
        $call = $informasi_sosmed ? $informasi_sosmed->call : null;
        $email = $informasi_sosmed ? $informasi_sosmed->email : null;


        return view('layouts.homepage', compact('title_pengaturan', 'name_pengaturan', 'background', 'logo', 'title_about', 'left_description', 'pengaturan', 'about', 'right_description', 'feature_1', 'feature_2', 'feature_3', 'informasi', 'username_1', 'posisi_1', 'deskripsi_1', 'photo_1', 'username_2', 'posisi_2', 'deskripsi_2', 'photo_2', 'username_3', 'posisi_3', 'deskripsi_3', 'photo_3','username_4', 'posisi_4', 'deskripsi_4', 'photo_4','name_location', 'email_informasi', 'call_informasi','title_sosmed', 'street_name', 'subdistrict', 'ward', 'call', 'email'));
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
            ->with('user') // Ambil relasi pengguna
            ->get(); // Ambil daftar perangkat

        $deviceOptionsFormatted = $deviceOptions->map(function ($device) {
            return [
                'device_id' => $device->name,
                'user_name' => $device->user->name, // Akses username melalui relasi user
                'device_name' => $device->name
            ];
        });

        return response()->json([
            'data' => array_values($chartData),
            'deviceOptions' => $deviceOptionsFormatted, // Menggunakan $deviceOptionsFormatted
            'deviceCount' => count($deviceOptionsFormatted)
        ]);
    }
}
