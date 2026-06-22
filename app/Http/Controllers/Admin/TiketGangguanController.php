<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller; 
use App\Models\TiketGangguan;
use Illuminate\Http\Request;

class TiketGangguanController extends Controller
{
    public function index()
    {
        $tickets = TiketGangguan::latest()->get();
        return view('admin.ticket_gangguan.index', compact('tickets'));
    }

    public function create()
    {
        return view('admin.ticket_gangguan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'          => 'required|string|max:255',
            'customer_id'            => 'required|string|max:100',
            'jenis_gangguan'         => 'required|in:Loss Merah,Redaman Tinggi,SSID Hilang,SSID Lemah,ONT Mati,PON Blinking,Internet Offline,Internet Lemot,Lainnya',
            'prioritas'              => 'required|in:Rendah,Normal,Tinggi',
            'keterangan'             => 'nullable|string',
            'connection_status'      => 'required|string',
            'pppoe_username'         => 'required|string',
            'ip_address'             => 'required|ip',
            'mac_address'            => 'required|string',
            'last_update_connection' => 'required|date',
        ]);

        TiketGangguan::create($validated);

        return redirect()->route('admin.ticket_gangguan.index')->with('success', 'Tiket gangguan berhasil dibuat!');
    }

    public function show($id)
    {
        $ticket = TiketGangguan::findOrFail($id); 
        return view('admin.ticket_gangguan.show', compact('ticket'));
    }

    public function destroy($id)
    {
        $ticket = TiketGangguan::findOrFail($id);
        $ticket->delete();

        return redirect()->route('admin.ticket_gangguan.index')->with('success', 'Tiket gangguan berhasil dihapus!');
    }
}