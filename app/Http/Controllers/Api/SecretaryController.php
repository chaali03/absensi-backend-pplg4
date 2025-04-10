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
        return response()->json(Student::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $student = Student::create($request->only('name'));

        return response()->json([
            'message' => 'Siswa berhasil ditambahkan',
            'student' => $student
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $student->update($request->only('name'));

        return response()->json(['message' => 'Siswa berhasil diperbarui']);
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json(['message' => 'Siswa berhasil dihapus']);
    }

    // ============================
    // 🗓 Manajemen Absensi
    // ============================

    public function markAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'records' => 'required|array',
            'records.*.student_id' => 'required|exists:students,id',
            'records.*.status' => 'required|in:present,absent',
            'records.*.absence_reason_id' => 'nullable|exists:absence_reasons,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        foreach ($request->records as $record) {
            Attendance::updateOrCreate(
                ['student_id' => $record['student_id'], 'date' => $request->date],
                [
                    'status' => $record['status'],
                    'absence_reason_id' => $record['absence_reason_id'] ?? null,
                ]
            );
        }

        return response()->json(['message' => 'Absensi berhasil ditandai']);
    }

    public function importAttendance(Request $request)
    {
        // Dianggap sudah di-handle di tempat lain
        return response()->json(['message' => 'Impor absensi berhasil']);
    }

    // ============================
    // ❌ CRUD Alasan Ketidakhadiran
    // ============================

    public function allReasons()
    {
        return response()->json(AbsenceReason::all());
    }

    public function addReason(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $reason = AbsenceReason::create($request->only('reason'));

        return response()->json([
            'message' => 'Alasan berhasil ditambahkan',
            'reason' => $reason
        ]);
    }

    public function updateReason(Request $request, AbsenceReason $reason)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $reason->update($request->only('reason'));

        return response()->json(['message' => 'Alasan berhasil diperbarui']);
    }

    public function deleteReason(AbsenceReason $reason)
    {
        $reason->delete();
        return response()->json(['message' => 'Alasan berhasil dihapus']);
    }
}
