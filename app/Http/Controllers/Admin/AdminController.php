<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource with search and filter.
     */
    public function index(Request $request)
    {
             $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

      

        $admins = $query->latest()->paginate(20);

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
           
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            
            
            // Pengaturan Jam Absen Admin
            'check_in_limit' => 'nullable|date_format:H:i',
            'bonus_check_in_mulai' => 'nullable|date_format:H:i',
            'bonus_check_in_selesai' => 'nullable|date_format:H:i',
            'bonus_check_out_mulai' => 'nullable|date_format:H:i',
            'bonus_check_out_selesai' => 'nullable|date_format:H:i',
            'bonus_check_in_nominal' => 'nullable|numeric|min:0',
            'bonus_check_out_nominal' => 'nullable|numeric|min:0',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_superadmin'] = 0;
       
        

        User::create($validated);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $admin)
    {
        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $admin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $admin->id,
            'password' => 'required|string|min:8',
            
            
            // Pengaturan Jam Absen Admin
            'check_in_limit' => 'nullable|date_format:H:i',
            'bonus_check_out_mulai' => 'nullable|date_format:H:i',
            'bonus_check_out_selesai' => 'nullable|date_format:H:i',
            
        ]);

        
        // Opsional: Tambahkan logika ganti password jika diisi saat update
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $validated['password'] = Hash::make($request->password);
        }

        $admin->update($validated);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $admin)
    {
        $admin->delete();

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin deleted successfully!');
    }
}