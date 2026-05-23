<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    protected $fillable = [
        'user_id',
        'nama_bank',
        'no_akun',
        'nama_akun',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
