<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            ['name' => 'Ani', 'nis' => '12345', 'status' => 'aktif'],
            ['name' => 'Budi', 'nis' => '12346', 'status' => 'aktif'],
            ['name' => 'Cici', 'nis' => '12347', 'status' => 'keluar'],
            ['name' => 'Dito', 'nis' => '12348', 'status' => 'dikeluarkan'],
            ['name' => 'Eka', 'nis' => '12349', 'status' => 'aktif'],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }
    }
}
