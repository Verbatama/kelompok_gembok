<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ISP Admin</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght=300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50">

<div class="min-h-screen bg-slate-50 flex" x-data="{ sidebarOpen: false }">
    
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transform lg:translate-x-0 lg:static lg:inset-0 transition duration-300 ease-in-out flex flex-col shadow-xl">
        
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800 bg-slate-950">
            <div class="flex items-center space-x-2">
                <div class="h-8 w-8 rounded-lg bg-cyan-500 flex items-center justify-center text-white shadow-lg shadow-cyan-500/30">
                    <i class="fas fa-network-wired text-sm"></i>
                </div>
                <span class="text-lg font-bold text-white tracking-wider">ISP<span class="text-cyan-400">MANAGER</span></span>
            </div>
            <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white lg:hidden">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl bg-gradient-to-r from-cyan-500/20 to-blue-500/10 text-cyan-400 font-medium transition group">
                <i class="fas fa-th-large text-lg text-cyan-400"></i>
                <span>Dashboard</span>
            </a>

            <div x-data="{ open: false }" class="space-y-1">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/60 hover:text-slate-200 transition group">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-server text-lg group-hover:text-cyan-400 transition"></i>
                        <span class="font-medium">Manajemen ISP</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs transition-transform duration-200" :class="open ? 'rotate-90 text-cyan-400' : ''"></i>
                </button>

                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     class="pl-11 pr-2 pb-1 space-y-1" style="display: none;">
                    <a href="#" class="block px-4 py-2 text-sm rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                        Daftar ISP
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition">
                        Tambah ISP
                    </a>
                </div>
            </div>

            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/60 hover:text-slate-200 transition group">
                <i class="fas fa-box text-lg group-hover:text-cyan-400 transition"></i>
                <span class="font-medium">Paket Layanan</span>
            </a>

            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/60 hover:text-slate-200 transition group">
                <i class="fas fa-users-cog text-lg group-hover:text-cyan-400 transition"></i>
                <span class="font-medium">Langganan</span>
            </a>

            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800/60 hover:text-slate-200 transition group">
                <i class="fas fa-user text-lg group-hover:text-cyan-400 transition"></i>
                <span class="font-medium">Profile</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            <a href="#" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition font-medium">
                <i class="fas fa-sign-out-alt text-lg"></i>
                <span>Keluar Aplikasi</span>
            </a>
        </div>
    </aside>

    <div class="flex-1 min-w-0 flex flex-col overflow-hidden">
        
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 z-40">
            <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-700 lg:hidden">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <div class="ml-auto flex items-center space-x-4">
                <div class="flex items-center space-x-3 border-l pl-4 border-slate-200">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-slate-800">Super Admin</p>
                        <p class="text-xs text-slate-500">Administrator</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold shadow-md shadow-cyan-500/20">
                        S
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 bg-slate-50"
              id="dashboard-data"
              data-months='["Jan", "Feb", "Mar", "Apr", "Mei", "Jun"]'
              data-revenue='[12000000, 19000000, 15000000, 25000000, 22000000, 30000000]'
              data-customers='[50, 75, 60, 90, 110, 140]'
              data-packages-labels='["Paket Hemat", "Paket Family", "Paket Gaming", "Paket Bisnis"]'
              data-packages-data='[40, 30, 20, 10]'
              data-invoice-data='[85, 15]'>

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight sm:text-3xl">Ringkasan Sistem</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola data infrastruktur jaringan, distribusi beban bandwidth, dan penagihan real-time.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md hover:-translate-y-0.5 transition duration-300">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Total Pelanggan</p>
                        <p class="text-3xl font-bold text-slate-800 tracking-tight">1.250</p>
                        <span class="inline-flex items-center text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full mt-2">
                            <i class="fas fa-circle text-[6px] mr-1.5 animate-pulse"></i> 1.180 Aktif
                        </span>
                    </div>
                    <div class="h-12 w-12 bg-cyan-50 rounded-xl flex items-center justify-center text-cyan-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md hover:-translate-y-0.5 transition duration-300">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Pendapatan Lunas</p>
                        <p class="text-3xl font-bold text-slate-800 tracking-tight">Rp 45.500.000</p>
                        <span class="inline-flex items-center text-xs font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full mt-2">
                            Bulan ini
                        </span>
                    </div>
                    <div class="h-12 w-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md hover:-translate-y-0.5 transition duration-300">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Pendapatan Tertunda</p>
                        <p class="text-3xl font-bold text-slate-800 tracking-tight">Rp 4.200.000</p>
                        <span class="inline-flex items-center text-xs font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full mt-2">
                            12 Invoice Pending
                        </span>
                    </div>
                    <div class="h-12 w-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between hover:shadow-md hover:-translate-y-0.5 transition duration-300">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1">Paket Aktif</p>
                        <p class="text-3xl font-bold text-slate-800 tracking-tight">4</p>
                        <span class="inline-flex items-center text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full mt-2">
                            Infrastruktur ISP
                        </span>
                    </div>
                    <div class="h-12 w-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                        <i class="fas fa-box-open text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center">
                        <span class="h-2 w-2 rounded-full bg-cyan-500 mr-2"></span>
                        Tren Pendapatan Bulanan
                    </h3>
                    <div style="height: 280px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center">
                        <span class="h-2 w-2 rounded-full bg-blue-500 mr-2"></span>
                        Pertumbuhan Pelanggan Baru
                    </h3>
                    <div style="height: 280px;">
                        <canvas id="customerChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center">
                        <span class="h-2 w-2 rounded-full bg-indigo-500 mr-2"></span>
                        Rasio Distribusi Paket Layanan
                    </h3>
                    <div style="height: 260px;" class="flex items-center justify-center">
                        <canvas id="packageChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 mr-2"></span>
                        Metrik Status Invoice Masuk
                    </h3>
                    <div style="height: 260px;" class="flex items-center justify-center">
                        <canvas id="invoiceChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800">Invoice Terbaru</h3>
                        <a href="#" class="text-xs font-semibold text-cyan-600 hover:text-cyan-700 transition">Lihat Semua</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/80 transition">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">Ahmad Subarjo</p>
                                <p class="text-xs text-slate-400 mt-0.5">INV-2026-001</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-slate-800">Rp 250.000</p>
                                <span class="inline-block text-[11px] px-2 py-0.5 rounded-full mt-1 font-medium bg-emerald-50 text-emerald-700">
                                    Lunas
                                </span>
                            </div>
                        </div>
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/80 transition">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">Budi Setiadi</p>
                                <p class="text-xs text-slate-400 mt-0.5">INV-2026-002</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-slate-800">Rp 450.000</p>
                                <span class="inline-block text-[11px] px-2 py-0.5 rounded-full mt-1 font-medium bg-amber-50 text-amber-700">
                                    Pending
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800">Pelanggan Baru</h3>
                        <a href="#" class="text-xs font-semibold text-cyan-600 hover:text-cyan-700 transition">Lihat Semua</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/80 transition">
                            <div class="flex items-center space-x-3">
                                <div class="h-9 w-9 rounded-xl bg-slate-100 flex items-center justify-center font-bold text-slate-600 text-sm">
                                    C
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Citra Lestari</p>
                                    <p class="text-xs text-slate-400 mt-0.5">081234567890</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-medium text-slate-700 bg-slate-100 px-2 py-1 rounded-lg inline-block">Paket Family</p>
                                <span class="block text-[11px] mt-1 text-emerald-500 font-medium">
                                    Aktif
                                </span>
                            </div>
                        </div>
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/80 transition">
                            <div class="flex items-center space-x-3">
                                <div class="h-9 w-9 rounded-xl bg-slate-100 flex items-center justify-center font-bold text-slate-600 text-sm">
                                    D
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Dedi Wijaya</p>
                                    <p class="text-xs text-slate-400 mt-0.5">087765432109</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-medium text-slate-700 bg-slate-100 px-2 py-1 rounded-lg inline-block">Paket Gaming</p>
                                <span class="block text-[11px] mt-1 text-emerald-500 font-medium">
                                    Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
    Chart.defaults.font.family = "'Inter', 'sans-serif'";
    Chart.defaults.font.size = 11;
    Chart.defaults.color = '#94A3B8';

    const dataContainer = document.getElementById('dashboard-data');

    // Parsing data dari Atribut Data HTML Statis
    const chartMonths = JSON.parse(dataContainer.getAttribute('data-months')) || [];
    const revenueData = JSON.parse(dataContainer.getAttribute('data-revenue')) || [];
    const customerData = JSON.parse(dataContainer.getAttribute('data-customers')) || [];
    const packageLabels = JSON.parse(dataContainer.getAttribute('data-packages-labels')) || [];
    const packageData = JSON.parse(dataContainer.getAttribute('data-packages-data')) || [];
    const invoiceData = JSON.parse(dataContainer.getAttribute('data-invoice-data')) || [];

    // Line Chart: Revenue
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueGradient = revenueCtx.createLinearGradient(0, 0, 0, 250);
    revenueGradient.addColorStop(0, 'rgba(6, 182, 212, 0.2)');
    revenueGradient.addColorStop(1, 'rgba(6, 182, 212, 0.0)');

    if(revenueCtx && chartMonths.length > 0) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: chartMonths,
                datasets: [{
                    label: 'Pendapatan',
                    data: revenueData,
                    borderColor: '#06b6d4',
                    backgroundColor: revenueGradient,
                    borderWidth: 2.5,
                    tension: 0.35,
                    fill: true,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#06b6d4'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#F1F5F9' }, ticks: { callback: (v) => 'Rp ' + (v >= 1000000 ? (v/1000000).toFixed(1) + 'Jt' : v) } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Bar Chart: Customers
    const customerCtx = document.getElementById('customerChart');
    if(customerCtx && chartMonths.length > 0) {
        new Chart(customerCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: chartMonths,
                datasets: [{
                    data: customerData,
                    backgroundColor: '#3b82f6',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#F1F5F9' }, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Doughnut Charts configs 
    const doughnutOptions = {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '75%',
        plugins: { legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true, pointStyle: 'circle' } } }
    };

    const packageCtx = document.getElementById('packageChart');
    if(packageCtx && packageLabels.length > 0) {
        new Chart(packageCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: packageLabels,
                datasets: [{
                    data: packageData,
                    backgroundColor: ['#06b6d4', '#3b82f6', '#10b981', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: doughnutOptions
        });
    }

    const invoiceCtx = document.getElementById('invoiceChart');
    if(invoiceCtx && invoiceData.length > 0) {
        new Chart(invoiceCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Lunas', 'Pending'],
                datasets: [{
                    data: invoiceData,
                    backgroundColor: ['#10b981', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: doughnutOptions
        });
    }
</script>
</body>
</html>