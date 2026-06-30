<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasUuids;
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'read_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
