<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class geofences extends Model
{
    use HasFactory;

    protected $table = 'geofences';

    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'coordinates',
        'radius'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
