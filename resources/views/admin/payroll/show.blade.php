@extends('layouts.app')

@section('title', 'Detail Payroll Teknisi')

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

<div class="max-w-6xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Detail Payroll Teknisi
            </h1>
        </div>

    </div>

   
    <div class="bg-white dark:bg-slate-700 rounded-xl shadow overflow-hidden">

        <div class="bg-blue-600 text-white px-6 py-4">
            <h2 class="text-lg font-semibold">
                Informasi Payroll
            </h2>
        </div>

        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nama Teknisi</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ $technicianPayroll->technician->name }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Periode</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ \Carbon\Carbon::create()->month($technicianPayroll->bulan)->translatedFormat('F') }}
                        {{ $technicianPayroll->tahun }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Gaji Pokok</p>
                    <p class="font-semibold text-green-600 dark:text-green-400">
                        Rp {{ number_format($technicianPayroll->gaji_pokok,0,',','.') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Bonus</p>
                    <p class="font-semibold text-green-600 dark:text-green-400">
                        Rp {{ number_format($technicianPayroll->bonus,0,',','.') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Telat</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ $technicianPayroll->jumlah_telat }} Kali
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Tidak Masuk</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ $technicianPayroll->jumlah_absen }} Kali
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Denda Telat Per Kejadian</p>
                    <p class="font-semibold text-red-500 dark:text-red-400">
                        Rp {{ number_format($technicianPayroll->denda_telat,0,',','.') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Denda Tidak Masuk Per Kejadian</p>
                    <p class="font-semibold text-red-500 dark:text-red-400">
                        Rp {{ number_format($technicianPayroll->denda_absen,0,',','.') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Potongan</p>
                    <p class="text-xl font-bold text-red-600 dark:text-red-400">
                        Rp {{ number_format($technicianPayroll->total_potongan,0,',','.') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Diterima</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                        Rp {{ number_format($technicianPayroll->total_diterima,0,',','.') }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>

                    @if($technicianPayroll->status == 'draft')
                    <span class="inline-flex px-3 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-400 text-sm font-semibold">
                        Draft
                    </span>

                    @elseif($technicianPayroll->status == 'paid')
                    <span class="inline-flex px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 text-sm font-semibold">
                        Paid
                    </span>

                    @else
                    <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 dark:bg-slate-600 text-gray-700 dark:text-gray-300 text-sm font-semibold">
                        {{ ucfirst($technicianPayroll->status) }}
                    </span>
                    @endif
                </div>

                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Diproses Pada</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ optional($technicianPayroll->processed_at)->format('d M Y H:i') }}
                    </p>
                </div>

            </div>

        </div>

        <!-- Footer -->
        <div class="bg-gray-50 dark:bg-slate-900 px-6 py-4 flex justify-end gap-3 border-t dark:border-slate-700">

            <a href="{{ route('admin.payroll.index') }}" class="px-5 py-2 rounded-lg bg-gray-600 hover:bg-gray-700 text-white transition">
                Kembali
            </a>

            @if($technicianPayroll->status == 'draft')
            <a href="{{ route('admin.payroll.edit', $technicianPayroll->id) }}" class="px-5 py-2 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white transition">
                Edit
            </a>
            @endif

        </div>

    </div>

</div>

        </div>
    </div>
</div>
@endsection