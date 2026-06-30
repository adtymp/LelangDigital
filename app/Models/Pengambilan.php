<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class Pengambilan extends Model
{
    use HasUuids;
    protected $fillable = [
        'user_id',
        'subsubproyek_id',
        'dari_halaman',
        'sampai_halaman',
        'total_halaman',
        'harga_perlembar',
        'total_harga',
        'pdf_split',
        'xls_awal',
        'xls_hasil',
        'status',
    ];

    public function subsubproyeks()
    {
        return $this->belongsTo(Subsubproyek::class, 'subsubproyek_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penilaians() {
        return $this->hasOne(Penilaian::class);
    }

    public function logpoins(){
        return $this->hasMany(LogPoin::class, 'pengambilan_id');
    }
}
