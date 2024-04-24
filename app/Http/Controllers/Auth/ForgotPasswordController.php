<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetPhoneToken;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class ForgotPasswordController extends Controller
{
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Panggil Password::sendResetLink() dengan menyertakan parameter email
        $status = Password::sendResetLink(['email' => $request->email]);

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()->route('validation')->with(['status' => __($status)]);
        } else {
            return back()->withErrors(['email' => __($status)]);
        }
    }
    public function sendResetLinkPhone(Request $request)
    {
        // dd($request->all());
        $request->validate(['phone' => 'required|numeric']);

        // $user = $request->user();

        $url = "https://app.japati.id/api/send-message";

        // Mendapatkan token untuk pengguna yang melakukan reset password dengan phone
        $token = Str::random(64); // Gunakan metode yang sesuai untuk menghasilkan token unik
        // set waktu 
        $currentTime = Carbon::now()->toDateTimeString();
        // Simpan token ke dalam database
        PasswordResetPhoneToken::updateOrCreate(
            ['phone' => $request->phone], // Kriteria pencarian
            ['token' => $token, 'created_at' => $currentTime] // Data untuk di-update atau dibuat
        );

        $appUrl = route('password.phone.reset', ['token' => $token, 'phone' => $request->phone]);

        $data = [
            'gateway' => '6285954906329',
            'number' => $request->phone,
            'type' => 'text',
            'message' => "Click this link to reset your password: $appUrl",
        ];

        try {
            $response = Http::withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')->post($url, $data);
            // dd($response->status());
            if ($response->successful()) {
                return redirect()->route('validation.phone')->with(['status' => 'RESET_LINK_SENT']);
            } else {
                return back()->withErrors(['phone' => 'Failed to send reset password link to your phone number.']);
            }
        } catch (RequestException $e) {
            return back()->withErrors(['phone' => 'Failed to send reset password link. Please try again later.']);
        }
    }
}
