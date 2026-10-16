<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periodes', function (Blueprint $t) {
            $t->id();
            $t->string('nama');                 // mis. "Ganjil 2026/2027 v4"
            $t->string('tahun_ajaran', 9);      // "2026/2027"
            $t->boolean('aktif')->default(false);
            $t->timestamps();
        });

        Schema::table('kelas', fn (Blueprint $t) => $t->unsignedSmallInteger('angkatan')->nullable());
        Schema::table('gurus', fn (Blueprint $t) => $t->string('kode', 10)->nullable()->unique());
        Schema::table('jadwals', function (Blueprint $t) {
            $t->foreignId('periode_id')->nullable()->constrained('periodes')->cascadeOnDelete();
            $t->string('pekan', 5)->nullable(); // null = tiap pekan, '1-2', '3-4'
        });

        // jadwal yang sudah ada (dummy) masuk periode pertama yang langsung aktif
        $id = DB::table('periodes')->insertGetId([
            'nama' => 'Jadwal awal', 'tahun_ajaran' => '2026/2027', 'aktif' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('jadwals')->update(['periode_id' => $id]);

        DB::statement('alter table "periodes" enable row level security');
    }

    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $t) {
            $t->dropConstrainedForeignId('periode_id');
            $t->dropColumn('pekan');
        });
        Schema::table('gurus', fn (Blueprint $t) => $t->dropColumn('kode'));
        Schema::table('kelas', fn (Blueprint $t) => $t->dropColumn('angkatan'));
        Schema::dropIfExists('periodes');
    }
};