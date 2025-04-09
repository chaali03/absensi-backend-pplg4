<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // tambahan: misal "sekretaris", "wali_kelas", dll
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Cek apakah user adalah sekretaris
     */
    public function isSecretary(): bool
    {
        return $this->role === 'sekretaris';
    }

    /**
     * Cek apakah user adalah wali kelas
     */
    public function isHomeroomTeacher(): bool
    {
        return $this->role === 'wali_kelas';
    }

    /**
     * Cek apakah user adalah siswa
     */
    public function isStudent(): bool
    {
        return $this->role === 'siswa';
    }
}
