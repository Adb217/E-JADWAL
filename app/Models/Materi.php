<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materis';
    protected $guarded = [];

    protected static function booted(): void
    {
        // hapus materi -> file fisiknya ikut kehapus
        static::deleting(fn (Materi $m) => $m->files->each->delete());
    }

    public function mapel() { return $this->belongsTo(Mapel::class); }
    public function files() { return $this->hasMany(MateriFile::class); }
}