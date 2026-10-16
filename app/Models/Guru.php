<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guru extends Model
{
        public function kehadiran() { return $this->hasMany(KehadiranGuru::class); }

    public function kehadiranHariIni() { return $this->hasOne(KehadiranGuru::class)->whereDate('tanggal', today()); }

    public function getStatusHariIniAttribute(): string
    {
        return $this->kehadiranHariIni?->status ?? 'Belum Datang';
    }
    protected $table = 'gurus';
    protected $guarded = [];

    public function mapel() { return $this->belongsToMany(Mapel::class, 'guru_mapel'); }
    public function jadwals() { return $this->hasMany(Jadwal::class); }

    public function getInisialAttribute(): string
    {
        return Str::of($this->nama)->explode(' ')->take(2)
            ->map(fn ($w) => Str::upper(Str::substr($w, 0, 1)))->implode('');
    }
}