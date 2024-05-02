<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $table = 'team';

    protected $fillable = [
        'informasi',
        'username_1',
        'username_2',
        'username_3',
        'username_4',
        'posisi_1',
        'posisi_2',
        'posisi_3',
        'posisi_4',
        'deskripsi_1',
        'deskripsi_2',
        'deskripsi_3',
        'deskripsi_4',
        'photo_1',
        'photo_2',
        'photo_3',
        'photo_4',
    ];
}
