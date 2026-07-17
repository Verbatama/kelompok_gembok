@extends('layouts.app')

@section('title', 'Vouchers')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-800 transition-colors duration-300" x-data="{ sidebarOpen: false, activeTab: 'online', isDark: localStorage.getItem('theme') === 'dark' }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Voucher System</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Manage voucher sales, pricing, and Mikrotik hotspot</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.vouchers.pricing') }}" class="bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-slate-600 px-4 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600 transition">
                        <i class="fas fa-tags mr-2"></i>Pricing
                    </a>
                    <a href="{{ route('admin.vouchers.generate') }}" class="bg-gradient-to-r from-blue-500 to-cyan-600 text-white px-6 py-2 rounded-lg hover:from-blue-600 hover:to-purple-700 transition shadow-lg">
                        <i class="fas fa-magic mr-2"></i>Generate
                    </a>
                </div>
            </div>

            <!-- Tabs -->
            <div class="mb-6 border-b border-gray-200 dark:border-slate-700">
                <nav class="flex space-x-8">
                    <button @click="activeTab = 'online'" :class="activeTab === 'online' ? 'border-cyan-500 text-cyan-600 dark:text-cyan-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'" class="py-4 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-shopping-cart mr-2"></i>Online Sales
                    </button>
                    <button @click="activeTab = 'hotspot'" :class="activeTab === 'hotspot' ? 'border-cyan-500 text-cyan-600 dark:text-cyan-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'" class="py-4 px-1 border-b-2 font-medium text-sm">
                        <i class="fas fa-wifi mr-2"></i>Hotspot Mikrotik
                        @if($stats['hotspot_unsynced'] > 0)
                        <span class="ml-2 bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 text-xs px-2 py-0.5 rounded-full">{{ $stats['hotspot_unsynced'] }} unsynced</span>
                        @endif
                    </button>
                </nav>
            </div>

            <!-- Online Sales Tab -->
            <div x-show="activeTab === 'online'" x-cloak>
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Sales</p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($stats['total_sales'], 0, ',', '.') }}</h3>
                            </div>
                            <div class="h-12 w-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <i class="fas fa-money-bill-wave text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Vouchers Sold</p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_vouchers']) }}</h3>
                            </div>
                            <div class="h-12 w-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center text-cyan-600 dark:text-cyan-400">
                                <i class="fas fa-ticket-alt text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Active Packages</p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['active_pricing'] }}</h3>
                            </div>
                            <div class="h-12 w-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center text-green-600 dark:text-green-400">
                                <i class="fas fa-box-open text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Purchases -->
                <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-600 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Purchases</h2>
                        <a href="{{ route('admin.vouchers.purchases') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-600">
                            <thead class="bg-gray-50 dark:bg-slate-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Package</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-700 divide-y divide-gray-200 dark:divide-slate-600">
                                @forelse($recent_purchases as $purchase)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-600">
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $purchase->created_at->format('d M Y H:i') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $purchase->phone_number }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $purchase->pricing->package_name ?? 'Unknown' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">Rp {{ number_format($purchase->price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $purchase->status === 'success' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : '' }}
                                                {{ $purchase->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300' : '' }}
                                                {{ $purchase->status === 'failed' ? 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' : '' }}">
                                                {{ ucfirst($purchase->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-shopping-cart text-4xl mb-4 text-gray-300 dark:text-slate-600"></i>
                                            <p>No purchases yet</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Hotspot Tab -->
            <div x-show="activeTab === 'hotspot'" x-cloak>
                <!-- Mikrotik Status -->
                <div class="bg-white dark:bg-slate-700 rounded-xl shadow-sm p-4 mb-6 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        @if($mikrotikConnected)
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-link text-green-600 dark:text-green-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Mikrotik Connected</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Ready to sync</p>
                            </div>
                        @else
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-unlink text-red-600 dark:text-red-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Mikrotik Disconnected</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><a href="{{ route('admin.settings.mikrotik') }}" class="text-cyan-600 dark:text-cyan-400 hover:underline">Configure settings</a></p>
                            </div>
                        @endif
                    </div>
                    <a href="{{ route('admin.vouchers.hotspot.sync') }}" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition">
                        <i class="fas fa-sync mr-2"></i> Sync
                    </a>
                </div>

                <!-- Hotspot Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Hotspot Profiles</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['hotspot_profiles'] }}</p>
                        <a href="{{ route('admin.vouchers.hotspot.profiles') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">Manage →</a>
                    </div>
                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 border-l-4 border-green-500">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Vouchers</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['hotspot_vouchers'] }}</p>
                        <a href="{{ route('admin.vouchers.hotspot') }}" class="text-sm text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">View All →</a>
                    </div>
                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 border-l-4 border-cyan-500">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Unused Vouchers</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['hotspot_unused'] }}</p>
                    </div>
                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Unsynced</p>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['hotspot_unsynced'] }}</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.vouchers.generate') }}?type=hotspot" class="bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl p-5 text-white hover:from-cyan-600 hover:to-blue-600 transition shadow-md">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-plus text-2xl"></i>
                            <div>
                                <p class="font-semibold">Generate Vouchers</p>
                                <p class="text-sm text-white/80">Create & sync to Mikrotik</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('admin.vouchers.hotspot.profiles') }}" class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl p-5 text-white hover:from-purple-600 hover:to-pink-600 transition shadow-md">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-layer-group text-2xl"></i>
                            <div>
                                <p class="font-semibold">Manage Profiles</p>
                                <p class="text-sm text-white/80">Hotspot user profiles</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('admin.vouchers.hotspot') }}" class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl p-5 text-white hover:from-green-600 hover:to-emerald-600 transition shadow-md">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-ticket-alt text-2xl"></i>
                            <div>
                                <p class="font-semibold">View Vouchers</p>
                                <p class="text-sm text-white/80">All hotspot vouchers</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection     