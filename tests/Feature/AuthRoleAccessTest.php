<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_berhasil_dan_dapat_token()
    {
        $user = User::factory()->create([
            'email' => 'secretary@example.com',
            'password' => bcrypt('password'),
            'role' => 'secretary',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'secretary@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token', 'token_type', 'user']);
    }

    /** @test */
    public function hanya_secretary_yang_bisa_akses_route_secretary()
    {
        $secretary = User::factory()->create([
            'role' => 'secretary',
        ]);

        $token = $secretary->createToken('test_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/secretary/students');

        $response->assertStatus(200); // 200 OK (route ada dan role sesuai)
    }

    /** @test */
    public function student_tidak_bisa_akses_route_secretary()
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        $token = $student->createToken('test_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/secretary/students');

        $response->assertStatus(403); // 403 Forbidden (karena role salah)
    }
}
