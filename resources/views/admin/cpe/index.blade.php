@extends('layouts.app')

@section('title', 'CPE Management')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-900 transition-colors duration-300" x-data="{ sidebarOpen: false, selectedDevices: [] }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">CPE Management</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Remote manage customer modems via GenieACS</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($connected ?? false)
                            <span class="flex items-center px-4 py-2 bg-green-100 dark:bg-green-950/40 text-green-800 dark:text-green-300 rounded-lg border border-green-200 dark:border-green-900/60">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                GenieACS Connected
                            </span>
                        @bars
                            <span class="flex items-center px-4 py-2 bg-red-100 dark:bg-red-950/40 text-red-800 dark:text-red-300 rounded-lg border border-red-200 dark:border-red-900/60">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                Disconnected
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            @if(!($connected ?? false))
                <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/50 rounded-xl p-6 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-4"></i>
                        <div>
                            <h3 class="text-lg font-bold text-red-800 dark:text-red-400">Connection Failed</h3>
                            <p class="text-red-700 dark:text-red-300 mt-1">{{ $error ?? 'Unable to connect to GenieACS. Please check your configuration.' }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 border-l-4 border-cyan-500 transition-colors duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Devices</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</p>
                            </div>
                            <div class="h-14 w-14 bg-cyan-100 dark:bg-cyan-950/40 rounded-full flex items-center justify-center">
                                <i class="fas fa-router text-cyan-600 dark:text-cyan-400 text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 border-l-4 border-green-500 transition-colors duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Online</p>
                                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['online'] ?? 0 }}</p>
                            </div>
                            <div class="h-14 w-14 bg-green-100 dark:bg-green-950/40 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 border-l-4 border-red-500 transition-colors duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Offline</p>
                                <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['offline'] ?? 0 }}</p>
                            </div>
                            <div class="h-14 w-14 bg-red-100 dark:bg-red-950/40 rounded-full flex items-center justify-center">
                                <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-4 mb-6 transition-colors duration-300">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <form method="GET" class="flex items-center space-x-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by device ID..." class="px-4 py-2 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-cyan-500 dark:focus:ring-cyan-400 focus:outline-none w-64">
                            <button type="submit" class="bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700 dark:hover:bg-cyan-500 transition">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                        
                        <div class="flex items-center space-x-2">
                            <button onclick="bulkRefresh()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition" x-show="selectedDevices.length > 0">
                                <i class="fas fa-sync-alt mr-2"></i>Bulk Refresh
                            </button>
                            <button onclick="bulkReboot()" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition" x-show="selectedDevices.length > 0">
                                <i class="fas fa-redo mr-2"></i>Bulk Reboot
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md overflow-hidden transition-colors duration-300">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-200 dark:border-slate-700">
                                <tr>
                                    <th class="px-4 py-3 text-left">
                                        <input type="checkbox" @change="selectedDevices = $event.target.checked ? {{ json_encode($devices->pluck('id')) }} : []" class="rounded bg-white dark:bg-slate-700 border-gray-300 dark:border-slate-600 text-cyan-600 focus:ring-cyan-500">
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Device</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Model</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">IP Address</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Last Seen</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                                @forelse($devices ?? [] as $device)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                                        <td class="px-4 py-3">
                                            <input type="checkbox" :value="'{{ $device['id'] }}'" x-model="selectedDevices" class="rounded bg-white dark:bg-slate-700 border-gray-300 dark:border-slate-600 text-cyan-600 focus:ring-cyan-500">
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $device['serial'] ?? 'Unknown' }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ Str::limit($device['id'], 30) }}</p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>
                                                <p class="text-gray-900 dark:text-gray-200">{{ $device['model'] ?? 'Unknown' }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $device['manufacturer'] ?? '' }}</p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 font-mono text-sm text-gray-600 dark:text-gray-300">{{ $device['ip_address'] ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            @if($device['status'] === 'online')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-950/40 text-green-800 dark:text-green-300">
                                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                                    Online
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-950/40 text-red-800 dark:text-red-300">
                                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                                    Offline
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $device['last_inform'] }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center space-x-3 text-sm">
                                                <a href="{{ route('admin.cpe.show', urlencode($device['id'])) }}" class="text-cyan-600 dark:text-cyan-400 hover:text-cyan-800 dark:hover:text-cyan-300 transition" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button onclick="refreshDevice('{{ $device['id'] }}')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition" title="Refresh">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                                <button onclick="rebootDevice('{{ $device['id'] }}')" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-300 transition" title="Reboot">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                            <div class="flex flex-col items-center justify-center space-y-2">
                                                <i class="fas fa-router text-gray-300 dark:text-slate-600 text-5xl mb-2"></i>
                                                <span>No devices found</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function refreshDevice(deviceId) {
    fetch(`/admin/cpe/${encodeURIComponent(deviceId)}/refresh`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    })
    .catch(error => alert('Error: ' + error.message));
}

function rebootDevice(deviceId) {
    if (!confirm('Are you sure you want to reboot this device?')) return;
    
    fetch(`/admin/cpe/${encodeURIComponent(deviceId)}/reboot`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    })
    .catch(error => alert('Error: ' + error.message));
}

function bulkRefresh() {
    const devices = Alpine.raw(Alpine.$data(document.querySelector('[x-data]')).selectedDevices);
    if (devices.length === 0) return;
    
    fetch('/admin/cpe/bulk-refresh', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ device_ids: devices })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
}

function bulkReboot() {
    if (!confirm('Are you sure you want to reboot selected devices?')) return;
    
    const devices = Alpine.raw(Alpine.$data(document.querySelector('[x-data]')).selectedDevices);
    if (devices.length === 0) return;
    
    fetch('/admin/cpe/bulk-reboot', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ device_ids: devices })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
}
</script>
@endpush
@endsection