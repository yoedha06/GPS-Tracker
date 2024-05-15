<?php

namespace App\Http\Controllers;

use App\Models\TypeNotif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypeNotifController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'count' => 'required',
            'time_schedule' => 'required',
        ]);

        session([
            'count' => $request->input('count'),
            'time_schedule' => $request->input('time_schedule'),
        ]);

        $existingNotification = TypeNotif::where('user_id', Auth::id())->first();

        TypeNotif::updateOrCreate(
            ['user_id' => Auth::id()],
            ['count' => $request->count, 'time_schedule' => $request->time_schedule]
        );

        $message = $existingNotification ? 'Update successfully' : 'You will receive the data at the time you specify';

        if ($existingNotification && $request->count == 0) {
            session()->forget(['count', 'time_schedule']);
        }

        return redirect()->back()->with('notif', $message);
    }
}
