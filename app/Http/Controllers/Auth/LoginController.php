<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        // Cek apakah ada email yang disimpan di session
        $registered_email = session('registered_email');

        return view('auth.login', compact('registered_email'));
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if the user's email is verified
            if (!$user->hasVerifiedEmail()) {
                // If the email is not verified, send a verification email
                $user->sendEmailVerificationNotification();

                // Optionally, redirect to a custom page to inform the user to check their email
                return redirect('/email/verify')->with('status', 'A verification link has been sent to your email address.');
            }

            // For admin users, redirect to the main dashboard
            if ($user->role == 'admin') {
                return redirect()->route('main.dashboard');
            } else {
                // For non-admin users, redirect to the home page or any other intended route
                return redirect()->intended('/home');
            }
        }

        return redirect('/login')->with('error', 'Invalid credentials');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
