<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GrupKerja extends Model
{
    use HasUlids;

    protected $table = 'grup_kerjas';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_grup',
        'deskripsi',
        'departemen_id',
        'created_by',
    ];

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function anggota(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'detail_grups', 'grup_kerja_id', 'user_id')
                    ->using(DetailGrup::class)
                    ->withTimestamps();
    }
}
