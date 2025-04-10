<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
