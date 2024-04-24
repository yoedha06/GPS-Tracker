<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;


class VerificationController extends Controller
{
    use VerifiesEmails;

    protected $redirectTo = '/login'; // Mengarahkan ke halaman login setelah verifikasi

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function showVerificationPage()
    {
        $verified = Auth::user()->hasVerifiedEmail();
        return view('auth.verify', ['verified' => $verified]);
    }

    public function verify(Request $request)
    {
        if (!$request->user()->hasVerifiedEmail()) {
            return redirect()->route('login');
        }

        $request->fulfill();
        $user = $request->user();
        event(new Verified($user));
        Auth::logout();

        return redirect($this->redirectPath());
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('login')->with('error', 'Email Anda sudah diverifikasi.');
        }

        $request->user()->sendEmailVerificationNotification();

        session()->flash('resent', true);

        return back()->with('successs', 'Email verification has been successfully resent.');
    }

    public function resendPhoneVerification(Request $request)
    {
        $user = $request->user(); // Anda dapat menyesuaikan ini sesuai dengan struktur data aplikasi Anda

        // Pastikan user ada dan memiliki nomor telepon
        if ($user && $user->phone) {
            $url = "https://app.japati.id/api/send-message";

            $appUrl = route('login');

            $data = [
                'gateway' => '6285954906329',
                'number' => $user->phone,
                'type' => 'text',
                'message' => "Click this link to verify your phone: $appUrl?token=" . $user->id,
            ];

            try {
                $response = Http::withToken('API-TOKEN-iGIXgP7hUwO08mTokHFNYSiTbn36gI7PRntwoEAUXmLbSWI6p7cXqq')
                    ->post($url, $data);

                if ($response->successful()) {
                    return redirect('/phone/verify')->with('success', 'A verification link has been sent to your phone number.');
                } else {
                    return redirect('/phone/verify')->with('error', 'Failed to resend verification link to your phone number.');
                }
            } catch (RequestException $e) {
                return redirect('/phone/verify')->with('error', 'Failed to resend verification link. Please try again later.');
            }
        }
    }
}
