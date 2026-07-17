@extends('layouts.app')

@section('title', 'Laporan Harian')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-900 transition-colors duration-300" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Harian</h1>
                        <p class="text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <form method="GET" class="flex items-center gap-2">
                        <input type="date" name="date" value="{{ $date }}" class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent [color-scheme:light_dark]">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-search mr-1"></i> Lihat
                        </button>
                    </form>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border-l-4 border-green-500 transition-colors duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pendapatan Hari Ini</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-950/40 rounded-full flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-green-600 dark:text-green-400 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border-l-4 border-blue-500 transition-colors duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Invoice Terbayar</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $invoicesPaid }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-950/40 rounded-full flex items-center justify-center">
                                <i class="fas fa-file-invoice-dollar text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border-l-4 border-purple-500 transition-colors duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pelanggan Baru</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $newCustomers }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-950/40 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-plus text-purple-600 dark:text-purple-400 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payments Table -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden transition-colors duration-300 border border-transparent dark:border-slate-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pembayaran Hari Ini</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                            <thead class="bg-gray-50 dark:bg-slate-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Waktu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Invoice</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pelanggan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Paket</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Metode</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                                @forelse($payments as $payment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $payment->paid_date?->format('H:i') ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                        {{ $payment->invoice_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $payment->customer?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $payment->package?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 dark:text-green-400">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-gray-300">
                                            {{ ucfirst($payment->payment_method ?? 'cash') }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-inbox text-4xl mb-4 text-gray-400 dark:text-gray-500"></i>
                                        <p>Belum ada pembayaran hari ini</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex flex-col sm:flex-row justify-between gap-4">
                    <a href="{{ route('admin.reports.daily', ['date' => \Carbon\Carbon::parse($date)->subDay()->toDateString()]) }}" 
                       class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition flex items-center justify-center">
                        <i class="fas fa-chevron-left mr-2 text-sm"></i> Hari Sebelumnya
                    </a>
                    <a href="{{ route('admin.reports.daily', ['date' => \Carbon\Carbon::parse($date)->addDay()->toDateString()]) }}" 
                       class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition flex items-center justify-center">
                        Hari Berikutnya <i class="fas fa-chevron-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection