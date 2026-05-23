<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogPoin extends Model
{
    protected $fillable = [
        'pengambilan_id',
        'jenis',
        'jumlah_poin',
    ];

    public function pengambilan()
    {
        return $this->belongsTo(Pengambilan::class, 'pengambilan_id');
    }
}
