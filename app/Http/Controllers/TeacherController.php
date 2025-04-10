<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;

class TeacherController extends Controller
{
    /**
     * Ringkasan kehadiran: by semester, bulan, minggu, hari
     * GET /api/teacher/summary?type=semester&date=2025-01-01
     */
    public function summary(Request $request)
    {
        $type = $request->query('type', 'semester'); // semester, month, week, day
        $date = $request->query('date', now()->toDateString());

        $query = Attendance::select('student_id', 'status', 'date')
            ->with('student');

        switch ($type) {
            case 'month':
                $query->whereMonth('date', date('m', strtotime($date)))
                      ->whereYear('date', date('Y', strtotime($date)));
                break;
            case 'week':
                $query->whereBetween('date', [
                    date('Y-m-d', strtotime($date . ' -' . (date('N', strtotime($date)) - 1) . ' days')),
                    date('Y-m-d', strtotime($date . ' +' . (7 - date('N', strtotime($date))) . ' days'))
                ]);
                break;
            case 'day':
                $query->whereDate('date', $date);
                break;
            case 'semester':
            default:
                $month = date('n', strtotime($date));
                if ($month >= 1 && $month <= 6) {
                    $query->whereBetween('date', [date('Y-01-01'), date('Y-06-30')]);
                } else {
                    $query->whereBetween('date', [date('Y-07-01'), date('Y-12-31')]);
                }
                break;
        }

        $data = $query->get();

        return response()->json($data);
    }

    /**
     * Lihat daftar kehadiran berdasarkan tanggal tertentu
     * GET /api/teacher/attendance/2025-04-10
     */
    public function attendanceByDate($date)
    {
        $attendance = Attendance::with(['student', 'absenceReason'])
            ->whereDate('date', $date)
            ->get();

        return response()->json($attendance);
    }

    /**
     * Export data absensi ke Excel
     * GET /api/teacher/export
     */
    public function exportExcel()
    {
        return Excel::download(new AttendanceExport, 'rekap-absensi.xlsx');
    }
}
