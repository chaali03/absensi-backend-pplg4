<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use Carbon\Carbon;

class TeacherController extends Controller
{
    // 📊 Ringkasan absensi siswa (misal total hadir/tidak hadir per siswa)
    public function summary()
    {
        $summary = Student::with(['attendances' => function ($query) {
            $query->select('student_id', 'status');
        }])->get()->map(function ($student) {
            $hadir = $student->attendances->where('status', 'hadir')->count();
            $izin = $student->attendances->where('status', 'izin')->count();
            $sakit = $student->attendances->where('status', 'sakit')->count();
            $alpha = $student->attendances->where('status', 'alpha')->count();

            return [
                'nama' => $student->name,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpha' => $alpha,
            ];
        });

        return response()->json($summary);
    }

    // 📅 Lihat absensi siswa pada tanggal tertentu
    public function attendanceByDate($date)
    {
        $tanggal = Carbon::parse($date)->toDateString();

        $absensi = Attendance::with('student')
            ->whereDate('date', $tanggal)
            ->get()
            ->map(function ($a) {
                return [
                    'nama' => $a->student->name,
                    'status' => $a->status,
                    'alasan' => $a->reason,
                ];
            });

        return response()->json([
            'tanggal' => $tanggal,
            'absensi' => $absensi,
        ]);
    }

    // 🧾 Export absensi ke Excel
    public function exportExcel(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        return Excel::download(new AttendanceExport($start, $end), 'rekap_absensi.xlsx');
    }
}
