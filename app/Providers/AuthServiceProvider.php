<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            Log::info('Verification email triggered for user: ' . $notifiable->email);
            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('Jika Anda tidak meregistrasi akun ini, abaikan email ini.')
                ->line('Jika Anda memerlukan bantuan, silakan hubungi kami di support@example.com')
                ->action('Verify Email Address', $url);
        });
    }
}
