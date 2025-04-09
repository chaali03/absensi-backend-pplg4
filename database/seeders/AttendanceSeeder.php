<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\AbsenceReason;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $absenceReasons = AbsenceReason::all();

        // Set tanggal awal dan akhir (misalnya seminggu terakhir)
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();

        foreach ($students as $student) {
            $date = $startDate->copy();

            while ($date <= $endDate) {
                // Random status
                $status = collect(['hadir', 'sakit', 'izin', 'alfa'])->random();

                // Isi reason hanya kalau bukan "hadir"
                $absenceReasonId = null;
                if ($status !== 'hadir') {
                    $absenceReasonId = $absenceReasons->random()->id;
                }

                Attendance::create([
                    'student_id' => $student->id,
                    'date' => $date->format('Y-m-d'),
                    'status' => $status,
                    'absence_reason_id' => $absenceReasonId,
                ]);

                $date->addDay();
            }
        }
    }
}
