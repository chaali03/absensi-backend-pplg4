<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Login dan generate token Sanctum.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate(); // validasi email + password

        $user = $request->user();

        // Hapus token sebelumnya (opsional, biar 1 sesi aja)
        $user->tokens()->delete();

        // Generate token baru
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Logout dan hapus token saat ini.
     */
    public function destroy(Request $request)
    {
        // Hapus token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }
}
