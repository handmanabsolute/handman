<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUlids;

    protected $table = 'users';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'no_telp',
        'jenis_kelamin',
        'tanggal_lahir',
        'is_active',
        'foto_profil',
        'status_pegawai',
        'alamat',
        'nama_role',
        'deskripsi_user',
        'departemen_id',
        'otp_code',
        'otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tanggal_lahir' => 'date',
        'otp_expires_at' => 'datetime',
    ];

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'departemen_id', 'id');
    }

    public function grupKerjas(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(GrupKerja::class, 'detail_grups', 'user_id', 'grup_kerja_id')
                    ->using(DetailGrup::class)
                    ->withTimestamps();
    }
}
