<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nis',
    ];

    /**
     * Relasi ke attendance (1 siswa punya banyak data kehadiran)
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
