<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
        public const WARNA_JURUSAN = [
        'TKJ'  => ['border' => 'border-l-teal-500',    'chip' => 'bg-teal-100 text-teal-800'],
        'RPL'  => ['border' => 'border-l-indigo-500',  'chip' => 'bg-indigo-100 text-indigo-800'],
        'DKV'  => ['border' => 'border-l-fuchsia-500', 'chip' => 'bg-fuchsia-100 text-fuchsia-800'],
        'PSPT' => ['border' => 'border-l-orange-500',  'chip' => 'bg-orange-100 text-orange-800'],
        'KKR'  => ['border' => 'border-l-pink-500',    'chip' => 'bg-pink-100 text-pink-800'],
    ];

    /** Warna dicari dari nama kelas (mis. "XII RPL" -> RPL), jadi kelas X/XI nanti otomatis ikut. */
    public function getWarnaAttribute(): array
    {
        foreach (self::WARNA_JURUSAN as $kode => $w) {
            if (preg_match('/\b' . $kode . '\b/i', $this->nama)) {
                return $w;
            }
        }

        return ['border' => 'border-l-gray-400', 'chip' => 'bg-gray-100 text-gray-700'];
    }
    
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