<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
        */
        public function boot()
    {
        // Menghapus panggilan registerPolicies()

        ResetPassword::toMailUsing(function ($user, $token) {
            $email = $user->email; // Mengambil alamat email dari objek user

            Log::info('Password reset email triggered for user: ' . $email);

            return (new MailMessage)
                ->subject(Lang::get('Reset Password Notification!'))
                ->greeting('Selamat datang ' . $user->username)
                ->line(Lang::get('Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi untuk akun Anda.'))
                ->action(
                    Lang::get('Reset Password'),
                    url(config('app.url').route('password.reset', ['token' => $token, 'email' => $email], false))
                )
                ->line(Lang::get('Tautan pengaturan ulang kata sandi ini akan kedaluwarsa dalam :count hitungan menit.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]));
        });

    }
}

