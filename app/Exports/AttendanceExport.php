<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Attendance::with('student')
            ->get()
            ->map(function ($attendance) {
                return [
                    'Nama Siswa' => $attendance->student->name,
                    'Tanggal' => $attendance->date,
                    'Status' => $attendance->status,
                    'Alasan' => $attendance->reason,
                ];
            });
    }

    public function headings(): array
    {
        return ['Nama Siswa', 'Tanggal', 'Status', 'Alasan'];
    }
}
