<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeNotif extends Model
{
    use HasFactory;
    protected $table = "type_notification";

    protected $fillable = [
         'notification_type', 'custom_interval_hours'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
