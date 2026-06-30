<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\TechnicianAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TechnicianAttendanceController extends Controller
{
    public function index()
    {
        if (!session()->has('technician_id')) {
            return redirect()->route('technician.login');
        }

        $techId = session('technician_id');
        $technician = Technician::findOrFail($techId);
        $today = Carbon::today('Asia/Jakarta');

        $records = TechnicianAttendance::where('technician_id', $techId)
            ->whereDate('attendance_date', $today)
            ->orderBy('created_at')
            ->get();

        $checkInRecord = $records->firstWhere('status', 'check-in');
        $checkOutRecord = $records->firstWhere('status', 'check-out');

        $sudahCheckin = (bool) $checkInRecord;
        $sudahCheckout = (bool) $checkOutRecord;

        $jamMasuk = $checkInRecord
            ? Carbon::parse($checkInRecord->created_at)->format('H:i')
            : null;

        $jamKeluar = $checkOutRecord
            ? Carbon::parse($checkOutRecord->created_at)->format('H:i')
            : null;

        return view('technician.attendance.index', compact(
            'technician',
            'sudahCheckin',
            'sudahCheckout',
            'jamMasuk',
            'jamKeluar'
        ));
    }

    public function store(Request $request)
    {
        if (!session()->has('technician_id')) {
            return redirect()->route('technician.login');
        }

        $request->validate([
            'image_selfie' => 'required',
            'status' => 'required|in:check-in,check-out',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);

        $techId = session('technician_id');

        $now = Carbon::now('Asia/Jakarta');
        $today = Carbon::today('Asia/Jakarta');

        // Batas terlambat
        $limit = Carbon::today('Asia/Jakarta')->setTime(9, 10, 0);

        $terlambat = $request->status === 'check-in' && $now->greaterThan($limit);

        // Cegah double check-in / check-out
        $exists = TechnicianAttendance::where('technician_id', $techId)
            ->where('status', $request->status)
            ->whereDate('attendance_date', $today)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah melakukan ' . $request->status . ' hari ini.');
        }

        // Cegah check-out tanpa check-in
        if ($request->status === 'check-out') {
            $checkedIn = TechnicianAttendance::where('technician_id', $techId)
                ->where('status', 'check-in')
                ->whereDate('attendance_date', $today)
                ->exists();

            if (!$checkedIn) {
                return back()->with('error', 'Anda belum melakukan check-in hari ini.');
            }
        }

        // Simpan selfie
        $image = $request->image_selfie;
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);

        $filename = 'technician_' . Str::uuid() . '.jpg';

        Storage::disk('public')->put(
            'attendances/' . $filename,
            base64_decode($image)
        );

        // Simpan absensi
        TechnicianAttendance::create([
            'technician_id' => $techId,
            'attendance_date' => $today,
            'image_selfie' => 'attendances/' . $filename,
            'status' => $request->status,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_late' => $terlambat,
        ]);

        if ($request->status === 'check-in') {
            if ($terlambat) {
                return back()->with(
                    'warning',
                    'Check-in berhasil. Anda terlambat (melewati pukul 09.10 WIB).'
                );
            }

            return back()->with(
                'success',
                'Check-in berhasil tepat waktu.'
            );
        }

        return back()->with(
            'success',
            'Check-out berhasil.'
        );
    }
}
