<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

// ✅ Registrasi
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

// ✅ Login (return Sanctum token)
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

// ✅ Lupa Password
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

// ✅ Reset Password
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

// ✅ Verifikasi Email
Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth:sanctum', 'signed', 'throttle:6,1']) // pakai sanctum
    ->name('verification.verify');

// ✅ Kirim Ulang Verifikasi
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth:sanctum', 'throttle:6,1']) // pakai sanctum
    ->name('verification.send');

// ✅ Logout (hapus token)
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum') // pakai sanctum
    ->name('logout');
