<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbsenceReason;

class AbsenceReasonController extends Controller
{
    // Ambil semua alasan
    public function index()
    {
        return response()->json(AbsenceReason::all());
    }

    // Tambah alasan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string|unique:absence_reasons,reason',
            'description' => 'nullable|string',
        ]);

        $reason = AbsenceReason::create($validated);

        return response()->json($reason, 201);
    }

    // Update alasan
    public function update(Request $request, AbsenceReason $absence_reason)
    {
        $validated = $request->validate([
            'reason' => 'required|string|unique:absence_reasons,reason,' . $absence_reason->id,
            'description' => 'nullable|string',
        ]);

        $absence_reason->update($validated);

        return response()->json($absence_reason);
    }

    // Hapus alasan
    public function destroy(AbsenceReason $absence_reason)
    {
        $absence_reason->delete();

        return response()->json(['message' => 'Alasan ketidakhadiran dihapus.']);
    }
}
