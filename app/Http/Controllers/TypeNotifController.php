<?php

namespace App\Http\Controllers;

use App\Models\TypeNotif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypeNotifController extends Controller
{

    public function store(Request $request)
    {
        // Validasi data yang diterima dari formulir
        $request->validate([
            'notification_type' => 'required',
        ]);

        // Simpan data ke dalam tabel notifikasi
        $notification = new TypeNotif();
        $notification->notification_type = $request->input('notification_type');

        // Jika jenis notifikasi adalah "Custom Interval", simpan juga interval yang dipilih
        if ($request->input('notification_type') == 3) {
            $notification->custom_interval_hours = $request->input('custom_interval_hours');
        }

        $userId = Auth::id(); 
        $notification->user_id = $userId;

        $notification->save();

        return redirect()->back()->with('notif', 'Notifikasi ditambahkan'); 
    }
}
