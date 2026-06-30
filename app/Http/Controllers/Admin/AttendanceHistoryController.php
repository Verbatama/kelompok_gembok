<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TechnicianAttendance;

class AttendanceHistoryController extends Controller
{
    public function index()
    {
        $histories = TechnicianAttendance::with('technician')

            // filter tanggal
            ->when(request('date'), function ($query) {
                $query->whereDate('created_at', request('date'));
            })

            // filter terlambat / tepat waktu
            ->when(request('is_late') !== null && request('is_late') !== '', function ($query) {
                $query->where('is_late', request('is_late'));
            })

            // filter nama teknisi
            ->when(request('search'), function ($query) {
                $query->whereHas('technician', function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%');
                });
            })

            ->latest()
            ->paginate(20)
            ->withQueryString();


        // hanya check-in
        $totalTepat = TechnicianAttendance::where('status', 'check-in')
            ->where('is_late', false)
            ->count();

        $totalTerlambat = TechnicianAttendance::where('status', 'check-in')
            ->where('is_late', true)
            ->count();


        return view('admin.attendance.history', compact(
            'histories',
            'totalTepat',
            'totalTerlambat'
        ));
    }
}