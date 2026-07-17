<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\TechnicianLeaveRequest;
use Illuminate\Http\Request;

class RiwayatPengajuanLiburController extends Controller
{
    public function index(Request $request)
    {
        $query = TechnicianLeaveRequest::with('technician')->latest('leave_date');

        if ($request->filled('technician_id')) {
            $query->where('technician_id', $request->technician_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('from')) {
            $query->whereDate('leave_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('leave_date', '<=', $request->to);
        }

        $leaves = $query->paginate(20)->withQueryString();
        $technicians = Technician::orderBy('name')->get();

        return view('admin.riwayat-libur.index', compact('leaves', 'technicians'));
    }
}