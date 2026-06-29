<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminAttendanceController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $today  = Carbon::today();

        // Ambil semua absensi hari ini milik user ini, urut waktu terbaru
        $records = AdminAttendance::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->orderBy('created_at')
            ->get();

        $checkInRecord  = $records->firstWhere('status', 'check-in');
        $checkOutRecord = $records->firstWhere('status', 'check-out');

        $sudahCheckin  = (bool) $checkInRecord;
        $sudahCheckout = (bool) $checkOutRecord;

        $jamMasuk  = $checkInRecord  ? Carbon::parse($checkInRecord->created_at)->format('H:i')  : null;
        $jamKeluar = $checkOutRecord ? Carbon::parse($checkOutRecord->created_at)->format('H:i') : null;

        return view('admin.attendance.index', compact(
            'sudahCheckin', 'sudahCheckout', 'jamMasuk', 'jamKeluar'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image_selfie' => 'required',
            'status'       => 'required|in:check-in,check-out',
            'latitude'     => 'nullable',
            'longitude'    => 'nullable',
        ]);

        // Cegah double check-in / double check-out di hari yang sama
        $today  = Carbon::today();
        $exists = AdminAttendance::where('user_id', auth()->id())
            ->where('status', $request->status)
            ->whereDate('created_at', $today)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah melakukan ' . $request->status . ' hari ini.');
        }

        // Cegah check-out tanpa check-in
        if ($request->status === 'check-out') {
            $checkedIn = AdminAttendance::where('user_id', auth()->id())
                ->where('status', 'check-in')
                ->whereDate('created_at', $today)
                ->exists();

            if (!$checkedIn) {
                return back()->with('error', 'Anda belum melakukan check-in hari ini.');
            }
        }

        // Simpan foto base64
        $image    = $request->image_selfie;
        $image    = str_replace('data:image/jpeg;base64,', '', $image);
        $image    = str_replace(' ', '+', $image);
        $filename = 'admin_' . Str::uuid() . '.jpg';

        Storage::disk('public')->put('attendances/' . $filename, base64_decode($image));

        AdminAttendance::create([
            'user_id'      => auth()->id(),
            'image_selfie' => 'attendances/' . $filename,
            'status'       => $request->status,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
        ]);

        $label = $request->status === 'check-in' ? 'Check-in' : 'Check-out';
        return back()->with('success', $label . ' berhasil dicatat.');
    }
}