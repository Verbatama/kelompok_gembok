@extends('layouts.app')

@section('title', 'Riwayat Absensi Teknisi')

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
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Riwayat Absensi Teknisi</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Pantau kehadiran dan keterlambatan seluruh teknisi</p>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-5 flex items-center space-x-4">
                    <div class="h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center">
                        <i class="fas fa-user-check text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tepat Waktu</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTepat }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-5 flex items-center space-x-4">
                    <div class="h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/40 flex items-center justify-center">
                        <i class="fas fa-user-clock text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Terlambat</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTerlambat }}</p>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-5 mb-6">
                <form method="GET" action="{{ route('admin.attendance.history') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                        <input type="date" name="date" value="{{ request('date', now()->toDateString()) }}" class="w-full rounded-lg border border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-600 text-gray-900 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keterlambatan</label>
                        <select name="is_late" class="w-full rounded-lg border border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-600 text-gray-900 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <option value="">Semua</option>
                            <option value="1" {{ request('is_late') == '1' ? 'selected' : '' }}>Terlambat</option>
                            <option value="0" {{ request('is_late') == '0' ? 'selected' : '' }}>Tepat Waktu</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Teknisi</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama teknisi..." class="w-full rounded-lg border border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-600 text-gray-900 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-search mr-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.attendance.history') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 dark:bg-slate-600 dark:hover:bg-slate-500 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-600">
                        <thead class="bg-gray-50 dark:bg-slate-600">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Nama Teknisi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Keterlambatan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Lokasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-700 divide-y divide-gray-200 dark:divide-slate-600">
                            @forelse ($histories as $history)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-600/50 transition">
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $histories->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($history->technician->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $history->technician->name ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">

                                    @if($history->status === 'absent')<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-black text-white">
                                        <i class="fas fa-sign-out-alt mr-1"></i> Absent
                                    </span @elseif ($history->status === 'check-in')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400">
                                        <i class="fas fa-sign-in-alt mr-1"></i> Check-In
                                    </span>
                                    @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-400">
                                        <i class="fas fa-sign-out-alt mr-1"></i> Check-Out
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 whitespace-nowrap">
                                    <i class="fas fa-clock text-gray-400 mr-1"></i>
                                    {{ $history->created_at->format('d-m-Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($history->status === 'check-in')
                                    @if ($history->is_late)
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Terlambat
                                    </span>
                                    @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400">
                                        <i class="fas fa-check-circle mr-1"></i> Tepat Waktu
                                    </span>
                                    @endif
                                    @else
                                    <span class="text-gray-400 dark:text-gray-500 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($history->latitude && $history->longitude)
                                    <a href="https://maps.google.com/?q={{ $history->latitude }},{{ $history->longitude }}" target="_blank" class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-lg bg-cyan-50 text-cyan-700 hover:bg-cyan-100 dark:bg-cyan-900/30 dark:text-cyan-400 dark:hover:bg-cyan-900/50 transition">
                                        <i class="fas fa-map-marker-alt mr-1"></i> Lihat Peta
                                    </a>
                                    @else
                                    <span class="text-gray-400 dark:text-gray-500 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-calendar-times text-4xl mb-4 text-gray-300 dark:text-slate-500"></i>
                                    <p>Tidak ada data absensi untuk filter yang dipilih.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($histories->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700">
                    {{ $histories->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
