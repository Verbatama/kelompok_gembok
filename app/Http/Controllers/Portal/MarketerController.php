<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProspectCustomer;
class MarketerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        $jumlahProspect = 0;
        $jumlahPasang = 0;
        $jumlahCancel = 0;

        return view('marketer.dashboard', [
            'jumlahProspect' => $jumlahProspect,
            'jumlahPasang' => $jumlahPasang,
            'jumlahCancel' => $jumlahCancel,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('marketer.create_prospect');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create_prospect(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'address' => ['required', 'string'],
            'discount' => ['nullable', 'numeric'],
            'ktp_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('ktp_photo')) {
            $validated['ktp_photo'] = $request->file('ktp_photo')->store('ktp', 'public');
        }

        \App\Models\ProspectCustomer::create($validated);

        return redirect()
            ->route('marketer.create_prospect')
            ->with('success', 'Prospect berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prospect = \App\Models\ProspectCustomer::findOrFail($id);

        return view('marketer.show', compact('prospect'));
    }

    public function daftar_prospect()
    {
        $prospects = \App\Models\ProspectCustomer::latest()->paginate(10);

        return view('marketer.index', compact('prospects'));
    }

  /**
 * Show the form for editing the specified resource.
 */
public function edit(string $id)
{
    $prospect = ProspectCustomer::findOrFail($id);

    return view('marketer.edit', compact('prospect'));
}

/**
 * Update the specified resource in storage.
 */
public function update(Request $request, string $id)
{
    $prospect = ProspectCustomer::findOrFail($id);

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'phone' => ['required', 'string', 'max:20'],
        'email' => ['nullable', 'email'],
        'address' => ['required', 'string'],
        'discount' => ['nullable', 'numeric'],
        'ktp_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
    ]);

    // Jika upload foto baru
    if ($request->hasFile('ktp_photo')) {

        // hapus foto lama
        if ($prospect->ktp_photo) {
            Storage::disk('public')->delete($prospect->ktp_photo);
        }

        $validated['ktp_photo'] = $request
            ->file('ktp_photo')
            ->store('ktp', 'public');
    }

    $prospect->update($validated);

    return redirect()
        ->route('marketer.index')
        ->with('success', 'Data prospect berhasil diperbarui.');
}

/**
 * Remove the specified resource from storage.
 */
public function destroy(string $id)
{
    $prospect = ProspectCustomer::findOrFail($id);

    if ($prospect->ktp_photo) {
        Storage::disk('public')->delete($prospect->ktp_photo);
    }

    $prospect->delete();

    return redirect()
        ->route('marketer.index')
        ->with('success', 'Data prospect berhasil dihapus.');
}
}
