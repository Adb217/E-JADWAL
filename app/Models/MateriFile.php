<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MateriFile extends Model
{
    /** Disk penyimpanan. Nanti pindah ke Supabase Storage tinggal ganti ke 's3'. */
    public const DISK = 'local';

    protected $table = 'materi_files';
    protected $guarded = [];

    protected static function booted(): void
    {
        static::deleting(fn (MateriFile $f) => Storage::disk(self::DISK)->delete($f->path));
    }

    public function materi() { return $this->belongsTo(Materi::class); }

    public function getEkstensiAttribute(): string
    {
        return strtolower(pathinfo($this->nama_asli, PATHINFO_EXTENSION));
    }

    public function getIconAttribute(): string
    {
        return match (true) {
            in_array($this->ekstensi, ['jpg', 'jpeg', 'png', 'gif', 'webp']) => 'fa-file-image',
            $this->ekstensi === 'pdf' => 'fa-file-pdf',
            in_array($this->ekstensi, ['xls', 'xlsx', 'csv']) => 'fa-file-excel',
            in_array($this->ekstensi, ['doc', 'docx']) => 'fa-file-word',
            in_array($this->ekstensi, ['ppt', 'pptx']) => 'fa-file-powerpoint',
            in_array($this->ekstensi, ['zip', 'rar', '7z']) => 'fa-file-zipper',
            default => 'fa-file',
        };
    }

    public function getUkuranTeksAttribute(): string
    {
        return $this->ukuran >= 1048576
            ? number_format($this->ukuran / 1048576, 1) . ' MB'
            : max(1, round($this->ukuran / 1024)) . ' KB';
    }
}