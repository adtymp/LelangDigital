<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasUuids;
    protected $fillable = [
        'pengirim_id',
        'penerima_id',
        'teks',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];
 
    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }
 
    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }
 
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }
}
