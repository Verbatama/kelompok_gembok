@extends('layouts.app')

@section('title', 'Riwayat Pengajuan Libur')

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
            <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Riwayat Pengajuan Libur</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Daftar pengajuan libur, izin, dan sakit seluruh teknisi</p>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Total: <strong class="text-gray-800 dark:text-white">{{ $leaves->total() }}</strong> pengajuan
                </div>
            </div>

            {{-- Alerts --}}
            @if (session('success'))
            <div class="flex items-center gap-3 rounded-xl bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 px-4 py-3 text-sm text-green-700 dark:text-green-300 mb-6">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div class="flex items-center gap-3 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 px-4 py-3 text-sm text-red-700 dark:text-red-300 mb-6">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
            @endif

            {{-- Filter card --}}
            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-5 mb-6">
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teknisi</label>
                        <select name="technician_id" class="w-full rounded-lg border border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-600 text-gray-900 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <option value="">Semua Teknisi</option>
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}" {{ request('technician_id') == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis</label>
                        <select name="type" class="w-full rounded-lg border border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-600 text-gray-900 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            <option value="">Semua Jenis</option>
                            <option value="libur" {{ request('type') == 'libur' ? 'selected' : '' }}>Libur</option>
                            <option value="izin" {{ request('type') == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ request('type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
                        <input type="date" name="from" value="{{ request('from') }}" class="w-full rounded-lg border border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-600 text-gray-900 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
                        <input type="date" name="to" value="{{ request('to') }}" class="w-full rounded-lg border border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-600 text-gray-900 dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.riwayat-libur.index') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 dark:bg-slate-600 dark:hover:bg-slate-500 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- List card --}}
            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-600 flex items-center justify-between">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                        <i class="fas fa-list-ul mr-1.5 text-cyan-600 dark:text-cyan-400"></i> Daftar Pengajuan
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Halaman {{ $leaves->currentPage() }} dari {{ $leaves->lastPage() }}</p>
                </div>

                <div class="divide-y divide-gray-200 dark:divide-slate-600">
                    @forelse($leaves as $leave)
                        @php
                            $iconMap = [
                                'libur' => ['bg-blue-100 dark:bg-blue-900/40', 'text-blue-600 dark:text-blue-400', 'fa-umbrella-beach'],
                                'izin'  => ['bg-amber-100 dark:bg-amber-900/40', 'text-amber-600 dark:text-amber-400', 'fa-file-alt'],
                                'sakit' => ['bg-red-100 dark:bg-red-900/40', 'text-red-600 dark:text-red-400', 'fa-notes-medical'],
                            ];
                            [$bg, $text, $icon] = $iconMap[$leave->type] ?? ['bg-gray-100 dark:bg-slate-600', 'text-gray-600 dark:text-gray-300', 'fa-calendar'];
                        @endphp
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-slate-600/40 transition">
                            <div class="flex items-center justify-between flex-wrap gap-3">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 {{ $bg }} rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas {{ $icon }} {{ $text }} text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $leave->technician->name ?? 'Teknisi tidak ditemukan' }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            <i class="far fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::parse($leave->leave_date)->translatedFormat('d F Y') }}
                                        </p>
                                        @if($leave->reason)
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $leave->reason }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                            <i class="fas fa-clock mr-1"></i>Diajukan: {{ $leave->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full {{ $bg }} {{ $text }}">
                                        {{ ucfirst($leave->type) }}
                                    </span>
                                    <div class="mt-2">
                                        @if (\Carbon\Carbon::parse($leave->leave_date)->isToday())
                                            <span class="text-xs text-cyan-600 dark:text-cyan-400 font-medium">Hari ini</span>
                                        @elseif (\Carbon\Carbon::parse($leave->leave_date)->isPast())
                                            <span class="text-xs text-gray-400 dark:text-gray-500">Selesai</span>
                                        @else
                                            <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">Akan datang</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-calendar-check text-5xl mb-3 text-gray-300 dark:text-slate-500"></i>
                            <p class="text-sm">Belum ada pengajuan libur</p>
                        </div>
                    @endforelse
                </div>

                @if($leaves->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700">
                    {{ $leaves->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection