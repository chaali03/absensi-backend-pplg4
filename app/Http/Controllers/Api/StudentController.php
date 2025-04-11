<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function myAttendance()
    {
        return response()->json(['message' => 'ini data absensi siswa']);
    }

    public function index()
{
    $user = Auth::user();

    if ($user->hasRole('siswa')) {
        // Logic khusus siswa
        return response()->json(['message' => 'Ini halaman khusus siswa']);
    }

    return response()->json(['message' => 'Akses ditolak'], 403);
}
}
 