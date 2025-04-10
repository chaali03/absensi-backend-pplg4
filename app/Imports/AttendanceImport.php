<?php

namespace App\Imports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Attendance([
            'student_id' => $row['student_id'],
            'date' => $row['date'],
            'status' => $row['status'],
            'absence_reason_id' => $row['absence_reason_id'] ?? null,
        ]);
    }
}
