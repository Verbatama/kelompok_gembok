<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\AdminLeaveRequest;

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
             'check_in_limit' => 'nullable|date_format:H:i',
            'bonus_check_in_mulai' => 'nullable|date_format:H:i',
            'bonus_check_in_selesai' => 'nullable|date_format:H:i',
            'bonus_check_out_mulai' => 'nullable|date_format:H:i',
            'bonus_check_out_selesai' => 'nullable|date_format:H:i',
        ]);

        $now = Carbon::now('Asia/Jakarta');
       $user = auth()->user();

       // Cek apakah hari ini sedang libur
        $today = Carbon::today('Asia/Jakarta');

        $leave = AdminLeaveRequest::where('user_id', auth()->id())
        ->whereDate('leave_date', $today)
            ->exists();

        if ($leave) {
        return back()->with(
        'error',
        'Hari ini Anda sedang mengajukan libur sehingga tidak dapat melakukan absensi.'
    );
}

         $limit = Carbon::today('Asia/Jakarta')
        ->setTimeFromTimeString($user->check_in_limit);

       $terlambat = $request->status === 'check-in' && $now->greaterThan($limit);

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

        $bonusDidapat = false;
        
        if ($leave) {
    $bonusDidapat = false;
}
        

        if ($request->status == 'check-in' && $now->lte($limit)) {
    $bonusDidapat = true;

}

    if ($request->status == 'check-out') {

    $mulaiBonus = Carbon::today('Asia/Jakarta')
        ->setTimeFromTimeString($user->bonus_check_out_mulai);

    $selesaiBonus = Carbon::today('Asia/Jakarta')
        ->setTimeFromTimeString($user->bonus_check_out_selesai);

    if ($now->between($mulaiBonus, $selesaiBonus)) {
        $bonusDidapat = true;
        $bonusNominal = $user->bonus_check_out_nominal;
    }
}

        AdminAttendance::create([
            'user_id'      => auth()->id(),
            'image_selfie' => 'attendances/' . $filename,
            'status'       => $request->status,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'check_in_limit'   => $user->check_in_limit,
            'bonus_check_out_mulai'  => $user->bonus_check_out_mulai,
            'bonus_check_out_selesai'=> $user->bonus_check_out_selesai,
            
            'bonus_didapat'=> $bonusDidapat,
        ]);

        if ($request->status === 'check-in') {

    if ($terlambat) {
        return back()->with('warning', 'Check-in berhasil. Anda terlambat (melewati pukul 09.10 WIB).');
    }

        return back()->with('success', 'Check-in berhasil tepat waktu.');
}

        return back()->with('success', 'Check-out berhasil.');
    }
}