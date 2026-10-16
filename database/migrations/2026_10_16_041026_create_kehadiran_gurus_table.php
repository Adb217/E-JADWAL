<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kehadiran_gurus', function (Blueprint $t) {
            $t->id();
            $t->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
            $t->date('tanggal');
            $t->string('status', 20);                    // Belum Datang | Sudah Datang | Tidak Hadir
            $t->string('sumber', 20)->default('admin');  // nanti 'simaster'
            $t->timestamps();
            $t->unique(['guru_id', 'tanggal']);
        });

        DB::statement('alter table "kehadiran_gurus" enable row level security');
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadiran_gurus');
    }
};