<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informasi_Sosmed extends Model
{
    use HasFactory;

    protected $table = 'informasi_sosmed';

    protected $fillable = [
        'title_sosmed',
        'street_name',
        'subdistrict',
        'ward',
        'call',
        'email',
    ];
}
