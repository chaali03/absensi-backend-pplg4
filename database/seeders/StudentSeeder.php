<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['name' => 'Budi', 'nis' => '12345', 'status' => 'aktif'],
            ['name' => 'Ani', 'nis' => '67890', 'status' => 'aktif'],
        ];

        foreach ($students as $data) {
            Student::updateOrCreate(
                ['nis' => $data['nis']],
                ['name' => $data['name'], 'status' => $data['status']]
            );
        }
    }
}
