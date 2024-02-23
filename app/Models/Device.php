<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $table = "Device";
    protected $fillable = ["user_id", "name", "serial_number"];

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function history()
    {
        return $this->hasOne(History::class, 'device_id', 'id_device');
    }
    
}
