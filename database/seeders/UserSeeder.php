<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role dulu
        $roles = ['sekretaris', 'siswa', 'wali_kelas'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Buat user dan assign role
        $users = [
            [
                'name' => 'Sekretaris Utama',
                'email' => 'sekretaris@example.com',
                'password' => Hash::make('password'),
                'role' => 'sekretaris'
            ],
            [
                'name' => 'Wali Kelas 1',
                'email' => 'wali@example.com',
                'password' => Hash::make('password'),
                'role' => 'wali_kelas'
            ],
            [
                'name' => 'Siswa A',
                'email' => 'siswa@example.com',
                'password' => Hash::make('password'),
                'role' => 'siswa'
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => $data['password']]
            );
            $user->syncRoles([$data['role']]);
        }
    }
}
