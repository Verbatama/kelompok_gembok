<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AdminLeaveRequestController extends Controller
{
    public function index()
    {
        $leaves = AdminLeaveRequest::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('admin.leave.index', compact('leaves'));
    }

    public function create()
    {
        return view('admin.leave.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_date' => 'required|date|after_or_equal:today',
            'type'       => 'required|in:libur,izin,sakit',
            'reason'     => 'nullable|max:500',
        ]);

        $exists = AdminLeaveRequest::where('user_id', auth()->id())
            ->whereDate('leave_date', $request->leave_date)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Tanggal tersebut sudah diajukan.');
        }

        AdminLeaveRequest::create([
            'user_id'    => auth()->id(),
            'leave_date' => $request->leave_date,
            'type'       => $request->type,
            'reason'     => $request->reason,
        ]);

        return redirect()
            ->route('admin.leave.index')
            ->with('success', 'Pengajuan libur berhasil.');
    }

    public function destroy($id)
    {
        $leave = AdminLeaveRequest::where('user_id', auth()->id())
            ->findOrFail($id);

        if (Carbon::parse($leave->leave_date)->isToday()) {
            return back()->with('error', 'Libur hari ini tidak dapat dibatalkan.');
        }

        $leave->delete();

        return back()->with('success', 'Pengajuan libur berhasil dibatalkan.');
    }
}