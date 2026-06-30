<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Poin extends Model
{
    use HasUuids;
    protected $fillable = [
        'aspek',
        'slug',
        'bobot',
        'status',
    ];
}
