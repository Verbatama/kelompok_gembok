@extends('layouts.app')

@section('title', 'Payroll Teknisi')

@section('content')

@include('admin.partials.sidebar')

<div class="min-h-screen bg-gray-100 dark:bg-slate-900 p-6 md:ml-64">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Payroll Teknisi
            </h1>

            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Daftar riwayat penggajian teknisi.
            </p>
        </div>

        <a href="{{ route('admin.payroll.create') }}" class="px-5 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition">
            + Proses Payroll
        </a>

    </div>


    @if(session('success'))

    <div class="mb-5 rounded-lg bg-green-100 border border-green-300 text-green-700 px-4 py-3">
        {{ session('success') }}
    </div>

    @endif



    <div class="bg-white dark:bg-slate-800 rounded-xl shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-100 dark:bg-slate-700">

                    <tr>

                        <th class="px-5 py-3 text-left text-sm font-semibold">
                            Teknisi
                        </th>

                        <th class="px-5 py-3 text-center text-sm font-semibold">
                            Periode
                        </th>

                        <th class="px-5 py-3 text-right text-sm font-semibold">
                            Gaji Pokok
                        </th>

                        <th class="px-5 py-3 text-center text-sm font-semibold">
                            Telat
                        </th>

                        <th class="px-5 py-3 text-center text-sm font-semibold">
                            Absen
                        </th>

                        <th class="px-5 py-3 text-right text-sm font-semibold">
                            Bonus
                        </th>

                        <th class="px-5 py-3 text-right text-sm font-semibold">
                            Potongan
                        </th>

                        <th class="px-5 py-3 text-right text-sm font-semibold">
                            Total
                        </th>

                        <th class="px-5 py-3 text-center text-sm font-semibold">
                            Status
                        </th>

                        <th class="px-5 py-3 text-center text-sm font-semibold">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">


                    @forelse($payrolls as $payroll)

                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">


                        <td class="px-5 py-4">
                            <div class="font-semibold text-gray-800 dark:text-white">
                                {{ $payroll->technician->name }}
                            </div>
                        </td>


                        <td class="px-5 py-4 text-center">
                            {{ \Carbon\Carbon::create()->month($payroll->bulan)->translatedFormat('F') }} {{ $payroll->tahun }}
                        </td>


                        <td class="px-5 py-4 text-right">
                            Rp {{ number_format($payroll->gaji_pokok, 0, ',', '.') }}
                        </td>
                        {{-- <td class="px-5 py-4 text-center">
                            {{ 30 - ($payroll->jumlah_telat + $payroll->jumlah_absen) }}
                        </td> --}}

                        <td class="px-5 py-4 text-center">
                            {{ $payroll->jumlah_telat }}
                        </td>


                        <td class="px-5 py-4 text-center">
                            {{ $payroll->jumlah_absen }}
                        </td>


                        <td class="px-5 py-4 text-right text-green-600">
                            Rp {{ number_format($payroll->bonus, 0, ',', '.') }}
                        </td>


                        <td class="px-5 py-4 text-right text-red-600">
                            Rp {{ number_format($payroll->total_potongan, 0, ',', '.') }}
                        </td>


                        <td class="px-5 py-4 text-right font-bold text-indigo-600">
                            Rp {{ number_format($payroll->total_diterima, 0, ',', '.') }}
                        </td>


                        <td class="px-5 py-4 text-center">

                            @if($payroll->status == 'draft')

                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">
                                Draft
                            </span>

                            @elseif($payroll->status == 'approved')

                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs">
                                Approved
                            </span>

                            @else

                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">
                                Paid
                            </span>

                            @endif

                        </td>


                        <td class="px-5 py-4">

                            <div class="flex justify-center gap-2">

                                <a href="{{ route('admin.payroll.show', $payroll) }}" class="px-3 py-1 rounded bg-sky-500 text-white text-sm">
                                    Detail
                                </a>


                                <a href="{{ route('admin.payroll.edit', $payroll) }}" class="px-3 py-1 rounded bg-amber-500 text-white text-sm">
                                    Edit
                                </a>


                                <form action="{{ route('admin.payroll.destroy', $payroll) }}" method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button onclick="return confirm('Hapus payroll ini?')" class="px-3 py-1 rounded bg-red-600 text-white text-sm">
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </td>


                    </tr>


                    @empty


                    <tr>

                        <td colspan="10" class="py-10 text-center text-gray-500">

                            Belum ada data payroll.

                        </td>

                    </tr>


                    @endforelse


                </tbody>

            </table>

        </div>


        <div class="p-5 border-t dark:border-slate-700">

            {{ $payrolls->links() }}

        </div>


    </div>


</div>


@endsection
