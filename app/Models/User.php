<?php

namespace App\Models;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Contracts\Auth\MustVerifyEmail;
=======
// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Auth\Passwords\CanResetPassword;
>>>>>>> 4aed63c0ceb57ae1981213c1fe1ce6cb617ef11f
=======
use Illuminate\Contracts\Auth\MustVerifyEmail;
>>>>>>> 9d113701464c49c7f4a69e245663a370e96f395a
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
<<<<<<< HEAD
    use HasApiTokens, HasFactory, Notifiable;
<<<<<<< HEAD
=======
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;

>>>>>>> 4aed63c0ceb57ae1981213c1fe1ce6cb617ef11f
=======
>>>>>>> 9d113701464c49c7f4a69e245663a370e96f395a
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function devices()
    {
        return $this->hasMany(Device::class, 'user_id');
    }
}
