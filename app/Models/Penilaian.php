<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $fillable = [
        'pengambilan_id',
        'skor',
        'catatan',
        'total_skor',
        'total_poin',
    ];

    protected $casts = [
        'skor' => 'array',
        'catatan' => 'array'
    ];

    public function pembayarans()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function pengambilan()
    {
        return $this->belongsTo(Pengambilan::class, 'pengambilan_id');
    }
}
