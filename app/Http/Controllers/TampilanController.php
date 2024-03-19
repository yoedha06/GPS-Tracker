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

        // Hitung jumlah perangkat yang dimiliki oleh pengguna tersebut
        $deviceCount = $user->devices()->count();

        $history = History::count();

        // Ambil data jumlah history dari setiap device beserta nama perangkat
        $historyData = History::join('device', 'history.device_id', '=', 'device.id_device')
            ->select('device.name', DB::raw('count(*) as count'))
            ->groupBy('history.device_id', 'device.name')
            ->get();

        // Ambil daftar perangkat yang dimiliki oleh pengguna
        $devices = $user->devices;

        return view('customer.index', compact('user', 'deviceCount', 'historyData', 'history', 'devices'));
    }



    public function admin()
    {
        // Get total counts
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

        return view('admin.index', compact('user', 'usersCount', 'deviceCount', 'totalHistory', 'historyData', 'history'));
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

        $query = History::query()->whereDate('date_time', $selectedDate);

        // Jika perangkat dipilih, tambahkan kondisi where untuk perangkat
        if ($selectedDevice) {
            $query->whereHas('device', function ($query) use ($selectedDevice) {
                $query->where('name', $selectedDevice);
            });
        }

        // Ambil data history sesuai dengan tanggal dan perangkat yang dipilih
        $historyData = $query->get();

        // Format data untuk dikirim sebagai respons JSON
        $formattedData = $historyData->map(function ($item) {
            return [
                'date_time' => $item->date_time,
                'device_name' => $item->device->name, // Menggunakan 'name' sebagai nama perangkat
                'count' => 1 // Jumlah data sesuai dengan tanggal yang dipilih
            ];
        });

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

        $chartData = $query->pluck('date_time')->toArray();

        return response()->json([
            'data' => $formattedData,
            'deviceOptions' => $deviceOptions,
            'chartData' => $chartData
        ]);
    }
}
