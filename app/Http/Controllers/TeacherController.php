<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function summary()
    {
        return response()->json(['message' => 'Ringkasan absensi']);
    }

    public function attendanceByDate($date)
    {
        return response()->json(['message' => "Absensi tanggal $date"]);
    }

    public function exportExcel()
    {
        return response()->json(['message' => 'Export ke Excel']);
    }

    public function index()
{
    $user = Auth::user();

    if ($user->hasRole('wali_kelas')) {
        // Logic khusus wali kelas
        return response()->json(['message' => 'Ini halaman khusus wali kelas']);
    }

    return response()->json(['message' => 'Akses ditolak'], 403);
}
}
