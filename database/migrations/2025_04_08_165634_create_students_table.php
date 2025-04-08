<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name', 4); // Nama maksimal 4 karakter
            $table->string('nis')->unique(); // Nomor Induk Siswa unik
            $table->enum('status', ['aktif', 'keluar', 'dikeluarkan'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
