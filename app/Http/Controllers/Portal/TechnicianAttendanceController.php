<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\TechnicianAttendance;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;


class TechnicianAttendanceController extends Controller
{
    public function index()
    {
        if (!session()->has('technician_id')) {
            return redirect()->route('technician.login');
        }

        $techId = session('technician_id');
        $technician = Technician::find($techId);
        $today  = Carbon::today();

        $records = TechnicianAttendance::where('technician_id', $techId)
            ->whereDate('created_at', $today)
            ->orderBy('created_at')
            ->get();

        $checkInRecord  = $records->firstWhere('status', 'check-in');
        $checkOutRecord = $records->firstWhere('status', 'check-out');

        $sudahCheckin  = (bool) $checkInRecord;
        $sudahCheckout = (bool) $checkOutRecord;

        $jamMasuk  = $checkInRecord
            ? Carbon::parse($checkInRecord->created_at)->format('H:i')
            : null;
        $jamKeluar = $checkOutRecord
            ? Carbon::parse($checkOutRecord->created_at)->format('H:i')
            : null;

        return view('technician.attendance.index', compact(
            'technician', 'sudahCheckin', 'sudahCheckout', 'jamMasuk', 'jamKeluar'
        ));
    }

    public function store(Request $request)
    {
        if (!session()->has('technician_id')) {
            return redirect()->route('technician.login');
        }

        $request->validate([
            'image_selfie' => 'required',
            'status'       => 'required|in:check-in,check-out',
            'latitude'     => 'nullable',
            'longitude'    => 'nullable',
        ]);

        $techId = session('technician_id');
        $today  = Carbon::today();

        // Cegah double check-in / check-out
        $exists = TechnicianAttendance::where('technician_id', $techId)
            ->where('status', $request->status)
            ->whereDate('created_at', $today)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah melakukan ' . $request->status . ' hari ini.');
        }

        // Cegah check-out tanpa check-in
        if ($request->status === 'check-out') {
            $checkedIn = TechnicianAttendance::where('technician_id', $techId)
                ->where('status', 'check-in')
                ->whereDate('created_at', $today)
                ->exists();

            if (!$checkedIn) {
                return back()->with('error', 'Anda belum melakukan check-in hari ini.');
            }
        }

        $image    = $request->image_selfie;
        $image    = str_replace('data:image/jpeg;base64,', '', $image);
        $image    = str_replace(' ', '+', $image);
        $filename = 'technician_' . Str::uuid() . '.jpg';

        Storage::disk('public')->put('attendances/' . $filename, base64_decode($image));

        TechnicianAttendance::create([
            'technician_id' => $techId,
            'image_selfie'  => 'attendances/' . $filename,
            'status'        => $request->status,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
        ]);

        $label = $request->status === 'check-in' ? 'Check-in' : 'Check-out';
        return back()->with('success', $label . ' berhasil dicatat.');
    }
}