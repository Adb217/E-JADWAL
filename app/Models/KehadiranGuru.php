<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KehadiranGuru extends Model
{
    public const STATUS = ['Belum Datang', 'Sudah Datang', 'Tidak Hadir'];

    public const BADGE = [
        'Belum Datang' => ['label' => 'Belum datang / terlambat', 'class' => 'bg-amber-100 text-amber-700'],
        'Sudah Datang' => ['label' => 'Sudah datang',             'class' => 'bg-emerald-100 text-emerald-700'],
        'Tidak Hadir'  => ['label' => 'Tidak hadir',              'class' => 'bg-red-100 text-red-700'],
    ];

    protected $table = 'kehadiran_gurus';
    protected $guarded = [];

    public function guru() { return $this->belongsTo(Guru::class); }
}