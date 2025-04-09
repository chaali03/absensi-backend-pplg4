<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenceReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason',
        'description',
    ];

    /**
     * Relasi ke attendance (banyak attendance bisa punya 1 reason)
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
