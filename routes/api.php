<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SecretaryController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\DashboardController;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 🔐 Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/register', [AuthController::class, 'register']);


// 🛡 Route yang butuh token Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Info user login & role
    Route::get('/me', function (Request $request) {
        return response()->json([
            'user' => $request->user(),
            'roles' => $request->user()->getRoleNames()
        ]);
    });

    // Logout
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    });

    // 🛡 Sekretaris Routes
    Route::middleware('role:sekretaris')->prefix('sekretaris')->group(function () {

        Route::get('/dashboard', fn () => response()->json(['message' => 'Halo Sekretaris!']));

        // 📋 CRUD Siswa
        Route::get('/siswa', [SecretaryController::class, 'index']);
        Route::post('/siswa', [SecretaryController::class, 'store']);
        Route::put('/siswa/{student}', [SecretaryController::class, 'update']);
        Route::delete('/siswa/{student}', [SecretaryController::class, 'destroy']);

        // 🗓 Absensi
        Route::post('/absensi', [SecretaryController::class, 'markAttendance']);
        Route::post('/absensi/import', [SecretaryController::class, 'importAttendance']);

        // ❌ Alasan Ketidakhadiran
        Route::get('/alasan', [SecretaryController::class, 'allReasons']);
        Route::post('/alasan', [SecretaryController::class, 'addReason']);
        Route::put('/alasan/{reason}', [SecretaryController::class, 'updateReason']);
        Route::delete('/alasan/{reason}', [SecretaryController::class, 'deleteReason']);
    });

    // 👨‍🏫 Wali Kelas Routes
    Route::middleware('role:wali_kelas')->prefix('wali-kelas')->group(function () {

        Route::get('/dashboard', fn () => response()->json(['message' => 'Halo Wali Kelas!']));

        // 📊 Ringkasan Absensi
        Route::get('/absensi/rekap', [TeacherController::class, 'attendanceSummary']);
        Route::get('/absensi/per-hari/{tanggal}', [TeacherController::class, 'attendanceByDate']);
        Route::get('/absensi/export', [TeacherController::class, 'exportToExcel']);
    });

    // 👩‍🎓 Siswa Routes
    Route::middleware('role:siswa')->prefix('siswa')->group(function () {

        Route::get('/dashboard', fn () => response()->json(['message' => 'Halo Siswa!']));

        // 📖 Riwayat Absensi
        Route::get('/absensi/riwayat', [StudentController::class, 'attendanceHistory']);
    });
});

// 🔁 Fallback kalau route tidak ditemukan
Route::fallback(function () {
    return response()->json([
        'message' => 'Endpoint tidak ditemukan atau tidak memiliki izin akses.'
    ], 404);
});

Route::get('/dashboard', fn () => response()->json(['message' => 'Halo Sekretaris!']));

Route::middleware(['auth:sanctum', 'role:sekretaris'])->get('/tes-role', function () {
    return response()->json(['message' => 'Sekretaris bisa akses!']);
});
