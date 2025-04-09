<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;

class AttendanceImport implements ToModel
{
    public function model(array $row)
    {
        // Abaikan header row
        if ($row[0] === 'student_id') {
            return null;
        }

        return new Attendance([
            'student_id' => $row[0],
            'date'       => $row[1],
            'status'     => $row[2],
            'reason_id'  => $row[3] ?? null,
        ]);
    }
}
