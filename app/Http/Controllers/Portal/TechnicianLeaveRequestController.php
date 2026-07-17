<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\TechnicianLeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TechnicianLeaveRequestController extends Controller
{
    public function index()
    {
        if (!session()->has('technician_id')) {
            return redirect()->route('technician.login');
        }

        $techId = session('technician_id');

        $leaves = TechnicianLeaveRequest::where('technician_id', $techId)
            ->latest()
            ->get();

        return view('technician.leave.index', compact('leaves'));
    }

    public function create()
    {
        if (!session()->has('technician_id')) {
            return redirect()->route('technician.login');
        }

        return view('technician.leave.create');
    }

    public function store(Request $request)
    {
        if (!session()->has('technician_id')) {
            return redirect()->route('technician.login');
        }

        $request->validate([
            'leave_date' => 'required|date|after_or_equal:today',
            'type'       => 'required|in:libur,izin,sakit',
            'reason'     => 'nullable|max:500',
        ]);

        $techId = session('technician_id');

        $exists = TechnicianLeaveRequest::where('technician_id', $techId)
            ->whereDate('leave_date', $request->leave_date)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Tanggal tersebut sudah diajukan.');
        }

        TechnicianLeaveRequest::create([
            'technician_id' => $techId,
            'leave_date'    => $request->leave_date,
            'type'          => $request->type,
            'reason'        => $request->reason,
        ]);

        return redirect()
            ->route('technician.leave.index')
            ->with('success', 'Pengajuan libur berhasil.');
    }

    public function destroy($id)
    {
        if (!session()->has('technician_id')) {
            return redirect()->route('technician.login');
        }

        $techId = session('technician_id');

        $leave = TechnicianLeaveRequest::where('technician_id', $techId)
            ->findOrFail($id);

        if (Carbon::parse($leave->leave_date)->isToday()) {
            return back()->with('error', 'Libur hari ini tidak dapat dibatalkan.');
        }

        $leave->delete();

        return back()->with('success', 'Pengajuan libur berhasil dibatalkan.');
    }
}