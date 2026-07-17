@extends('layouts.app')

@section('title', 'Package Details')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-200" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.packages.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $package->name }}</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Package Details</p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.packages.edit', $package) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Package Info -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-transparent dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Package Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Name</p>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $package->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Speed</p>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $package->speed }} Mbps</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Price</p>
                                <p class="font-medium text-gray-900 dark:text-gray-200">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tax Rate</p>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $package->tax_rate ?? 0 }}%</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                <span class="px-3 py-1 text-sm rounded-full {{ $package->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Mikrotik Profile</p>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $package->mikrotik_profile ?? '-' }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Description</p>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $package->description ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Card -->
                <div>
                    <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl shadow-md p-6 text-white dark:from-cyan-600 dark:to-blue-700">
                        <h3 class="text-lg font-semibold mb-4">Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span>Total Customers</span>
                                <span class="font-bold">{{ $package->customers->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Active Customers</span>
                                <span class="font-bold">{{ $package->customers->where('status', 'active')->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Monthly Revenue</span>
                                <span class="font-bold">Rp {{ number_format($package->price * $package->customers->where('status', 'active')->count(), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customers using this package -->
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-transparent dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Customers Using This Package</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Phone</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Join Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($package->customers->take(10) as $customer)
                            <tr>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="font-medium text-cyan-600 hover:text-cyan-800 dark:text-cyan-400 dark:hover:text-cyan-300">
                                        {{ $customer->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $customer->phone ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $customer->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ ucfirst($customer->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $customer->join_date ? $customer->join_date->format('d M Y') : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No customers using this package</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection