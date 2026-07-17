@extends('layouts.app')

@section('title', 'Edit Payroll Teknisi')

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

        <div class="py-8 px-4">
    <div class="max-w-7xl mx-auto">


        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

            <div>
                <h1 class="text-3xl font-bold text-slate-800 dark:text-white">
                    Edit Payroll Teknisi
                </h1>

            </div>


        </div>

        <form action="{{ route('admin.payroll.update',$technicianPayroll->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow border border-slate-200 dark:border-slate-700 overflow-hidden">


                <div class="bg-blue-600 text-white px-6 py-4">
                    <h2 class="text-lg font-semibold">
                        Informasi Payroll Teknisi
                    </h2>
                </div>

                <div class="p-8">

                    <div class="grid lg:grid-cols-3 gap-8">


                        <div class="lg:col-span-2 space-y-5">

                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                                   Nama Teknisi
                                </label>

                                <input readonly value="{{ $technicianPayroll->technician->name }}" class="w-full h-14 rounded-xl bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white px-4">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                                    Periode
                                </label>

                                <input readonly value="{{ \Carbon\Carbon::create()->month($technicianPayroll->bulan)->translatedFormat('F') }} {{ $technicianPayroll->tahun }}" class="w-full h-14 rounded-xl bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white px-4">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                                    Gaji Pokok
                                </label>

                                <input readonly value="Rp {{ number_format($technicianPayroll->gaji_pokok,0,',','.') }}" class="w-full h-14 rounded-xl bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white px-4">
                            </div>

                            <div class="grid md:grid-cols-2 gap-5">

                                <div>
                                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                                        Jumlah Telat
                                    </label>

                                    <input readonly id="jumlah_telat" value="{{ $technicianPayroll->jumlah_telat }}" class="w-full h-14 rounded-xl bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white px-4">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                                        Jumlah Absen
                                    </label>

                                    <input readonly id="jumlah_absen" value="{{ $technicianPayroll->jumlah_absen }}" class="w-full h-14 rounded-xl bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white px-4">
                                </div>

                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                                    Denda Telat / Kejadian
                                </label>

                                <input type="number" name="denda_telat" id="denda_telat" value="{{ old('denda_telat',$technicianPayroll->denda_telat) }}" class="w-full h-14 rounded-xl border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/40 focus:border-blue-500">

                                @error('denda_telat')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-2">{{ $message }}</p>
                                @enderror

                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                                    Denda Tidak Masuk / Kejadian
                                </label>

                                <input type="number" name="denda_absen" id="denda_absen" value="{{ old('denda_absen',$technicianPayroll->denda_absen) }}" class="w-full h-14 rounded-xl border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/40 focus:border-blue-500">

                                @error('denda_absen')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-2">{{ $message }}</p>
                                @enderror

                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                                    Bonus
                                </label>

                                <input type="number" name="bonus" id="bonus" value="{{ old('bonus',$technicianPayroll->bonus) }}" class="w-full h-14 rounded-xl border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/40 focus:border-blue-500">

                                @error('bonus')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-2">{{ $message }}</p>
                                @enderror

                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">
                                    Status
                                </label>

                                <select name="status" class="w-full h-14 rounded-xl border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white px-4 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/40 focus:border-blue-500">

                                    <option value="draft" {{ $technicianPayroll->status=='draft'?'selected':'' }}>
                                        Draft
                                    </option>

                                    <option value="approved" {{ $technicianPayroll->status=='approved'?'selected':'' }}>
                                        Approved
                                    </option>

                                    <option value="paid" {{ $technicianPayroll->status=='paid'?'selected':'' }}>
                                        Paid
                                    </option>

                                </select>

                            </div>

                        </div>


                        <div>

                            <div class="sticky top-5 space-y-5">

                                <div class="rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-6">

                                    <p class="text-sm text-slate-500 dark:text-slate-400">
                                        Total Potongan
                                    </p>

                                    <h3 id="totalPotongan" class="mt-3 text-3xl font-bold text-red-600 dark:text-red-400">

                                        Rp {{ number_format($technicianPayroll->total_potongan,0,',','.') }}

                                    </h3>

                                </div>

                                <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 p-6">

                                    <p class="text-sm text-slate-500 dark:text-slate-400">
                                        Total Diterima
                                    </p>

                                    <h3 id="totalDiterima" class="mt-3 text-3xl font-bold text-emerald-600 dark:text-emerald-400">

                                        Rp {{ number_format($technicianPayroll->total_diterima,0,',','.') }}

                                    </h3>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="border-t dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-8 py-5 flex items-center justify-between">


                    <a href="{{ route('admin.payroll.index') }}" class="px-5 py-3 rounded-xl bg-slate-700 hover:bg-slate-800 text-white transition">
                        Kembali
                    </a>



                    <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 shadow transition">
                        Simpan Perubahan
                    </button>
                </div>

            </div>

    </div>

    </form>

        </div>
    </div>
</div>
@endsection