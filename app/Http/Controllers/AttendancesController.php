<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Perbaikan typo 'illuminate' menjadi 'Illuminate'

class AttendancesController extends Controller
{
    public function index()
    {
        // Mengambil riwayat absen hari ini untuk ditampilkan di tabel bawah form
        $riwayatAbsen = DB::table('attendances')
            ->whereDate('created_at', today())
            ->latest()
            ->paginate(10);

        return view('attendance.index', compact('riwayatAbsen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'selfie' => 'required',
            'status' => 'required|in:check-in,check-out', // Validasi tambahan untuk memastikan status aman
        ], [
            'nama.required' => 'Kolom nama wajib diisi!',
            'selfie.required' => 'Anda wajib mengambil foto selfie!',
        ]);

        $imagePath = null;
        if ($request->filled('selfie')) {
            $base64 = $request->selfie;
            if (preg_match('/^data:image\/(png|jpg|jpeg);base64,/', $base64, $m)) {
                $ext = $m[1] === 'jpeg' ? 'jpg' : $m[1];
                $data = base64_decode(preg_replace('/^data:image\/(png|jpg|jpeg);base64,/', '', $base64));
                
                if ($data !== false) {
                    $filename = 'attendance/public_'.Str::random(15).'.'.$ext;
                    Storage::disk('public')->put($filename, $data);
                    $imagePath = 'storage/'.$filename;
                }
            }
        }

        // PERBAIKAN: Menambahkan field 'status' ke dalam query insert database
        DB::table('attendances')->insert([
            'nama' => $request->nama,
            'image_selfie' => $imagePath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $request->status, // <-- Ini yang bikin tombol check-out berfungsi dan tersimpan dengan benar!
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mengubah pesan sukses dinamis berdasarkan status yang dipilih
        $pesanStatus = $request->status === 'check-in' ? 'CHECK-IN' : 'CHECK-OUT';

        return back()->with('success', 'Absensi ' . $pesanStatus . ' atas nama ' . $request->nama . ' berhasil disimpan!');
    }
}