<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'mapels';
    protected $guarded = [];

    protected static function booted(): void
    {
        // hapus mapel -> materi + file fisiknya ikut kehapus (cascade DB doang nggak ngehapus file)
        static::deleting(fn (Mapel $m) => $m->materis->each->delete());
    }

    public function jadwals() { return $this->hasMany(Jadwal::class); }
    public function materis() { return $this->hasMany(Materi::class); }
}