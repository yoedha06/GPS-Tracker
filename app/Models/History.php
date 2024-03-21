<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $table = "history";

    protected $primaryKey = "id_history";
    protected $fillable = [
        "device_id",
        "latitude",
        "longitude",
        "bounds",
        "accuracy",
        "altitude",
        "altitude_acuracy",
        "heading",
        "speeds",
        "date_time",
        "original",
    ];

    protected $casts = [
        'accuracy' => 'float',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id_device');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
