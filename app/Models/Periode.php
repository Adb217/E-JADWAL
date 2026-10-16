<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periodes';
    protected $guarded = [];
    protected $casts = ['aktif' => 'boolean'];

    private static ?self $aktif = null;
    private static bool $loaded = false;

    public static function aktif(): ?self
    {
        if (! self::$loaded) {
            self::$aktif = self::where('aktif', true)->first();
            self::$loaded = true;
        }

        return self::$aktif;
    }

    public static function aktifId(): ?int { return self::aktif()?->id; }

    /** "2026/2027" -> 2026 */
    public static function tahunMulai(): int
    {
        return (int) substr(self::aktif()?->tahun_ajaran ?? (now()->year . '/'), 0, 4);
    }

    public static function lupakan(): void
    {
        self::$loaded = false;
        self::$aktif = null;
    }

    public function jadwals() { return $this->hasMany(Jadwal::class)->withoutGlobalScopes(); }
}