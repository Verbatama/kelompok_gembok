@extends('layouts.app')

@section('title', 'Ajukan Libur')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-800 transition-colors duration-300" x-data="{
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

            {{-- Header --}}
            <div class="mb-6 flex items-center gap-4">
                <a href="{{ route('admin.leave.index') }}" class="h-10 w-10 flex items-center justify-center rounded-lg bg-white dark:bg-slate-700 shadow-md text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-600 transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Ajukan Libur</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Isi form berikut untuk mengajukan libur, izin, atau sakit</p>
                </div>
            </div>

            {{-- Alerts --}}
            @if (session('error'))
            <div class="mb-6 flex items-center gap-3 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-6 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                <p class="font-medium mb-1"><i class="fas fa-exclamation-circle mr-1"></i> Terjadi kesalahan:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Form --}}
            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 max-w-2xl">
                <form method="POST" action="{{ route('admin.leave.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                        <input type="date" name="leave_date" value="{{ old('leave_date') }}" min="{{ now()->toDateString() }}" required
                               class="w-full rounded-lg border border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-600 text-gray-900 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Pengajuan</label>
                        <select name="type" required class="w-full rounded-lg border border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-600 text-gray-900 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <option value="" disabled {{ old('type') ? '' : 'selected' }}>Pilih jenis...</option>
                            <option value="libur" {{ old('type') == 'libur' ? 'selected' : '' }}>Libur</option>
                            <option value="izin" {{ old('type') == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ old('type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alasan <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <textarea name="reason" rows="4" maxlength="500" placeholder="Tuliskan alasan pengajuan..."
                                  class="w-full rounded-lg border border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-600 text-gray-900 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500">{{ old('reason') }}</textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-paper-plane mr-1"></i> Ajukan
                        </button>
                        <a href="{{ route('admin.leave.index') }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-slate-600 dark:hover:bg-slate-500 text-gray-700 dark:text-gray-200 px-5 py-2 rounded-lg text-sm font-medium transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection