<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AbsenceReason;

class AbsenceReasonSeeder extends Seeder
{
    public function run(): void
    {
        $reasons = [
            ['reason' => 'Sakit', 'description' => 'Ketidakhadiran karena alasan kesehatan'],
            ['reason' => 'Izin', 'description' => 'Ketidakhadiran dengan izin resmi'],
            ['reason' => 'Alpha', 'description' => 'Tidak hadir tanpa keterangan'],
        ];

        foreach ($reasons as $data) {
            AbsenceReason::updateOrCreate(
                ['reason' => $data['reason']],
                ['description' => $data['description']]
            );
        }
    }
}
