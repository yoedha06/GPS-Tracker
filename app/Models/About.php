<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $table = 'about';

    protected $fillable = [
        'title_about',
        'left_description',
        'right_description',
        'right_description',
        'feature1',
        'feature2',
        'feature3',
    ];
}
