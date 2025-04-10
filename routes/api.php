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
*/

// ✅ Cek user login (berbasis token Sanctum)
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


// ✅ Route untuk Sekretaris
Route::middleware(['auth:sanctum', 'role:secretary'])->prefix('secretary')->group(function () {
    // 👩‍🏫 CRUD Data Siswa
    Route::get('/students', [SecretaryController::class, 'index']);       // Lihat semua siswa
    Route::post('/students', [SecretaryController::class, 'store']);      // Tambah siswa
    Route::put('/students/{student}', [SecretaryController::class, 'update']); // Edit siswa
    Route::delete('/students/{student}', [SecretaryController::class, 'destroy']); // Hapus siswa

    // 📅 Absensi
    Route::post('/mark-attendance', [SecretaryController::class, 'markAttendance']); // Tandai kehadiran
    Route::post('/import-attendance', [SecretaryController::class, 'importAttendance']); // Import absensi via Excel

    // 📄 Alasan ketidakhadiran (CRUD)
    Route::get('/absence-reasons', [SecretaryController::class, 'allReasons']);         // Lihat semua alasan
    Route::post('/absence-reasons', [SecretaryController::class, 'addReason']);         // Tambah alasan
    Route::put('/absence-reasons/{reason}', [SecretaryController::class, 'updateReason']); // Edit alasan
    Route::delete('/absence-reasons/{reason}', [SecretaryController::class, 'deleteReason']); // Hapus alasan
});


// ✅ Route untuk Siswa
Route::middleware(['auth:sanctum', 'role:student'])->prefix('student')->group(function () {
    // 👨‍🎓 Lihat histori kehadiran pribadi
    Route::get('/attendance', [StudentController::class, 'myAttendance']);
});


// ✅ Route untuk Wali Kelas
Route::middleware(['auth:sanctum', 'role:teacher'])->prefix('teacher')->group(function () {
    // 📊 Ringkasan dan detail kehadiran
    Route::get('/summary', [TeacherController::class, 'summary']);              // Ringkasan per semester/bulan/minggu
    Route::get('/attendance/{date}', [TeacherController::class, 'attendanceByDate']); // Detail per tanggal
    Route::get('/export', [TeacherController::class, 'exportExcel']);          // Export ke Excel
});
