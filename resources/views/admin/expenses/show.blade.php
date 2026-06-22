@extends('layouts.app')

@section('title', 'Detail Pengeluaran')

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
                <a href="{{ route('admin.expenses.index') }}" class="text-cyan-600 dark:text-cyan-400 hover:underline text-sm font-medium">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke List
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">Detail Pengeluaran</h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl">
                {{-- Data Rincian Teks --}}
                <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 space-y-4">
                    <div>
                        <span class="text-xs text-gray-400 dark:text-gray-400 uppercase tracking-wider block">Kategori</span>
                        <span class="text-lg font-bold text-cyan-600 dark:text-cyan-400">{{ $expense->category }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 dark:text-gray-400 uppercase tracking-wider block">Jumlah Nominal</span>
                        <span class="text-xl font-black text-gray-900 dark:text-white">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 dark:text-gray-400 uppercase tracking-wider block">Tanggal Transaksi</span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d F Y') }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 dark:text-gray-400 uppercase tracking-wider block">Keterangan</span>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 whitespace-pre-line">{{ $expense->description ?? 'Tidak ada keterangan tambahan.' }}</p>
                    </div>
                    <div class="pt-4 border-t border-gray-100 dark:border-slate-600 flex space-x-2">
                        <a href="{{ route('admin.expenses.edit', $expense->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm px-4 py-2 rounded-lg transition font-medium">
                            <i class="fas fa-edit mr-1"></i> Edit Data
                        </a>
                    </div>
                </div>

                {{-- Preview Lampiran Struk --}}
                <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 flex flex-col items-center justify-center min-h-[300px]">
                    <span class="text-xs text-gray-400 dark:text-gray-400 uppercase tracking-wider block mb-4 self-start">Lampiran Struk / Nota Fisik</span>
                    @if($expense->receipt)
                        <div class="w-full rounded-lg overflow-hidden border border-gray-200 dark:border-slate-600 mb-3 bg-gray-50 dark:bg-slate-600 flex justify-center p-2">
                            <img src="{{ asset('storage/' . $expense->receipt) }}" alt="Receipt Struk" class="max-h-64 object-contain">
                        </div>
                        <a href="{{ asset('storage/' . $expense->receipt) }}" target="_blank" class="text-sm text-cyan-600 dark:text-cyan-400 hover:underline">
                            <i class="fas fa-external-link-alt mr-1"></i> Buka Gambar di Tab Baru
                        </a>
                    @else
                        <div class="text-center text-gray-400 dark:text-slate-500">
                            <i class="fas fa-receipt text-6xl mb-3"></i>
                            <p class="text-sm">Tidak ada lampiran gambar struk untuk pengeluaran ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection