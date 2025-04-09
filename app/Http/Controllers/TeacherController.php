<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;

class TeacherController extends Controller
{
    // Ringkasan absensi semua siswa
    public function summary()
    {
        $summary = DB::table('attendances')
            ->select('student_id', DB::raw('count(*) as total'), DB::raw("sum(status = 'present') as hadir"), DB::raw("sum(status = 'absent') as tidak_hadir"))
            ->groupBy('student_id')
            ->get();

        return response()->json($summary);
    }

    // Melihat absensi semua siswa berdasarkan tanggal
    public function attendanceByDate($date)
    {
        $data = Attendance::with('student', 'reason')
            ->where('date', $date)
            ->get();

        return response()->json([
            'date' => $date,
            'attendances' => $data
        ]);
    }

    // Export absensi ke Excel
    public function exportExcel(Request $request)
    {
        return Excel::download(new AttendanceExport, 'attendance.xlsx');
    }
}
