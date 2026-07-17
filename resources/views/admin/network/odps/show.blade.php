@extends('layouts.app')

@section('title', 'ODP Details')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #odp-map { height: 400px; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-800 transition-colors duration-300" x-data="{ sidebarOpen: false, isDark: localStorage.getItem('theme') === 'dark' }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.network.odps.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $odp->name }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $odp->code }}</p>
                    </div>
                    <a href="{{ route('admin.network.odps.edit', $odp) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-edit mr-2"></i>Edit ODP
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-600 dark:text-blue-400"></i>
                            Basic Information
                        </h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">ODP Name</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $odp->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">ODP Code</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $odp->code }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Status</p>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $odp->status === 'active' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : ($odp->status === 'full' ? 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' : 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300') }}">
                                    {{ ucfirst($odp->status) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Installation Date</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $odp->installation_date ? $odp->installation_date->format('d M Y') : '-' }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Address</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $odp->address ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Latitude</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $odp->latitude ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Longitude</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $odp->longitude ?? '-' }}</p>
                            </div>
                            @if($odp->notes)
                            <div class="col-span-2">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Notes</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $odp->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-plug mr-2 text-blue-600 dark:text-blue-400"></i>
                            Port Capacity
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Port Usage</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $odp->used_ports }} / {{ $odp->capacity }}</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-slate-600 rounded-full h-4">
                                    @php
                                        $percentage = $odp->capacity > 0 ? ($odp->used_ports / $odp->capacity) * 100 : 0;
                                        $color = $percentage >= 90 ? 'bg-red-600' : ($percentage >= 70 ? 'bg-yellow-500' : 'bg-green-500');
                                    @endphp
                                    <div class="{{ $color }} h-4 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ number_format($percentage, 1) }}% utilized</p>
                            </div>
                            <div class="grid grid-cols-3 gap-4 pt-4 border-t border-gray-200 dark:border-slate-600">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $odp->capacity }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Ports</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $odp->used_ports }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Used</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $odp->capacity - $odp->used_ports }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Available</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($odp->latitude && $odp->longitude)
                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-600 dark:text-blue-400"></i>
                            Location
                        </h2>
                        <div id="odp-map" class="rounded-lg overflow-hidden dark:brightness-95 dark:invert dark:hue-rotate-180"></div>
                    </div>
                    @endif
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Quick Stats</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-users text-blue-600 dark:text-blue-400 mr-3"></i>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Connected Customers</span>
                                </div>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $odp->cableRoutes()->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-wifi text-green-600 dark:text-green-400 mr-3"></i>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">ONU Devices</span>
                                </div>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $odp->onuDevices()->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-network-wired text-cyan-600 dark:text-cyan-400 mr-3"></i>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Network Segments</span>
                                </div>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $odp->networkSegmentsStart()->count() + $odp->networkSegmentsEnd()->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('admin.network.odps.edit', $odp) }}" class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-edit mr-2"></i>Edit ODP
                            </a>
                            <a href="{{ route('admin.network.map') }}" class="block w-full text-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                                <i class="fas fa-map mr-2"></i>View on Map
                            </a>
                            <form action="{{ route('admin.network.odps.destroy', $odp) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ODP?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                    <i class="fas fa-trash mr-2"></i>Delete ODP
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($odp->latitude && $odp->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('odp-map').setView([{{ $odp->latitude }}, {{ $odp->longitude }}], 16);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    L.marker([{{ $odp->latitude }}, {{ $odp->longitude }}]).addTo(map)
        .bindPopup('<b>{{ $odp->name }}</b><br>{{ $odp->address }}')
        .openPopup();
</script>
@endif
@endpush