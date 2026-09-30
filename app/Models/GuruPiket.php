<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruPiket extends Model
{
    protected $table = 'guru_pikets';
    protected $guarded = [];

    public function guru() { return $this->belongsTo(Guru::class); }
}