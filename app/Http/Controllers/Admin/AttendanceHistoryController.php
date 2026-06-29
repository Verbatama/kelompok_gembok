<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TechnicianAttendance;

class AttendanceHistoryController extends Controller
{
 public function index()
{
    $histories = TechnicianAttendance::with('technician')
        ->latest()
        ->paginate(20);

    $totalTepat = TechnicianAttendance::where('is_late', false)->count();

    $totalTerlambat = TechnicianAttendance::where('is_late', true)->count();

    return view('admin.attendance.history', compact(
        'histories',
        'totalTepat',
        'totalTerlambat'
    ));
}
    }

