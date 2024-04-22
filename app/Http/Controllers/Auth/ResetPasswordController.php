<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\KirimEmail;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/login';


    public function showResetForm(Request $request, $token = null)
    {
        $email = $request->email;
        $phone = $request->phone;

        return view('auth.passwords.reset', compact('token', 'email', 'phone'));
    }


    // Melakukan reset password
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric', // Ganti 'email' dengan 'numeric'
            'password' => 'required|min:4|confirmed',
        ]);

        // Check if reset is requested by email
        if ($request->email) {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => bcrypt($password)
                    ])->save();
                }
            );

            if ($status == Password::PASSWORD_RESET) {
                return redirect('/login')->with('success', 'Password has been changed, please login again');
            } else {
                return back()->withInput()->withErrors(['email' => __($status)]);
            }
        }
        // Check if reset is requested by phone
        elseif ($request->phone) {
            // Validate token and phone number
            $tokenData = DB::table('password_reset_phone_tokens')
                ->where('phone', $request->phone)
                ->where('token', $request->token)
                ->first();

            if (!$tokenData) {
                return back()->withErrors(['token' => 'Invalid token for this phone number.']);
            }

            // Find user by phone number
            $user = DB::table('users')->where('phone', $request->phone)->first();

            if (!$user) {
                return back()->withErrors(['phone' => 'User not found.']);
            }

            // Update password
            DB::table('users')
                ->where('phone', $request->phone)
                ->update(['password' => Hash::make($request->password)]);

            // Delete the token
            DB::table('password_reset_phone_tokens')
                ->where('phone', $request->phone)
                ->delete();

            return redirect('/login')->with('success', 'Password has been changed, please login again');
        }
    }
}
