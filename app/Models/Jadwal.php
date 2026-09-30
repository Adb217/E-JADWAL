<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    public const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    protected $table = 'jadwals';
    protected $guarded = [];

    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function mapel() { return $this->belongsTo(Mapel::class); }
    public function guru() { return $this->belongsTo(Guru::class); }
    public function ruangan() { return $this->belongsTo(Ruangan::class); }

    public static function namaHariIni(): ?string
    {
        return self::HARI[now()->dayOfWeekIso - 1] ?? null;
    }

    public function scopeHariIni($q)
    {
        return $q->where('hari', self::namaHariIni() ?? '-');
    }

    public function scopeUrut($q)
    {
        $list = "'" . implode("','", self::HARI) . "'";
        return $q->orderByRaw("array_position(ARRAY[$list], hari::text)")->orderBy('jam_mulai');
    }

    /** Jadwal lain yang overlap jamnya dan memakai ruangan / guru / kelas yang sama. */
    public function scopeBentrok($q, array $d, ?int $ignoreId = null)
    {
        return $q->where('hari', $d['hari'])
            ->where('jam_mulai', '<', $d['jam_selesai'])
            ->where('jam_selesai', '>', $d['jam_mulai'])
            ->where(fn ($w) => $w->where('ruangan_id', $d['ruangan_id'])
                ->orWhere('guru_id', $d['guru_id'])
                ->orWhere('kelas_id', $d['kelas_id']))
            ->when($ignoreId, fn ($x) => $x->where('id', '!=', $ignoreId));
    }

    public function getMulaiAttribute(): string { return substr($this->jam_mulai, 0, 5); }
    public function getSelesaiAttribute(): string { return substr($this->jam_selesai, 0, 5); }

    public function getStatusAttribute(): string
    {
        if ($this->hari !== self::namaHariIni()) {
            return 'Terjadwal';
        }
        $now = now()->format('H:i:s');

        return match (true) {
            $now < $this->jam_mulai => 'Akan Datang',
            $now >= $this->jam_selesai => 'Selesai',
            default => 'Berlangsung',
        };
    }

    public function getBorderAttribute(): string
    {
        return ['Berlangsung' => 'border-l-maroon', 'Akan Datang' => 'border-l-blue-500', 'Selesai' => 'border-l-gray-300'][$this->status] ?? 'border-l-gold';
    }

    public function getBadgeAttribute(): string
    {
        return ['Berlangsung' => 'bg-maroon text-white', 'Akan Datang' => 'bg-blue-100 text-blue-700', 'Selesai' => 'bg-gray-100 text-gray-500'][$this->status] ?? 'bg-cream text-gray-600';
    }

    public function getSisaAttribute(): string
    {
        $m = max((int) ceil(now()->diffInMinutes(today()->setTimeFromTimeString($this->jam_selesai), false)), 0);

        return $m >= 60 ? intdiv($m, 60) . ' jam ' . ($m % 60) . ' menit' : $m . ' menit';
    }
}