<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel 'absence_reasons'.
     */
    public function up(): void
    {
        Schema::create('absence_reasons', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('reason')->unique(); // Nama alasan unik, contoh: Sakit, Izin
            $table->text('description')->nullable(); // Penjelasan tambahan, opsional
            $table->timestamps(); // Kolom created_at & updated_at
        });
    }

    /**
     * Undo migrasi, hapus tabel 'absence_reasons'.
     */
    public function down(): void
    {
        Schema::dropIfExists('absence_reasons');
    }
};
