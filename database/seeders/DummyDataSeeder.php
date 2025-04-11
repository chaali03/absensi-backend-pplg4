<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Sekretaris
        User::create([
            'name' => 'Sekretaris',
            'email' => 'sekretaris@example.com',
            'password' => Hash::make('password'),
            'role' => 'sekretaris',
        ]);

        // Wali Kelas
        User::create([
            'name' => 'WaliKelas',
            'email' => 'wali@example.com',
            'password' => Hash::make('password'),
            'role' => 'wali_kelas',
        ]);

        // 39 Siswa
        for ($i = 1; $i <= 39; $i++) {
            $name = Str::random(4); // max 4 karakter

            $user = User::create([
                'name' => $name,
                'email' => "siswa{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]);

            Student::create([
                'user_id' => $user->id,
                'name' => $name,
            ]);
        }
    }
}
