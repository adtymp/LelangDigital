<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'no_telp',
        'is_delete',
        'status_akun',
        'status_verifikasi',
        'level_id',
        'poin_level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function portofolio()
    {
        return $this->hasOne(Portofolio::class);
    }

    public function rekening()
    {
        return $this->hasOne(Rekening::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function pengambilans()
    {
        return $this->hasMany(Pengambilan::class);
    }

    public function isPending()
    {
        return $this->status_verifikasi === 'permintaan';
    }

    public function isAccepted()
    {
        return $this->status_verifikasi === 'diterima';
    }

    public function isRejected()
    {
        return $this->status_verifikasi === 'ditolak';
    }

    public function isActiveAccount()
    {
        return $this->status_akun === 'aktif';
    }

    public function isInactiveAccount()
    {
        return $this->status_akun === 'nonaktif';
    }
}
