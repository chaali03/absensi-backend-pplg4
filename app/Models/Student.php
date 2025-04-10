<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function myAttendance()
    {
        $studentId = Auth::user()->student_id; // Pastikan user punya relasi ke tabel students

        $attendance = Attendance::with('absenceReason')
            ->where('student_id', $studentId)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($attendance);
    }
}

