@extends('layouts.app')

@section('title', 'Buat Tiket Gangguan')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-800 transition-colors duration-300" 
     x-data="{ 
         sidebarOpen: false,
         isDark: localStorage.getItem('theme') === 'dark',
         toggleTheme() {
             this.isDark = !this.isDark;
             localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
             document.documentElement.classList.toggle('dark', this.isDark);
         }
     }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="mb-6">
                <a href="{{ route('admin.ticket_gangguan.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-2 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Tiket
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Tiket Gangguan Baru</h1>
            </div>

            <div class="max-w-3xl">
                <form action="{{ route('admin.ticket_gangguan.store') }}" method="POST" class="bg-white dark:bg-slate-700 rounded-xl shadow-sm p-6 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Pelanggan *</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            @error('customer_name')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ID Pelanggan *</label>
                            <input type="text" name="customer_id" value="{{ old('customer_id') }}" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            @error('customer_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">PPPoE Username *</label>
                            <input type="text" name="pppoe_username" value="{{ old('pppoe_username') }}" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            @error('pppoe_username')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">IP Address *</label>
                            <input type="text" name="ip_address" value="{{ old('ip_address') }}" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            @error('ip_address')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">MAC Address *</label>
                            <input type="text" name="mac_address" value="{{ old('mac_address') }}" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            @error('mac_address')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Gangguan *</label>
                            <select name="jenis_gangguan" required class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                <option value="">Pilih Gangguan</option>
                                @foreach(['Loss Merah', 'Redaman Tinggi', 'SSID Hilang', 'SSID Lemah', 'ONT Mati', 'PON Blinking', 'Internet Offline', 'Internet Lemot', 'Lainnya'] as $g)
                                    <option value="{{ $g }}" {{ old('jenis_gangguan') == $g ? 'selected' : '' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                            @error('jenis_gangguan')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prioritas *</label>
                            <select name="prioritas" required class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                <option value="Rendah" {{ old('prioritas') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="Normal" {{ old('prioritas', 'Normal') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                <option value="Tinggi" {{ old('prioritas') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                            </select>
                            @error('prioritas')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Koneksi *</label>
                            <select name="connection_status" required class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                <option value="online" {{ old('connection_status') == 'online' ? 'selected' : '' }}>Online / Connected</option>
                                <option value="offline" {{ old('connection_status') == 'offline' ? 'selected' : '' }}>Offline / Disconnected</option>
                            </select>
                            @error('connection_status')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Waktu Terakhir Update Koneksi *</label>
                        <input type="datetime-local" name="last_update_connection" value="{{ old('last_update_connection', now()->format('Y-m-d\TH:i')) }}" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        @error('last_update_connection')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keterangan Tambahan (Opsional)</label>
                        <textarea name="keterangan" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                            placeholder="Tulis detail keluhan pelanggan disini...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-slate-600">
                        <a href="{{ route('admin.ticket_gangguan.index') }}" class="px-4 py-2 border border-gray-300 dark:border-slate-500 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600 transition">Batal</a>
                        <button type="submit" class="bg-cyan-600 text-white px-5 py-2 rounded-lg hover:bg-cyan-700 transition shadow-md">
                            <i class="fas fa-save mr-2"></i>Simpan Tiket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection