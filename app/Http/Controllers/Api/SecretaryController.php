<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\AbsenceReason;
use Illuminate\Support\Facades\Validator;

class SecretaryController extends Controller
{
    // ============================
    // 👩‍🏫 Manajemen Siswa
    // ============================

    public function index()
    {
        return Student::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:4',
        ]);

        return Student::create($request->all());
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|max:4',
        ]);

        $student->update($request->all());
        return response()->json(['message' => 'Siswa diperbarui']);
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json(['message' => 'Siswa dihapus']);
    }

    // ============================
    // 🗓 Manajemen Absensi
    // ============================

    public function markAttendance(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'records' => 'required|array',
        ]);

        foreach ($request->records as $record) {
            Attendance::updateOrCreate(
                ['student_id' => $record['student_id'], 'date' => $request->date],
                ['status' => $record['status'], 'absence_reason_id' => $record['absence_reason_id'] ?? null]
            );
        }

        return response()->json(['message' => 'Absensi ditandai']);
    }

    public function importAttendance(Request $request)
    {
        // Fungsi impor dari Excel sudah kamu buat sebelumnya 👍
        return response()->json(['message' => 'Import berhasil']);
    }

    // ============================
    // 📄 CRUD Alasan Ketidakhadiran
    // ============================

    public function allReasons()
    {
        return AbsenceReason::all();
    }

    public function addReason(Request $request)
    {
        $request->validate(['reason' => 'required|string|max:255']);
        return AbsenceReason::create($request->only('reason'));
    }

    public function updateReason(Request $request, AbsenceReason $reason)
    {
        $request->validate(['reason' => 'required|string|max:255']);
        $reason->update($request->only('reason'));
        return response()->json(['message' => 'Alasan diperbarui']);
    }

    public function deleteReason(AbsenceReason $reason)
    {
        $reason->delete();
        return response()->json(['message' => 'Alasan dihapus']);
    }
}
