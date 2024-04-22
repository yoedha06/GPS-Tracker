<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetPhoneToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'token',
        'created_at',
    ];
    public $timestamps = false; // Tambahkan ini untuk menonaktifkan timestamps

}
