<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporan')->cascadeOnDelete();
            $table->enum('status', ['menunggu', 'tervalidasi', 'ditolak', 'diproses', 'selesai']);
            $table->text('catatan')->nullable();
            $table->foreignId('diperbarui_oleh')->constrained('pengguna')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_laporan');
    }
};
