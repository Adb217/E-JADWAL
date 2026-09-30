<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perubahan extends Model
{
    public const STATUS = ['Menunggu', 'Disetujui', 'Selesai'];

    public const TIPE = [
        'guru_berhalangan' => ['label' => 'Guru Berhalangan', 'icon' => 'fa-user-slash',  'border' => 'border-l-maroon',    'from' => 'Guru Asal',     'to' => 'Pengganti'],
        'pindah_ruangan'   => ['label' => 'Pindah Ruangan',   'icon' => 'fa-right-left',  'border' => 'border-l-gold',      'from' => 'Ruangan Asal',  'to' => 'Ruangan Baru'],
        'tukar_jadwal'     => ['label' => 'Tukar Jadwal',     'icon' => 'fa-shuffle',     'border' => 'border-l-maroon',    'from' => 'Jadwal Asal',   'to' => 'Jadwal Baru'],
        'lainnya'          => ['label' => 'Info Lainnya',     'icon' => 'fa-circle-info', 'border' => 'border-l-gray-400',  'from' => 'Sebelum',       'to' => 'Sesudah'],
    ];

    protected $table = 'perubahans';
    protected $guarded = [];

    public function getMetaAttribute(): array { return self::TIPE[$this->tipe] ?? self::TIPE['lainnya']; }
    public function getIconAttribute(): string { return $this->meta['icon']; }
    public function getBorderAttribute(): string { return $this->meta['border']; }
    public function getDotAttribute(): string { return str_replace('border-l-', 'bg-', $this->meta['border']); }

    public function getBadgeAttribute(): string
    {
        return ['Menunggu' => 'bg-amber-500', 'Disetujui' => 'bg-green-600', 'Selesai' => 'bg-gray-500'][$this->status] ?? 'bg-gray-500';
    }
}