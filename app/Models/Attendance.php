<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'status',
        'absence_reason_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Relasi ke model Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relasi ke model AbsenceReason (nullable)
     */
    public function absenceReason()
    {
        return $this->belongsTo(AbsenceReason::class);
    }
}
