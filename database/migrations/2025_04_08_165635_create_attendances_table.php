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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel students
            $table->unsignedBigInteger('student_id');

            // Tanggal kehadiran
            $table->date('date');

            // Status: hadir, sakit, izin, alfa
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alfa']);

            // Foreign key ke absence_reasons (optional, hanya diisi kalau sakit/izin/alfa)
            $table->unsignedBigInteger('absence_reason_id')->nullable();

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('absence_reason_id')->references('id')->on('absence_reasons')->onDelete('set null');

            // Unik: satu siswa tidak bisa dua kali absen di hari yang sama
            $table->unique(['student_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
