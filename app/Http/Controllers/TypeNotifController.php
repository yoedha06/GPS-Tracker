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
            'count' => 'required',
            'time_schedule' => 'required',
        ]);

        $existingNotification = TypeNotif::where('user_id', Auth::id())->first();

        TypeNotif::updateOrCreate(
            ['user_id' => Auth::id()],
            ['count' => $request->count, 'time_schedule' => $request->time_schedule]
        );

        $message = $existingNotification ? 'Update successfully' : 'You will receive the data at the time you specify';

        return redirect()->back()->with('notif', $message);
    }
}
