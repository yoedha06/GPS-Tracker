<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class location2 extends Model
{
    protected $table = "location2";

    protected $primaryKey = "id";

    protected $fillable = [
        'lat',
        'long',
        'original',
    ];
}
