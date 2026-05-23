<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Poin extends Model
{
    protected $fillable = [
        'aspek',
        'slug',
        'bobot',
        'status',
    ];
}
