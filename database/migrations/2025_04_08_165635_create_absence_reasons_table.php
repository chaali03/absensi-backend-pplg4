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
        Schema::create('absence_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('reason')->unique(); // Contoh: Sakit, Izin, Tanpa Keterangan
            $table->text('description')->nullable(); // Penjelasan tambahan jika diperlukan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absence_reasons');
    }
};
