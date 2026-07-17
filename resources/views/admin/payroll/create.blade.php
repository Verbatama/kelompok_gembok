@extends('layouts.app')

@section('title', 'Proses Payroll')

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

    <div class="max-w-3xl mx-auto">

        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Proses Payroll Teknisi
            </h1>

            <p class="mt-2 text-gray-500 dark:text-gray-400">
                Pilih teknisi dan periode payroll. Data absensi dan gaji pokok akan dihitung otomatis.
            </p>
        </div>

        @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-300 bg-red-100 p-4">
            <ul class="list-disc ml-5 text-red-700">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.payroll.store') }}" method="POST">

            @csrf

            <div class="bg-white dark:bg-slate-700 rounded-xl shadow p-6 space-y-6">

                <div>

                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">
                        Teknisi
                    </label>

                    <select name="technician_id" required
                        class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">

                        <option value="">-- Pilih Teknisi --</option>

                        @foreach($technicians as $technician)
                        <option value="{{ $technician->id }}" {{ old('technician_id')==$technician->id ? 'selected' : ''
                            }}>
                            {{ $technician->name }}
                        </option>
                        @endforeach

                    </select>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>

                        <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">
                            Bulan
                        </label>

                        <select name="bulan" required
                            class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">

                            @for($i=1;$i<=12;$i++) <option value="{{ $i }}" {{ old('bulan', now()->
                                month)==$i?'selected':'' }}>
                                {{ DateTime::createFromFormat('!m',$i)->format('F') }}
                                </option>
                                @endfor

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">
                            Tahun
                        </label>

                        <input type="number" name="tahun" value="{{ old('tahun', now()->year) }}" required
                            class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">

                    </div>

                </div>

                <hr class="dark:border-slate-600">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>

                        <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">
                            Denda Telat (per kejadian)
                        </label>

                        <input type="number" name="denda_telat" value="{{ old('denda_telat',10000) }}" min="0" required
                            class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">

                    </div>

                    <div>

                        <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">
                            Denda Absen (per kejadian)
                        </label>

                        <input type="number" name="denda_absen" value="{{ old('denda_absen',50000) }}" min="0" required
                            class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">

                    </div>

                </div>

                <div>

                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">
                        Bonus
                    </label>

                    <input type="number" name="bonus" value="{{ old('bonus',0) }}" min="0" required
                        class="w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white">

                </div>

                <div class="flex justify-end gap-3 pt-4">

                    <a href="{{ route('admin.payroll.index') }}"
                        class="px-5 py-2 rounded-lg bg-gray-300 hover:bg-gray-400 dark:bg-slate-600 dark:hover:bg-slate-500 dark:text-white">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold">

                        Simpan Payroll

                    </button>

                </div>

            </div>

        </form>

    </div>

        </div>
    </div>
</div>
@endsection