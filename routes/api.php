<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SecretaryController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Endpoint-endpoint API untuk aplikasi absensi sekolah
| Menggunakan Sanctum sebagai sistem autentikasi token-based
|--------------------------------------------------------------------------
*/

// 🔐 Cek user yang sedang login (berbasis token)
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// =====================================================
// ✅ Route untuk Sekretaris
// Role: secretary
// Prefix: /api/secretary
// =====================================================
Route::middleware(['auth:sanctum', 'role:secretary'])->prefix('secretary')->group(function () {

    // 👩‍🏫 Manajemen Data Siswa
    Route::get('/students', [SecretaryController::class, 'index']);               // Lihat semua siswa
    Route::post('/students', [SecretaryController::class, 'store']);              // Tambah siswa
    Route::put('/students/{student}', [SecretaryController::class, 'update']);    // Update siswa
    Route::delete('/students/{student}', [SecretaryController::class, 'destroy']); // Hapus siswa

    // 📅 Manajemen Absensi
    Route::post('/mark-attendance', [SecretaryController::class, 'markAttendance']); // Tandai kehadiran
    Route::post('/import-attendance', [SecretaryController::class, 'importAttendance']); // Import dari Excel

    // 📄 CRUD Alasan Ketidakhadiran
    Route::get('/absence-reasons', [SecretaryController::class, 'allReasons']);           // Lihat semua alasan
    Route::post('/absence-reasons', [SecretaryController::class, 'addReason']);           // Tambah alasan
    Route::put('/absence-reasons/{reason}', [SecretaryController::class, 'updateReason']); // Update alasan
    Route::delete('/absence-reasons/{reason}', [SecretaryController::class, 'deleteReason']); // Hapus alasan
});

// =====================================================
// ✅ Route untuk Siswa
// Role: student
// Prefix: /api/student
// =====================================================
Route::middleware(['auth:sanctum', 'role:student'])->prefix('student')->group(function () {
    // 👨‍🎓 Lihat histori kehadiran pribadi
    Route::get('/attendance', [StudentController::class, 'myAttendance']);
});

// =====================================================
// ✅ Route untuk Wali Kelas
// Role: teacher
// Prefix: /api/teacher
// =====================================================
Route::middleware(['auth:sanctum', 'role:teacher'])->prefix('teacher')->group(function () {

    // 📊 Ringkasan Kehadiran (Semester, Bulan, Minggu)
    Route::get('/summary', [TeacherController::class, 'summary']); // ?filter=semester&value=1

    // 📅 Detail Kehadiran per Tanggal
    Route::get('/attendance/{date}', [TeacherController::class, 'attendanceByDate']);

    // 📤 Export ke Excel
    Route::get('/export', [TeacherController::class, 'exportExcel']);
});
