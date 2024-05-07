<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informasi_Contact extends Model
{
    use HasFactory;

    protected $table = 'informasi_contact';

    protected $fillable = [
        'name_location',
        'email',
        'call',
    ];
}
