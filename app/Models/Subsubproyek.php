<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subsubproyek extends Model
{
    use HasFactory;

    protected $fillable = [
        'subproyek_id',
        'nama_halaman',
        'file_pdf',
        'file_xls',
        'total_halaman',
        'harga_perlembar',
        'kualitas',
    ];

    public function subproyeks()
    {
        return $this->belongsTo(Subproyek::class, 'subproyek_id');
    }

    public function pengambilans(){
        return $this->hasMany(Pengambilan::class);
    }
}
