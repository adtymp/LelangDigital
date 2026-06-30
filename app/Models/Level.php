<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    use HasUuids;
    protected $fillable = [
        'nama_level',
        'slug',
        'min_poin',
        'delay_notifikasi',
    ];

    public function users(){
        return $this->hasMany(Level::class);
    }
}
