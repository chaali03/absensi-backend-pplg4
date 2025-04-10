<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{
    /**
     * 📊 Ringkasan kehadiran berdasarkan tipe: day, week, month, semester
     */
    public function summary(Request $request)
    {
        $type = $request->query('type', 'day'); // default: day
        $date = Carbon::parse($request->query('date', now()));

        // Hitung rentang waktu berdasarkan jenis summary
        switch ($type) {
            case 'week':
                $from = $date->copy()->startOfWeek();
                $to = $date->copy()->endOfWeek();
                break;

            case 'month':
                $from = $date->copy()->startOfMonth();
                $to = $date->copy()->endOfMonth();
                break;

            case 'semester':
                // Semester 1: Jul–Dec, Semester 2: Jan–Jun
                $month = $date->month;
                if ($month >= 7 && $month <= 12) {
                    $from = Carbon::create($date->year, 7, 1);
                    $to = Carbon::create($date->year, 12, 31);
                } else {
                    $from = Carbon::create($date->year, 1, 1);
                    $to = Carbon::create($date->year, 6, 30);
                }
                break;

            default: // type: day
                $from = $date;
                $to = $date;
        }

        // Ambil dan kelompokkan data absensi per siswa
        $summary = Attendance::whereBetween('date', [$from, $to])
            ->with(['student', 'absenceReason'])
            ->get()
            ->groupBy('student_id')
            ->map(function ($records, $studentId) {
                return [
                    'student_id' => $studentId,
                    'name' => optional($records->first()->student)->name,
                    'present' => $records->where('status', 'present')->count(),
                    'absent' => $records->where('status', 'absent')->count(),
                ];
            })
            ->values();

        return response()->json([
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'summary' => $summary
        ]);
    }

    /**
     * 👀 Detail absensi pada tanggal tertentu
     */
    public function attendanceByDate($date)
    {
        $data = Attendance::whereDate('date', $date)
            ->with(['student', 'absenceReason'])
            ->get();

        return response()->json([
            'date' => $date,
            'attendances' => $data,
        ]);
    }

    /**
     * 📥 Export seluruh data absensi ke Excel
     */
    public function exportExcel()
    {
        return Excel::download(new AttendanceExport, 'rekap-absensi.xlsx');
    }
}
