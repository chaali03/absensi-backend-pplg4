<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles; // ✅ Tambahkan ini!

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; // ✅ Tambahkan HasRoles

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ✅ Tambahkan ini di dalam class User
    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            if ($user->role && ! $user->hasRole($user->role)) {
                $user->assignRole($user->role);
            }
        });

        static::updated(function ($user) {
            if ($user->isDirty('role')) {
                $user->syncRoles($user->role);
            }
        });
    }

    // Helper untuk cek role
    public function isSecretary(): bool
    {
        return $this->hasRole('sekretaris');
    }

    public function isHomeroomTeacher(): bool
    {
        return $this->hasRole('wali_kelas');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('siswa');
    }
}
