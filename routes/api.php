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
| Role: sekretaris, siswa, wali_kelas
|--------------------------------------------------------------------------
*/

// 🔓 Login
Route::post('/login', [AuthController::class, 'login']);

// 🔐 Autentikasi Umum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', fn(Request $request) => response()->json([
        'authenticated' => true,
        'user' => $request->user()
    ]));

    Route::post('/logout', [AuthController::class, 'logout']);
});

// ✅ Sekretaris
Route::middleware(['auth:sanctum', 'role:sekretaris'])->prefix('secretary')->group(function () {
    Route::get('/students', [SecretaryController::class, 'index']);
    Route::post('/students', [SecretaryController::class, 'store']);
    Route::put('/students/{student}', [SecretaryController::class, 'update']);
    Route::delete('/students/{student}', [SecretaryController::class, 'destroy']);

    Route::post('/mark-attendance', [SecretaryController::class, 'markAttendance']);
    Route::post('/import-attendance', [SecretaryController::class, 'importAttendance']);

    Route::get('/absence-reasons', [SecretaryController::class, 'allReasons']);
    Route::post('/absence-reasons', [SecretaryController::class, 'addReason']);
    Route::put('/absence-reasons/{reason}', [SecretaryController::class, 'updateReason']);
    Route::delete('/absence-reasons/{reason}', [SecretaryController::class, 'deleteReason']);
});

// ✅ Siswa
Route::middleware(['auth:sanctum', 'role:siswa'])->prefix('student')->group(function () {
    Route::get('/attendance', [StudentController::class, 'myAttendance']);
});

// ✅ Wali Kelas
Route::middleware(['auth:sanctum', 'role:wali_kelas'])->prefix('teacher')->group(function () {
    Route::get('/summary', [TeacherController::class, 'summary']);
    Route::get('/attendance/{date}', [TeacherController::class, 'attendanceByDate']);
    Route::get('/export', [TeacherController::class, 'exportExcel']);
});
