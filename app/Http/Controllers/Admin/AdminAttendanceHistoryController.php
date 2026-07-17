<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAttendance;
use Illuminate\Http\Request;

class AdminAttendanceHistoryController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->is_superadmin, 403, 'Hanya superadmin yang dapat mengakses halaman ini.');

        $date = $request->input('date', now()->toDateString());

        $query = AdminAttendance::with('user')
            ->whereDate('created_at', $date)
            ->latest();

        if ($request->filled('is_late')) {
            if ($request->is_late == '1') {
                $query->where('status', 'check-in')
                      ->whereRaw('TIME(created_at) > check_in_limit');
            } else {
                $query->where('status', 'check-in')
                      ->whereRaw('TIME(created_at) <= check_in_limit');
            }
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $histories = $query->paginate(20)->withQueryString();

        $totalTepat = AdminAttendance::whereDate('created_at', $date)
            ->where('status', 'check-in')
            ->whereRaw('TIME(created_at) <= check_in_limit')
            ->count();

        $totalTerlambat = AdminAttendance::whereDate('created_at', $date)
            ->where('status', 'check-in')
            ->whereRaw('TIME(created_at) > check_in_limit')
            ->count();

        return view('admin.attendance-admin.history', compact('histories', 'totalTepat', 'totalTerlambat'));
    }
}