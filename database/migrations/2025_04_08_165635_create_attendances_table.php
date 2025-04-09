<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel students
            $table->unsignedBigInteger('student_id');

            // Tanggal kehadiran
            $table->date('date');

            // Status kehadiran
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alfa']);

            // Foreign key alasan absen (nullable kalau status hadir)
            $table->unsignedBigInteger('absence_reason_id')->nullable();

            // Timestamps
            $table->timestamps();

            // Constraints
            $table->foreign('student_id')
                ->references('id')->on('students')
                ->onDelete('cascade');

            $table->foreign('absence_reason_id')
                ->references('id')->on('absence_reasons')
                ->onDelete('set null');

            // Satu siswa tidak bisa absen dua kali dalam 1 hari
            $table->unique(['student_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
