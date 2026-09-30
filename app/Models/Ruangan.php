<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    public const TIPE = ['Kelas Reguler', 'Laboratorium Komputer', 'Lainnya'];

    protected $table = 'ruangans';
    protected $guarded = [];

    public function jadwals() { return $this->hasMany(Jadwal::class); }
}