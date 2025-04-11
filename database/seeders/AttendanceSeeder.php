<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\AbsenceReason;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $student = Student::first(); // contoh untuk 1 siswa
        $reason = AbsenceReason::where('reason', 'Izin')->first();

        $data = [
            ['date' => '2025-04-03', 'status' => 'izin', 'reason_id' => $reason->id],
            ['date' => '2025-04-04', 'status' => 'hadir', 'reason_id' => null],
        ];

        foreach ($data as $entry) {
            Attendance::updateOrCreate(
                ['student_id' => $student->id, 'date' => Carbon::parse($entry['date'])->toDateString()],
                [
                    'status' => $entry['status'],
                    'absence_reason_id' => $entry['reason_id']
                ]
            );
        }
    }
}
