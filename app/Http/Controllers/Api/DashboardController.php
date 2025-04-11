<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $tanggalHariIni = Carbon::today()->toDateString();

        return response()->json([
            'message' => 'Dashboard Sekretaris',
            'jumlah_siswa' => Student::count(),
            'jumlah_hadir_hari_ini' => Attendance::where('date', $tanggalHariIni)->where('status', 'hadir')->count(),
            'jumlah_tidak_hadir_hari_ini' => Attendance::where('date', $tanggalHariIni)->where('status', 'tidak_hadir')->count(),
        ]);
    }
}
