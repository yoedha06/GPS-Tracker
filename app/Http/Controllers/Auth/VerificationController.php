<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


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
        $user = User::where('verification_token', $request->route('token'))->firstOrFail();

        if ($user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));

            // Hapus token dan waktu kadaluarsa karena email sudah diverifikasi
            $user->verification_token = null;
            $user->verification_expiry = null;
            $user->save();

            return redirect($this->redirectPath())->with('success', 'Email already verified.');
        }
    }


    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('login')->with('error', 'Email Anda sudah diverifikasi.');
        }

        $token =  Str::random(40);
        $request->user()->verification_token = $token;
        $request->user()->verification_expiry = Carbon::now()->addMinute(60);
        $request->user()->save();

        $request->user()->sendEmailVerificationNotification();

        session()->flash('resent', true);

        return back()->with('successs', 'Email verification has been successfully resent.');
    }
}
