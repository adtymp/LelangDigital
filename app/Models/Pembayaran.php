<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasUuids;
    protected $fillable = [
        'penilaian_id',
        'status',
        'total_pembayaran',
        'bukti_transfer'
    ];

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class, 'penilaian_id');
    }

}
