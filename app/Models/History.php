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
        'device_id',
        'latlng',
        'bounds',
        'accuracy',
        'altitude',
        'altitude_acuracy',
        'heading',
        'speeds',
        'date_time',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id_device');
    }
}
