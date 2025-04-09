<?php

// database/seeders/AbsenceReasonSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AbsenceReason;

class AbsenceReasonSeeder extends Seeder
{
    public function run(): void
    {
        $reasons = [
            ['reason' => 'Sakit', 'description' => 'Ketidakhadiran karena alasan kesehatan'],
            ['reason' => 'Izin', 'description' => 'Izin resmi dari orang tua atau wali'],
            ['reason' => 'Tanpa Keterangan', 'description' => 'Tidak hadir tanpa alasan yang jelas (alfa)'],
        ];

        foreach ($reasons as $data) {
            AbsenceReason::create($data);
        }
    }
}
