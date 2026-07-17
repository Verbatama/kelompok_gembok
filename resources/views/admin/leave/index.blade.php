@extends('layouts.app')

@section('title', 'Pengajuan Libur')

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
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Pengajuan Libur</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola pengajuan libur, izin, dan sakit Anda</p>
                </div>
                <a href="{{ route('admin.leave.create') }}" class="inline-flex items-center justify-center bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-plus mr-2"></i> Ajukan Libur
                </a>
            </div>

            {{-- Alerts --}}
            @if (session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-xl bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 px-4 py-3 text-sm text-green-700 dark:text-green-400">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div class="mb-6 flex items-center gap-3 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
            @endif

            {{-- Tabel --}}
            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-600">
                        <thead class="bg-gray-50 dark:bg-slate-600">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Alasan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Diajukan</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-700 divide-y divide-gray-200 dark:divide-slate-600">
                            @forelse ($leaves as $leave)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-600/50 transition">
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 whitespace-nowrap">
                                    <i class="fas fa-calendar-day text-gray-400 mr-1"></i>
                                    {{ \Carbon\Carbon::parse($leave->leave_date)->format('d-m-Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($leave->type === 'libur')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400">
                                        <i class="fas fa-umbrella-beach mr-1"></i> Libur
                                    </span>
                                    @elseif ($leave->type === 'izin')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400">
                                        <i class="fas fa-file-alt mr-1"></i> Izin
                                    </span>
                                    @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">
                                        <i class="fas fa-notes-medical mr-1"></i> Sakit
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300 max-w-xs truncate">
                                    {{ $leave->reason ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ $leave->created_at->format('d-m-Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if (!\Carbon\Carbon::parse($leave->leave_date)->isToday())
                                    <form action="{{ route('admin.leave.destroy', $leave->id) }}" method="POST" class="inline" onsubmit="return confirm('Batalkan pengajuan libur ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-lg bg-red-50 text-red-700 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 transition">
                                            <i class="fas fa-trash mr-1"></i> Batalkan
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-gray-400 dark:text-gray-500 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-calendar-times text-4xl mb-4 text-gray-300 dark:text-slate-500"></i>
                                    <p>Belum ada pengajuan libur.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection