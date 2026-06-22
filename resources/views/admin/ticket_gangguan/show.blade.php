@extends('layouts.app')

@section('title', 'Detail Tiket #' . $ticket->id)

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
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <a href="{{ route('admin.ticket_gangguan.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-2 inline-block">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Tiket
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tiket #{{ $ticket->id }}</h1>
                    <p class="text-gray-600 dark:text-gray-400">Laporan Masalah: <span class="font-semibold text-cyan-600 dark:text-cyan-400">{{ $ticket->jenis_gangguan }}</span></p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-sm p-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-cyan-100 dark:bg-cyan-900/40 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-cyan-600 dark:text-cyan-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-gray-900 dark:text-white text-lg">{{ $ticket->customer_name }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $ticket->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <p class="text-xs text-gray-400 dark:text-gray-400 mt-0.5">ID Pelanggan: {{ $ticket->customer_id }}</p>
                                
                                <div class="mt-4 p-4 bg-gray-50 dark:bg-slate-600 rounded-lg border border-gray-100 dark:border-slate-500">
                                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-300 mb-1">Keterangan / Keluhan:</h4>
                                    <p class="text-gray-700 dark:text-gray-200 whitespace-pre-wrap">{{ $ticket->keterangan ?? 'Tidak ada keterangan tambahan.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-sm p-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-4"><i class="fas fa-history mr-2 text-gray-400"></i>Catatan Progress Petugas</h3>
                        <div class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                            Fitur penambahan log perbaikan dapat diletakkan di bagian ini.
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    
                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-sm p-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Informasi Tiket</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs text-gray-400 dark:text-gray-400 uppercase font-semibold">Status ONT Koneksi</label>
                                <div class="mt-1">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $ticket->connection_status === 'online' || $ticket->connection_status === 'connected' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400' }}">
                                        {{ strtoupper($ticket->connection_status) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-xs text-gray-400 dark:text-gray-400 uppercase font-semibold">Tingkat Prioritas</label>
                                <div class="mt-1">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                        @if($ticket->prioritas == 'Tinggi') bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400
                                        @elseif($ticket->prioritas == 'Normal') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400
                                        @else bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400 @endif">
                                        {{ $ticket->prioritas }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-100 dark:border-slate-600 pt-3">
                                <label class="text-xs text-gray-400 dark:text-gray-400 uppercase font-semibold">Terakhir Diperbarui</label>
                                <div class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $ticket->last_update_connection ? $ticket->last_update_connection->format('d M Y H:i') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-sm p-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-4"><i class="fas fa-network-wired mr-2 text-cyan-600"></i>Detail Jaringan</h3>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between py-1.5 border-b border-gray-50 dark:border-slate-600">
                                <span class="text-gray-500 dark:text-gray-400">PPPoE User:</span>
                                <span class="font-mono font-semibold text-gray-900 dark:text-white">{{ $ticket->pppoe_username }}</span>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-gray-50 dark:border-slate-600">
                                <span class="text-gray-500 dark:text-gray-400">IP Address:</span>
                                <span class="font-mono font-semibold text-gray-900 dark:text-white">{{ $ticket->ip_address }}</span>
                            </div>
                            <div class="flex justify-between py-1.5">
                                <span class="text-gray-500 dark:text-gray-400">MAC Address:</span>
                                <span class="font-mono font-semibold text-gray-900 dark:text-white">{{ $ticket->mac_address }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection