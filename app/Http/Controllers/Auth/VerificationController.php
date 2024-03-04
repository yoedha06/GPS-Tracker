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
}
