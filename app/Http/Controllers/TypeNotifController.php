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
            'phone_number' => 'required|string',
            'count' => 'required|integer|min:1',
            'time_schedule' => 'required|date_format:H:i',
        ]);

        $phoneNumbers = explode(';', $request->input('phone_number'));
        $phoneNumbers = array_map('trim', $phoneNumbers);
        $phoneNumbers = array_filter($phoneNumbers);

        foreach ($phoneNumbers as $number) {
            if (!is_numeric($number)) {
                return redirect()->back()->withErrors(['phone_number' => 'All phone numbers must be numeric.']);
            }
        }

        $formattedPhoneNumbers = implode(';', $phoneNumbers);

        session([
            'phone_number' => $request->input('phone_number'),
            'count' => $request->input('count'),
            'time_schedule' => $request->input('time_schedule'),
        ]);

        $existingNotification = TypeNotif::where('user_id', Auth::id())->first();

        TypeNotif::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'phone_number' => $formattedPhoneNumbers,
                'count' => $request->count,
                'time_schedule' => $request->time_schedule,
                'remaining_count' => $request->count,
            ]
        );

        $message = $existingNotification ? 'Update successfully' : 'You will receive the data at the time you specify';

        return redirect()->back()->with('notif', $message);
    }
}
