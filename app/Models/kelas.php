<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    public const ROMAWI = [10 => 'X', 11 => 'XI', 12 => 'XII'];

    protected $table = 'kelas';
    protected $guarded = [];

    public function jadwals() { return $this->hasMany(Jadwal::class); }

    /** Angkatan 13 = kelas 12 di TA 2026/2027. Tiap tahun ajaran geser 1 tingkat. */
    public static function hitungTingkat(?int $angkatan): ?int
    {
        return $angkatan ? 12 + (Periode::tahunMulai() - 2026) - ($angkatan - 13) : null;
    }

    public function getTingkatAttribute(): ?int { return self::hitungTingkat($this->angkatan); }
}