<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    use HasUlids;

    protected $table = 'lampirans';

    protected $fillable = [
        'nama_file',
        'gambar_file',
        'link_tugas',
        'keterangan_tugas',
    ];
}
