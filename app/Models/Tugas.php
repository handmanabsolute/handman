<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tugas extends Model
{
    use HasUlids;

    protected $table = 'tugas';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_tugas',
        'deskripsi',
        'tanggal_tugas',
        'deadline_tugas',
        'prioritas',
        'status_tugas',
        'kategoritugas',
        'departemen_id',
        'catatan_revisi'
    ];

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }

    public function lampirans(): HasMany
    {
        return $this->hasMany(Lampiran::class, 'tugas_id');
    }
}
