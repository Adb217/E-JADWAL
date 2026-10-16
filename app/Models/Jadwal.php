<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{

    public const WARNA_HARI = [
        'Senin'  => ['card' => 'bg-red-50 border-red-100',         'chip' => 'bg-red-600 text-white'],
        'Selasa' => ['card' => 'bg-amber-50 border-amber-100',     'chip' => 'bg-amber-600 text-white'],
        'Rabu'   => ['card' => 'bg-emerald-50 border-emerald-100', 'chip' => 'bg-emerald-600 text-white'],
        'Kamis'  => ['card' => 'bg-sky-50 border-sky-100',         'chip' => 'bg-sky-600 text-white'],
        'Jumat'  => ['card' => 'bg-violet-50 border-violet-100',   'chip' => 'bg-violet-600 text-white'],
        'Sabtu'  => ['card' => 'bg-slate-50 border-slate-200',     'chip' => 'bg-slate-600 text-white'],
    ];

    public static function warnaHari(?string $hari): array
    {
        return self::WARNA_HARI[$hari] ?? ['card' => 'bg-white border-gray-100', 'chip' => 'bg-gray-600 text-white'];
    }

    public function getWarnaHariAttribute(): array { return self::warnaHari($this->hari); }
    
    public const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        protected static function booted(): void
    {
        // semua query Jadwal otomatis cuma baca periode yang aktif
        static::addGlobalScope('periode', fn ($q) => $q->where('jadwals.periode_id', Periode::aktifId()));
        static::creating(fn (Jadwal $j) => $j->periode_id ??= Periode::aktifId());
    }

    /** Pekan 1-2 = tanggal 1-14, pekan 3-4 = tanggal 15 dst. */
    public static function pekanSekarang(): string
    {
        return now()->day <= 14 ? '1-2' : '3-4';
    }

        public function getPekanLabelAttribute(): ?string { return $this->pekan ? 'Pekan ' . $this->pekan : null; }
        
            public function getRuanganNamaAttribute(): string { return $this->ruangan?->nama ?? 'Belum ditentukan'; }
            
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
        return $q->where('hari', self::namaHariIni() ?? '-')
            ->where(fn ($w) => $w->whereNull('pekan')->orWhere('pekan', self::pekanSekarang()));
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
                            ->when(! empty($d['pekan']), fn ($x) => $x->where(fn ($p) => $p->whereNull('pekan')->orWhere('pekan', $d['pekan'])))
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