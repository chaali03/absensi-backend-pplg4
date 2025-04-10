<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AttendanceImport;

class SecretaryController extends Controller
{
    // Ambil daftar siswa
    public function index()
    {
        return response()->json(Student::all());
    }

    // Tambah siswa baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:4',
        ]);

        $student = Student::create($validated);

        return response()->json($student, 201);
    }

    // Update data siswa
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:4',
        ]);

        $student->update($validated);

        return response()->json($student);
    }

    // Hapus siswa
    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json(['message' => 'Siswa dihapus.']);
    }

    // Tandai kehadiran
    public function markAttendance(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
            'absence_reason_id' => 'nullable|exists:absence_reasons,id',
        ]);

        $attendance = Attendance::updateOrCreate(
            ['student_id' => $validated['student_id'], 'date' => $validated['date']],
            ['status' => $validated['status'], 'absence_reason_id' => $validated['absence_reason_id']]
        );

        return response()->json($attendance);
    }

    // Import absensi dari file Excel
    public function importAttendance(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new AttendanceImport, $request->file('file'));

        return response()->json(['message' => 'Data absensi berhasil diimpor.']);
    }
}
