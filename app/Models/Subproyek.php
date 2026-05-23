<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subproyek extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyek_id',
        'nama_sub_proyek',
        'total_halaman',
    ];

    public function proyeks()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }

    public function subsubproyeks()
    {
        return $this->hasMany(Subsubproyek::class);
    }
}
