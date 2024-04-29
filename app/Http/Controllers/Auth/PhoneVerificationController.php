<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetPhoneToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhoneVerificationController extends Controller
{
    public function verify($token)
    {
        // Cari token dalam database
        $tokenEntry = PasswordResetPhoneToken::where('token', $token)->first();

        // Jika token ditemukan
        if ($tokenEntry) {
            // Temukan pengguna berdasarkan nomor telepon yang terkait dengan token
            $user = User::where('phone', $tokenEntry->phone)->first();

            // Jika pengguna ditemukan
            if ($user) {
                // Setel waktu verifikasi telepon
                $user->phone_verified_at = now();
                $user->save();

                // Hapus token dari database
                $tokenEntry->delete();

                if (Auth::check()) {
                    Auth::logout();
                    // Redirect pengguna ke halaman login atau ke halaman yang sesuai
                    return redirect()->route('login')->with('success', 'Phone verification successful. You can now login.');
                }
            }
        }

        // Jika token tidak valid atau tidak ditemukan, arahkan pengguna ke halaman login dengan pesan kesalahan
        return redirect()->route('login')->with('error', 'Invalid verification link.');
    }

    // public function loginWithToken(Request $request)
    // {
    //     // Jika request memiliki token
    //     if ($request->has('token')) {
    //         // Cari pengguna berdasarkan token
    //         $user = User::find($request->input('token'));

    //         // Jika pengguna ditemukan, otentikasi pengguna dan arahkan ke halaman yang dimaksud
    //         if ($user) {
    //             Auth::login($user);
    //             return redirect()->route('login');
    //         }
    //     }

    //     // Jika token tidak valid atau tidak ada, arahkan ke halaman yang sesuai
    //     return redirect()->route('login')->with('error', 'Invalid verification token.');
    // }
}
