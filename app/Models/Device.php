<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $table = "device";

    protected $primaryKey = "id_device";
    protected $fillable = ['name', 'serial_number', 'photo', 'plat_nomor'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function history()
    {
        return $this->hasOne(History::class, 'device_id', 'id_device');
    }

}
