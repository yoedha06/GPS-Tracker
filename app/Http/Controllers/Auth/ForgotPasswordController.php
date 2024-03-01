<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

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
}
