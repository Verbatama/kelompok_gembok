<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modem;
use Illuminate\Http\Request;

class ModemController extends Controller
{
    public function index(Request $request)
{
    $query = Modem::query();

    // Search
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('nomor_aset_internal', 'like', "%{$search}%")
              ->orWhere('serial_number', 'like', "%{$search}%")
              ->orWhere('merek', 'like', "%{$search}%");
        });
    }

    // Filter Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Ambil semua status unik untuk dropdown
    $statuses = Modem::select('status')
        ->distinct()
        ->orderBy('status')
        ->pluck('status');

    // Data modem
    $modems = $query->latest()
        ->paginate(20)
        ->withQueryString();

    // Statistik
    $stats = [
        'total'  => Modem::count(),
        'stok'   => Modem::where('status', 'stok_gudang')->count(),
        'aktif'  => Modem::where('status', 'terpasang')->count(),
        'rusak'  => Modem::where('status', 'rusak')->count(),
    ];

    return view('admin.network.modems.index', compact(
        'modems',
        'stats',
        'statuses'
    ));
}

    public function create()
    {
        // Mengambil data pelanggan untuk pilihan drop-down saat modem dipasang
        $customers = \App\Models\Customer::all(); 
        return view('admin.network.modems.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_aset_internal' => 'required|string|max:50|unique:modems,nomor_aset_internal',
            'serial_number'       => 'required|string|max:100|unique:modems,serial_number',
            'merek'               => 'required|string|max:50',
            
            'status'              => 'required|in:stok_gudang,terpasang,rusak',
        ]);

        Modem::create($validated);

        return redirect()->route('admin.network.modems.index')
            ->with('success', 'Aset Modem baru berhasil didaftarkan ke gudang!');
    }

    public function edit(Modem $modem)
    {
        $customers = \App\Models\Customer::all();
        return view('admin.network.modems.edit', compact('modem', 'customers'));
    }

    public function update(Request $request, Modem $modem)
    {
        $validated = $request->validate([
            'nomor_aset_internal' => 'required|string|max:50|unique:modems,nomor_aset_internal,' . $modem->id,
            'serial_number'       => 'required|string|max:100|unique:modems,serial_number,' . $modem->id,
            'merek'               => 'required|string|max:50',
            
            'status'              => 'required|in:stok_gudang,terpasang,rusak',
        ]);


        $modem->update($validated);

        return redirect()->route('admin.network.modems.index')
            ->with('success', 'Data Modem berhasil diperbarui!');
    }

    public function destroy(Modem $modem)
    {
        $modem->delete();
        return redirect()->route('admin.network.modems.index')
            ->with('success', 'Aset Modem berhasil dihapus dari sistem!');
    }
}