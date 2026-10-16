<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwals', fn (Blueprint $t) => $t->unsignedBigInteger('ruangan_id')->nullable()->change());
    }

    public function down(): void
    {
        // dibiarkan nullable (baris Sesi nggak punya ruangan)
    }
};