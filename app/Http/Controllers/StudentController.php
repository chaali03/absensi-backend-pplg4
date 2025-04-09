<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;

class StudentController extends Controller
{
    // Menampilkan riwayat absensi milik sendiri
    public function myAttendance()
    {
        $user = Auth::user();

        $attendance = Attendance::with('reason')
            ->where('student_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'attendance' => $attendance
        ]);
    }
}
