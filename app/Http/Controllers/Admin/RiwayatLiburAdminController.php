<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLeaveRequest;
use Illuminate\Http\Request;

class RiwayatLiburAdminController extends Controller
{
    public function index(Request $request)
    {
        // Proteksi tambahan di level controller (selain middleware/route)
        abort_unless(auth()->user()->is_superadmin, 403, 'Anda tidak memiliki akses ke halaman ini.');

        $query = AdminLeaveRequest::with('user')->latest('leave_date');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
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

        $admins = \App\Models\User::orderBy('name')->get();

        return view('admin.riwayat-libur-admin.index', compact('leaves', 'admins'));
    }
}