<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materis', function (Blueprint $t) {
            $t->id();
            $t->foreignId('mapel_id')->constrained('mapels')->cascadeOnDelete();
            $t->string('judul', 150);
            $t->text('deskripsi')->nullable();
            $t->timestamps();
            $t->index(['mapel_id', 'created_at']);
        });

        Schema::create('materi_files', function (Blueprint $t) {
            $t->id();
            $t->foreignId('materi_id')->constrained('materis')->cascadeOnDelete();
            $t->string('path');
            $t->string('nama_asli');
            $t->string('mime', 150)->nullable();
            $t->unsignedBigInteger('ukuran')->default(0);
            $t->timestamps();
        });

        // sama kayak migration sebelumnya: kunci tabel dari akses REST Supabase
        foreach (['materis', 'materi_files'] as $tbl) {
            DB::statement('alter table "' . $tbl . '" enable row level security');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_files');
        Schema::dropIfExists('materis');
    }
};