<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class ResetLevel extends Model
{
    use HasUuids;
    protected $fillable = [
        'status',
        'lama_hari',
        'jam_reset',
        'last_reset_at',
    ];
}
