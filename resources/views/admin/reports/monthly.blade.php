@extends('layouts.app')

@section('title', 'Laporan Bulanan')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-900 transition-colors duration-300" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="space-y-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Bulanan</h1>
                        <p class="text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}</p>
                    </div>
                    <form method="GET" class="flex items-center gap-2">
                        <select name="month" class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate(2000, $m, 1)->translatedFormat('F') }}
                            </option>
                            @endfor
                        </select>
                        <select name="year" class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-search mr-1"></i> Lihat
                        </button>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border-l-4 border-green-500 transition-colors duration-300">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border-l-4 border-blue-500 transition-colors duration-300">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Invoice Terbayar</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $paidInvoices }} / {{ $totalInvoices }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $totalInvoices > 0 ? round(($paidInvoices / $totalInvoices) * 100, 1) : 0 }}% collection rate</p>
                    </div>
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border-l-4 border-purple-500 transition-colors duration-300">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pelanggan Baru</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $newCustomers }}</p>
                    </div>
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border-l-4 border-red-500 transition-colors duration-300">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pelanggan Churn</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $churnedCustomers }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 border border-transparent dark:border-slate-700 transition-colors duration-300">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pendapatan Harian</h2>
                    <canvas id="dailyRevenueChart" height="100"></canvas>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-transparent dark:border-slate-700 transition-colors duration-300">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Rincian Harian</h2>
                        <a href="{{ route('admin.reports.export', ['type' => 'revenue', 'period' => 'month', 'month' => $month, 'year' => $year]) }}" 
                           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm transition">
                            <i class="fas fa-download mr-1"></i> Export CSV
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                            <thead class="bg-gray-50 dark:bg-slate-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Jumlah Invoice</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pendapatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                                @foreach($dailyRevenue as $day)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $day['date'] }} {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $day['count'] }} invoice
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $day['revenue'] > 0 ? 'text-green-600 dark:text-green-400' : 'text-gray-400 dark:text-gray-500' }}">
                                        Rp {{ number_format($day['revenue'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.reports.daily', ['date' => \Carbon\Carbon::createFromDate($year, $month, $day['date'])->toDateString()]) }}" 
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 inline-flex items-center gap-1">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dailyData = @json($dailyRevenue);
    
    // Deteksi jika HTML mengaktifkan dark mode (.dark di tag html/body)
    const isDarkMode = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark');
    
    // Tentukan warna Chart.js berdasarkan tema aktif
    const textColor = isDarkMode ? '#94a3b8' : '#4b5563';
    const gridColor = isDarkMode ? '#334155' : '#f3f4f6';

    new Chart(document.getElementById('dailyRevenueChart'), {
        type: 'bar',
        data: {
            labels: dailyData.map(d => d.date),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: dailyData.map(d => d.revenue),
                backgroundColor: isDarkMode ? 'rgba(59, 130, 246, 0.4)' : 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { color: gridColor },
                    ticks: { color: textColor }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: {
                        color: textColor,
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection