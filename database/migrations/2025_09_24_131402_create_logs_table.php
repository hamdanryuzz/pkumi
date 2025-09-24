<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('user')->nullable(); // Admin/Dosen/Mahasiswa
            $table->string('action');           // CREATE / UPDATE / DELETE
            $table->string('module');           // Nama modul: Mahasiswa, Nilai, dsb
            $table->text('old_data')->nullable(); // Data lama
            $table->text('new_data')->nullable(); // Data baru
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
