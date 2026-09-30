<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('username')->nullable()->unique()->after('name');
            $t->string('email')->nullable()->change();
        });

        Schema::create('kelas', function (Blueprint $t) {
            $t->id();
            $t->string('nama')->unique();
            $t->string('jurusan')->nullable();
            $t->timestamps();
        });

        Schema::create('mapels', function (Blueprint $t) {
            $t->id();
            $t->string('nama')->unique();
            $t->string('kode', 20)->nullable();
            $t->timestamps();
        });

        Schema::create('gurus', function (Blueprint $t) {
            $t->id();
            $t->string('nama');
            $t->string('nip', 30)->nullable()->unique();
            $t->string('status', 20)->default('Aktif');
            $t->timestamps();
        });

        Schema::create('guru_mapel', function (Blueprint $t) {
            $t->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
            $t->foreignId('mapel_id')->constrained('mapels')->cascadeOnDelete();
            $t->primary(['guru_id', 'mapel_id']);
        });

        Schema::create('ruangans', function (Blueprint $t) {
            $t->id();
            $t->string('nama')->unique();
            $t->string('tipe', 50)->default('Kelas Reguler');
            $t->unsignedSmallInteger('kapasitas')->nullable();
            $t->timestamps();
        });

        Schema::create('jadwals', function (Blueprint $t) {
            $t->id();
            $t->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $t->foreignId('mapel_id')->constrained('mapels')->cascadeOnDelete();
            $t->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
            $t->foreignId('ruangan_id')->constrained('ruangans')->cascadeOnDelete();
            $t->string('hari', 10);
            $t->time('jam_mulai');
            $t->time('jam_selesai');
            $t->timestamps();
            $t->index(['hari', 'jam_mulai']);
        });

        Schema::create('guru_pikets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
            $t->string('hari', 10);
            $t->string('lokasi')->nullable();
            $t->timestamps();
        });

        Schema::create('perubahans', function (Blueprint $t) {
            $t->id();
            $t->string('tipe', 30);
            $t->string('judul');
            $t->string('dari')->nullable();
            $t->string('ke')->nullable();
            $t->text('alasan');
            $t->string('status', 20)->default('Menunggu');
            $t->timestamps();
        });

        // Supabase membuka schema public lewat REST API (anon key). Aktifkan RLS supaya
        // tabel ga bisa dibaca dari luar. Laravel konek pakai role postgres, jadi tetap bypass RLS.
        foreach (DB::select("select tablename from pg_tables where schemaname = 'public'") as $row) {
            DB::statement('alter table "' . $row->tablename . '" enable row level security');
        }
    }

    public function down(): void
    {
        foreach (['perubahans', 'guru_pikets', 'jadwals', 'ruangans', 'guru_mapel', 'gurus', 'mapels', 'kelas'] as $t) {
            Schema::dropIfExists($t);
        }
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('username'));
    }
};