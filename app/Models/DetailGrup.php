<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class DetailGrup extends Pivot
{
    use HasUlids;

    protected $table = 'detail_grups';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'grup_kerja_id',
        'user_id',
    ];
}
