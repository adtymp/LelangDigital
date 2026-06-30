<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class Portofolio extends Model
{
    use HasUuids;
    protected $fillable = [
        'user_id',
        'type',
        'file_path',
        'link_url',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
