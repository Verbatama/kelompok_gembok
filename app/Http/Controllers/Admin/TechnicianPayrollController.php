<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\TechnicianAttendance;
use App\Models\TechnicianPayrolls;
use Illuminate\Http\Request;

class TechnicianPayrollController extends Controller
{
    /**
     * Daftar payroll.
     */
    public function index()
    {
        $payrolls = TechnicianPayrolls::with('technician')
            ->latest('tahun')
            ->latest('bulan')
            ->paginate(20);

        return view('admin.payroll.index', compact('payrolls'));
    }

    /**
     * Form tambah payroll.
     */
    public function create()
    {
        $technicians = Technician::orderBy('name')->get();

        return view('admin.payroll.create', compact('technicians'));
    }

    /**
     * Simpan payroll.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'technician_id' => 'required|exists:technicians,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2024',

            // tarif per kejadian
            'denda_telat' => 'required|numeric|min:0',
            'denda_absen' => 'required|numeric|min:0',

            'bonus' => 'required|numeric|min:0',
        ]);

        // Payroll sudah ada?
        $exists = TechnicianPayrolls::where('technician_id', $validated['technician_id'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'technician_id' => 'Payroll periode tersebut sudah dibuat.'
                ])
                ->withInput();
        }

        // Teknisi
        $technician = Technician::findOrFail($validated['technician_id']);

        // Query absensi
        $attendance = TechnicianAttendance::where('technician_id', $technician->id)
            ->whereMonth('created_at', $validated['bulan'])
            ->whereYear('created_at', $validated['tahun']);

        // Hitung telat
        $jumlahTelat = (clone $attendance)
            ->where('is_late', true)
            ->count();

        // Hitung absen
        $jumlahAbsen = (clone $attendance)
            ->where('absent', true)
            ->count();

        // Snapshot
        $gajiPokok = $technician->gaji_pokok;

        // Total potongan
        $totalPotongan =
            ($jumlahTelat * $validated['denda_telat']) +
            ($jumlahAbsen * $validated['denda_absen']);

        // Total diterima
        $totalDiterima =
            $gajiPokok +
            $validated['bonus'] -
            $totalPotongan;

        TechnicianPayrolls::create([
            'technician_id' => $technician->id,

            'bulan' => $validated['bulan'],
            'tahun' => $validated['tahun'],

            'gaji_pokok' => $gajiPokok,

            'jumlah_telat' => $jumlahTelat,
            'jumlah_absen' => $jumlahAbsen,

            // tarif
            'denda_telat' => $validated['denda_telat'],
            'denda_absen' => $validated['denda_absen'],

            'bonus' => $validated['bonus'],

            'total_potongan' => $totalPotongan,
            'total_diterima' => $totalDiterima,

            'status' => 'draft',
            'processed_at' => now(),
        ]);

        return redirect()
            ->route('payroll.index')
            ->with('success', 'Payroll berhasil dibuat.');
    }

    /**
     * Detail payroll.
     */
    public function show(TechnicianPayrolls $technicianPayroll)
    {
        return view('admin.payroll.show', compact('technicianPayroll'));
    }

    /**
     * Form edit payroll.
     */
    public function edit(TechnicianPayrolls $technicianPayroll)
    {
        return view('admin.payroll.edit', compact('technicianPayroll'));
    }

    /**
     * Update payroll.
     */
    public function update(Request $request, TechnicianPayrolls $technicianPayroll)
    {
        $validated = $request->validate([
            'denda_telat' => 'required|numeric|min:0',
            'denda_absen' => 'required|numeric|min:0',
            'bonus' => 'required|numeric|min:0',
            'status' => 'required|in:draft,approved,paid',
        ]);

        // Hitung ulang total potongan
        $totalPotongan =
            ($technicianPayroll->jumlah_telat * $validated['denda_telat']) +
            ($technicianPayroll->jumlah_absen * $validated['denda_absen']);

        // Hitung ulang total diterima
        $totalDiterima =
            $technicianPayroll->gaji_pokok +
            $validated['bonus'] -
            $totalPotongan;

        $technicianPayroll->update([
            'denda_telat' => $validated['denda_telat'],
            'denda_absen' => $validated['denda_absen'],
            'bonus' => $validated['bonus'],
            'status' => $validated['status'],
            'total_potongan' => $totalPotongan,
            'total_diterima' => $totalDiterima,
        ]);

        return redirect()
            ->route('payroll.index')
            ->with('success', 'Payroll berhasil diperbarui.');
    }

    /**
     * Hapus payroll.
     */
    public function destroy(TechnicianPayrolls $technicianPayroll)
    {
        $technicianPayroll->delete();

        return redirect()
            ->route('payroll.index')
            ->with('success', 'Payroll berhasil dihapus.');
    }
    public function attendanceSummary(Request $request)
{
    $request->validate([
        'technician_id' => 'required|exists:technicians,id',
        'bulan' => 'required|integer|min:1|max:12',
        'tahun' => 'required|integer',
    ]);

    $technician = Technician::findOrFail($request->technician_id);

    $attendance = TechnicianAttendance::where('technician_id', $technician->id)
        ->whereMonth('created_at', $request->bulan)
        ->whereYear('created_at', $request->tahun);

    $jumlahTelat = (clone $attendance)
        ->where('is_late', true)
        ->count();

    $jumlahAbsen = (clone $attendance)
        ->where('absent', true)
        ->count();

    return response()->json([
        'gaji_pokok' => $technician->gaji_pokok,
        'jumlah_telat' => $jumlahTelat,
        'jumlah_absen' => $jumlahAbsen,
    ]);
}
}