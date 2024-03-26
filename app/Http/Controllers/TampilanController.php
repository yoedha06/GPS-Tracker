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

        $history = History::count();

        // Hitung jumlah perangkat yang dimiliki oleh pengguna tersebut
        $deviceCount = $user->devices()->count();

        // Ambil data jumlah history dari setiap device beserta nama perangkat
        $historyData = History::join('device', 'history.device_id', '=', 'device.id_device')
            ->where('device.user_id', $user->id) // Filter data berdasarkan user
            ->select('device.name', DB::raw('count(*) as count'))
            ->groupBy('history.device_id', 'device.name')
            ->get();

        // Ambil daftar perangkat yang dimiliki oleh pengguna
        $devices = $user->devices;

        return view('customer.index', compact('user', 'deviceCount', 'historyData', 'devices', 'history'));
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

        // Ambil data history sesuai dengan tanggal dan perangkat yang dipilih
        $historyData = $query->select('device_id', DB::raw('COUNT(*) as count'))->groupBy('device_id')->get();

        // Ambil nama perangkat berdasarkan ID perangkat
        $deviceNames = Device::whereIn('id_device', $historyData->pluck('device_id')->toArray())
            ->pluck('name', 'id_device')
            ->toArray();

        // Ubah data history untuk mencakup nama perangkat
        $historyDataWithDeviceName = $historyData->map(function ($item) use ($deviceNames) {
            return [
                'device_name' => $deviceNames[$item->device_id],
                'count' => $item->count
            ];
        });

        // Jika perangkat dipilih, filter data sesuai dengan perangkat yang dipilih
        if ($selectedDevice) {
            $historyDataWithDeviceName = $historyDataWithDeviceName->filter(function ($item) use ($selectedDevice) {
                return $item['device_name'] === $selectedDevice;
            });
        }

        // Ambil jumlah device
        $deviceCount = count($deviceNames);

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
            'data' => $historyDataWithDeviceName,
            'deviceOptions' => $deviceOptions,
            'deviceCount' => $deviceCount
        ]);
    }


    public function grafikadmin(Request $request)
    {
        $selectedDate = $request->input('selected_date');
        $selectedDevice = $request->input('selected_device');

        $query = History::query()->whereDate('date_time', $selectedDate);

        // Jika perangkat dipilih, tambahkan kondisi where untuk perangkat
        if ($selectedDevice) {
            $query->whereHas('device', function ($query) use ($selectedDevice) {
                $query->where('name', $selectedDevice);
            });
        }

        // Ambil data history sesuai dengan tanggal dan perangkat yang dipilih
        $historyData = $query->with(['device.user' => function ($query) {
            $query->select('id', 'name');
        }])->select('device_id', DB::raw('COUNT(*) as count'))->groupBy('device_id')->get();

        // Ubah data history untuk mencakup nama perangkat dan pengguna
        $historyDataWithDeviceName = $historyData->map(function ($item) {
            $userName = optional(optional($item->device)->user)->name;
            return [
                'user_name' => $userName,
                'device_name' => optional($item->device)->name,
                'user_id' => optional(optional($item->device)->user)->id, // Tambahkan id pengguna
                'count' => $item->count
            ];
        });

        // Jika perangkat dipilih, filter data sesuai dengan perangkat yang dipilih
        if ($selectedDevice) {
            $historyDataWithDeviceName = $historyDataWithDeviceName->filter(function ($item) use ($selectedDevice) {
                return $item['device_name'] === $selectedDevice;
            });
        }

        // Ambil jumlah device
        $deviceCount = $historyData->unique('device_id')->count();

        // Ambil daftar perangkat yang tersedia untuk tanggal yang dipilih
        $deviceOptions = Device::whereExists(function ($query) use ($selectedDate) {
            $query->select(DB::raw(1))
                ->from('history')
                ->whereColumn('id_device', 'history.device_id')
                ->whereDate('history.date_time', $selectedDate);
        })
            ->pluck('name') // Ambil nama perangkat
            ->unique() // Hapus duplikat
            ->values() // Re-indeks array
            ->toArray();

        return response()->json([
            'data' => $historyDataWithDeviceName,
            'deviceOptions' => $deviceOptions,
            'deviceCount' => $deviceCount
        ]);
    }
}
