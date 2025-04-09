<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SecretaryController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;

// ✅ Route default untuk cek user login
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// ✅ Group route untuk Secretary
Route::middleware(['auth:sanctum', 'role:secretary'])->prefix('secretary')->group(function () {
    Route::get('/students', [SecretaryController::class, 'index']); // Lihat semua siswa
    Route::post('/students', [SecretaryController::class, 'store']); // Tambah siswa
    Route::put('/students/{id}', [SecretaryController::class, 'update']); // Edit siswa
    Route::delete('/students/{id}', [SecretaryController::class, 'destroy']); // Hapus siswa
    Route::post('/mark-attendance', [SecretaryController::class, 'markAttendance']); // Tandai kehadiran
});

// ✅ Group route untuk Student
Route::middleware(['auth:sanctum', 'role:student'])->prefix('student')->group(function () {
    Route::get('/attendance', [StudentController::class, 'myAttendance']); // Lihat histori kehadiran pribadi
});

// ✅ Group route untuk Teacher
Route::middleware(['auth:sanctum', 'role:teacher'])->prefix('teacher')->group(function () {
    Route::get('/summary', [TeacherController::class, 'summary']); // Lihat ringkasan kehadiran
    Route::get('/attendance/{date}', [TeacherController::class, 'attendanceByDate']); // Lihat siapa hadir/absen
    Route::get('/export', [TeacherController::class, 'exportExcel']); // Export ke Excel
});
