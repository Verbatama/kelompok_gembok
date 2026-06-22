@extends('layouts.app')

@section('title', 'Tiket Gangguan')

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
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Tiket Gangguan</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola laporan gangguan koneksi dan layanan pelanggan</p>
                </div>
                <a href="{{ route('admin.ticket_gangguan.create') }}" class="bg-cyan-600 text-white px-6 py-3 rounded-lg hover:bg-cyan-700 transition transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>Buat Tiket Baru
                </a>
            </div>

            @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded-r-lg">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('admin.ticket_gangguan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari Pelanggan</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama Pelanggan, ID, Username PPPoE..." class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prioritas</label>
                        <select name="prioritas" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            <option value="">Semua Prioritas</option>
                            <option value="Rendah" {{ request('prioritas') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="Normal" {{ request('prioritas') == 'Normal' ? 'selected' : '' }}>Normal</option>
                            <option value="Tinggi" {{ request('prioritas') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700 transition">
                            <i class="fas fa-search mr-2"></i>Filter Laporan
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-600">
                        <thead class="bg-gray-50 dark:bg-slate-600">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Jenis Gangguan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Prioritas</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status ONT / IP</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Waktu Update</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-700 divide-y divide-gray-200 dark:divide-slate-600">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-600 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-cyan-700 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($ticket->customer_name, 0, 1)) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $ticket->customer_name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $ticket->customer_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $ticket->jenis_gangguan }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $ticket->keterangan ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                                            @if($ticket->prioritas == 'Tinggi') bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400
                                            @elseif($ticket->prioritas == 'Normal') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400
                                            @else bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400 @endif">
                                            {{ $ticket->prioritas }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full w-max
                                                {{ $ticket->connection_status === 'connected' || $ticket->connection_status === 'online' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400' }}">
                                                {{ ucfirst($ticket->connection_status) }}
                                            </span>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $ticket->ip_address }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $ticket->last_update_connection ? $ticket->last_update_connection->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('admin.ticket_gangguan.show', $ticket->id) }}" class="text-cyan-600 hover:text-cyan-800" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form id="delete-ticket-{{ $ticket->id }}" action="{{ route('admin.ticket_gangguan.destroy', $ticket->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete('delete-ticket-{{ $ticket->id }}', '{{ $ticket->customer_name }}')" class="text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-400" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- PERBAIKAN: Diubah jadi colspan="7" biar pas di tengah --}}
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-exclamation-circle text-4xl mb-4 text-gray-300 dark:text-slate-500"></i>
                                        <p>Tidak ada data tiket gangguan saat ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($tickets, 'hasPages') && $tickets->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700">
                    {{ $tickets->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- PERBAIKAN UTAMA: Menambahkan JavaScript confirmDelete --}}
<script>
function confirmDelete(formId, customerName) {
    // Jika kamu menggunakan library SweetAlert2 di app.blade.php
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Tiket gangguan atas nama " + customerName + " akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0891b2', // Warna cyan-600
            cancelButtonColor: '#ef4444', // Warna merah
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    } else {
        // Fallback memakai konfirmasi bawaan browser jika SweetAlert belum aktif
        if (confirm("Apakah Anda yakin ingin menghapus tiket gangguan milik " + customerName + "?")) {
            document.getElementById(formId).submit();
        }
    }
}
</script>
@endsection