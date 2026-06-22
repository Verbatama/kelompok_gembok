@extends('layouts.app')

@section('title', 'Edit Data Pengeluaran')

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
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">Edit Pengeluaran</h1>
            </div>

            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 max-w-2xl">
                <form action="{{ route('admin.expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori *</label>
                        <input type="text" name="category" value="{{ old('category', $expense->category) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('category') border-red-500 @enderror">
                        @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah Nominal (Rp) *</label>
                        <input type="number" name="amount" value="{{ old('amount', (int)$expense->amount) }}" required min="0" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('amount') border-red-500 @enderror">
                        @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Pengeluaran *</label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('expense_date') border-red-500 @enderror">
                        @error('expense_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keterangan / Deskripsi</label>
                        <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $expense->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nota Lama</label>
                        @if($expense->receipt)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $expense->receipt) }}" target="_blank" class="text-xs font-semibold text-cyan-600 dark:text-cyan-400 hover:underline">
                                    <i class="fas fa-image mr-1"></i> Lihat Struk Saat Ini
                                </a>
                            </div>
                        @endif
                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Ganti Struk Baru (Maks 2MB - Kosongkan jika tidak diganti)</label>
                        <input type="file" name="receipt" class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 dark:file:bg-slate-600 dark:file:text-white hover:file:bg-cyan-100">
                        @error('receipt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-slate-600">
                        <a href="{{ route('admin.expenses.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-slate-500 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-600 transition text-sm font-medium">Batal</a>
                        <button type="submit" class="bg-cyan-600 text-white px-6 py-2.5 rounded-lg hover:bg-cyan-700 transition shadow-lg text-sm font-medium">Perbarui Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection