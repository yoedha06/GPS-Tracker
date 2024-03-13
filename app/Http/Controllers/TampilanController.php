<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;

use Illuminate\Http\Request;

class TampilanController extends Controller
{
    public function index()
    {
        // Dapatkan pengguna yang saat ini masuk
        $user = auth()->user();

        // Hitung jumlah perangkat yang dimiliki oleh pengguna tersebut
        $deviceCount = $user->devices()->count();

        return view('customer.index', compact('user', 'deviceCount'));
    }

    public function admin()
    {
        //tampilan Admin
        $usersCount = User::count();
        $deviceCount = Device::count();
        $user = auth()->user();
        return view('admin.index', compact('user', 'usersCount', 'deviceCount'));
    }

    public function homepage()
    {
        //tampilan homepage
        return view('layouts.homepage');
    }
}
