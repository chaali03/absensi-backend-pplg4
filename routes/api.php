<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SecretaryController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;

/*
|--------------------------------------------------------------------------
| API Routes - Aplikasi Absensi Sekolah
|--------------------------------------------------------------------------
| Autentikasi token-based menggunakan Sanctum
| Role yang didukung: sekretaris, siswa, wali kelas
|--------------------------------------------------------------------------
*/

// =====================================================
// 🔓 Route Tanpa Autentikasi (Public)
// =====================================================

// 🔑 Login
Route::post('/login', [AuthController::class, 'login']);


// =====================================================
// 🔐 Route Dengan Autentikasi Sanctum (Token Required)
// =====================================================

Route::middleware('auth:sanctum')->group(function () {

    // 🔍 Cek info user yang sedang login
    Route::get('/me', function (Request $request) {
        return response()->json([
            'authenticated' => true,
            'user' => $request->user()
        ]);
    });

    // 🔐 Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

// =====================================================
// ✅ Route untuk Sekretaris
// URL: /api/secretary/*
// Role: secretary
// =====================================================
Route::middleware(['auth:sanctum', 'role:secretary'])->prefix('secretary')->group(function () {

    // 👩‍🏫 Manajemen Siswa
    Route::get('/students', [SecretaryController::class, 'index']);
    Route::post('/students', [SecretaryController::class, 'store']);
    Route::put('/students/{student}', [SecretaryController::class, 'update']);
    Route::delete('/students/{student}', [SecretaryController::class, 'destroy']);

    // 🗓️ Manajemen Absensi
    Route::post('/mark-attendance', [SecretaryController::class, 'markAttendance']);
    Route::post('/import-attendance', [SecretaryController::class, 'importAttendance']);

    // ❌ CRUD Alasan Ketidakhadiran
    Route::get('/absence-reasons', [SecretaryController::class, 'allReasons']);
    Route::post('/absence-reasons', [SecretaryController::class, 'addReason']);
    Route::put('/absence-reasons/{reason}', [SecretaryController::class, 'updateReason']);
    Route::delete('/absence-reasons/{reason}', [SecretaryController::class, 'deleteReason']);
});

// =====================================================
// ✅ Route untuk Siswa
// URL: /api/student/*
// Role: student
// =====================================================
Route::middleware(['auth:sanctum', 'role:student'])->prefix('student')->group(function () {
    Route::get('/attendance', [StudentController::class, 'myAttendance']);
});

// =====================================================
// ✅ Route untuk Wali Kelas
// URL: /api/teacher/*
// Role: teacher
// =====================================================
Route::middleware(['auth:sanctum', 'role:teacher'])->prefix('teacher')->group(function () {
    Route::get('/summary', [TeacherController::class, 'summary']);
    Route::get('/attendance/{date}', [TeacherController::class, 'attendanceByDate']);
    Route::get('/export', [TeacherController::class, 'exportExcel']);
});
