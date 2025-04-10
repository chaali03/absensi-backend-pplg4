<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Attendance::with(['student', 'absenceReason'])
            ->get()
            ->map(function ($attendance) {
                return [
                    'Nama Siswa' => $attendance->student->name,
                    'Tanggal' => $attendance->date->format('Y-m-d'),
                    'Status' => ucfirst($attendance->status),
                    'Alasan' => optional($attendance->absenceReason)->reason ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Tanggal',
            'Status',
            'Alasan',
        ];
    }
}
