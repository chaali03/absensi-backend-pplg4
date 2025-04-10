<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function myAttendance()
    {
        return response()->json(['message' => 'ini data absensi siswa']);
    }
}
