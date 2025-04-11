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

    public function dashboard()
    {
        if (auth()->user()->hasRole('sekretaris')) {
            return response()->json(['message' => 'Ini dashboard sekretaris']);
        }

        return response()->json(['message' => 'Akses ditolak'], 403);
    }

    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:sekretaris']);
    }

    // ============================
    // 👩‍🏫 Manajemen Siswa
    // ============================

    public function index()
    {
        try {
            $students = Student::all();
    
            return response()->json([
                'success' => true,
                'message' => 'Daftar siswa berhasil diambil.',
                'data' => $students
            ]);
        } catch (\Throwable $e) {
            \Log::error('Gagal ambil siswa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan di server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Student $student)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil ditemukan.',
            'data' => $student
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $student = Student::create($request->only('name'));

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil ditambahkan',
            'data' => $student
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $student->update($request->only('name'));

        return response()->json(['success' => true, 'message' => 'Siswa berhasil diperbarui']);
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json(['success' => true, 'message' => 'Siswa berhasil dihapus']);
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
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
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

        return response()->json(['success' => true, 'message' => 'Absensi berhasil ditandai']);
    }

    public function importAttendance(Request $request)
    {
        // Misalnya kamu handle Excel upload di tempat lain
        return response()->json([
            'success' => true,
            'message' => 'Impor absensi berhasil (mock response).'
        ]);
    }

    // ============================
    // ❌ CRUD Alasan Ketidakhadiran
    // ============================

    public function allReasons()
    {
        $reasons = AbsenceReason::all();
        return response()->json([
            'success' => true,
            'message' => 'Daftar alasan berhasil diambil.',
            'data' => $reasons
        ]);
    }

    public function addReason(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $reason = AbsenceReason::create($request->only('reason'));

        return response()->json([
            'success' => true,
            'message' => 'Alasan berhasil ditambahkan',
            'data' => $reason
        ]);
    }

    public function updateReason(Request $request, AbsenceReason $reason)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $reason->update($request->only('reason'));

        return response()->json(['success' => true, 'message' => 'Alasan berhasil diperbarui']);
    }

    public function deleteReason(AbsenceReason $reason)
    {
        $reason->delete();
        return response()->json(['success' => true, 'message' => 'Alasan berhasil dihapus']);
    }
}
