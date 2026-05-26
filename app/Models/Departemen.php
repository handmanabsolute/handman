<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departemen extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'departemens';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_departemen',
        'deskripsi_departemen',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'departemen_id', 'id');
    }
}
