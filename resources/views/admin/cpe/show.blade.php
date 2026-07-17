@extends('layouts.app')

@section('title', 'CPE Details')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-900 transition-colors duration-300" x-data="{ sidebarOpen: false, showWifiModal: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.cpe.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $status['serial_number'] ?? 'Device Details' }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $status['model'] ?? 'Unknown Model' }} - {{ $status['manufacturer'] ?? '' }}</p>
                    </div>
                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ $status['status'] === 'online' ? 'bg-green-100 dark:bg-green-950/40 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-900/50' : 'bg-red-100 dark:bg-red-950/40 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-900/50' }}">
                        <span class="w-2 h-2 rounded-full mr-2 {{ $status['status'] === 'online' ? 'bg-green-500 animate-pulse' : 'bg-red-500' }}"></span>
                        {{ ucfirst($status['status'] ?? 'unknown') }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 transition-colors duration-300">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-cyan-600 dark:text-cyan-400"></i>
                            Device Information
                        </h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Serial Number</p>
                                <p class="font-medium text-gray-900 dark:text-white font-mono">{{ $status['serial_number'] ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Model</p>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $status['model'] ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Manufacturer</p>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $status['manufacturer'] ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Firmware</p>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $status['firmware'] ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">IP Address</p>
                                <p class="font-medium text-gray-900 dark:text-white font-mono">{{ $status['ip_address'] ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">MAC Address</p>
                                <p class="font-medium text-gray-900 dark:text-white font-mono">{{ $status['mac_address'] ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Uptime</p>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ gmdate("H:i:s", $status['uptime'] ?? 0) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Last Inform</p>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $status['last_inform'] ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 transition-colors duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-wifi mr-2 text-blue-600 dark:text-blue-400"></i>
                                WiFi Settings
                            </h2>
                            <button @click="showWifiModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                                <i class="fas fa-edit mr-2"></i>Edit WiFi
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">SSID</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $wifiInfo['ssid'] ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Status</p>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($wifiInfo['enabled'] ?? false) ? 'bg-green-100 dark:bg-green-950/40 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-950/40 text-red-800 dark:text-red-300' }}">
                                    {{ ($wifiInfo['enabled'] ?? false) ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Channel</p>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $wifiInfo['channel'] ?? 'Auto' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Encryption</p>
                                <p class="font-medium text-gray-900 dark:text-gray-200">{{ $wifiInfo['encryption'] ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 transition-colors duration-300">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <button onclick="refreshDevice()" class="w-full flex items-center justify-center bg-cyan-600 text-white px-4 py-3 rounded-lg hover:bg-cyan-700 dark:hover:bg-cyan-500 transition">
                                <i class="fas fa-sync-alt mr-2"></i>Refresh Device
                            </button>
                            <button onclick="rebootDevice()" class="w-full flex items-center justify-center bg-yellow-600 text-white px-4 py-3 rounded-lg hover:bg-yellow-700 transition">
                                <i class="fas fa-redo mr-2"></i>Reboot Device
                            </button>
                            <button onclick="factoryReset()" class="w-full flex items-center justify-center bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Factory Reset
                            </button>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 transition-colors duration-300">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Connection Status</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-700/40 rounded-lg">
                                <span class="text-gray-600 dark:text-gray-400">Status</span>
                                <span class="font-medium {{ $status['status'] === 'online' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ ucfirst($status['status'] ?? 'unknown') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-700/40 rounded-lg">
                                <span class="text-gray-600 dark:text-gray-400">Protocol</span>
                                <span class="font-medium text-gray-900 dark:text-white">TR-069/CWMP</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showWifiModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/60 dark:bg-black/80 transition-opacity" @click="showWifiModal = false"></div>
            <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-xl max-w-md w-full p-6 transition-colors duration-300 border border-transparent dark:border-slate-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Edit WiFi Settings</h3>
                <form id="wifiForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SSID</label>
                        <input type="text" name="ssid" value="{{ $wifiInfo['ssid'] ?? '' }}" class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-cyan-500 dark:focus:ring-cyan-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                        <input type="password" name="password" placeholder="Leave empty to keep current" class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-cyan-500 dark:focus:ring-cyan-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Channel</label>
                        <select name="channel" class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-cyan-500 dark:focus:ring-cyan-400 focus:outline-none">
                            <option value="0">Auto</option>
                            @for($i = 1; $i <= 13; $i++)
                                <option value="{{ $i }}" {{ ($wifiInfo['channel'] ?? 0) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="enabled" id="wifi_enabled" {{ ($wifiInfo['enabled'] ?? false) ? 'checked' : '' }} class="rounded bg-white dark:bg-slate-700 border-gray-300 dark:border-slate-600 text-cyan-600 focus:ring-cyan-500">
                        <label for="wifi_enabled" class="ml-2 text-sm text-gray-700 dark:text-gray-300 select-none">WiFi Enabled</label>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100 dark:border-slate-700">
                        <button type="button" @click="showWifiModal = false" class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 dark:hover:bg-cyan-500 transition">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const deviceId = '{{ $status['device_id'] ?? '' }}';

function refreshDevice() {
    fetch(`/admin/cpe/${encodeURIComponent(deviceId)}/refresh`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(data => { alert(data.message); location.reload(); });
}

function rebootDevice() {
    if (!confirm('Reboot this device?')) return;
    fetch(`/admin/cpe/${encodeURIComponent(deviceId)}/reboot`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(data => alert(data.message));
}

function factoryReset() {
    if (!confirm('WARNING: This will reset the device to factory settings. Continue?')) return;
    fetch(`/admin/cpe/${encodeURIComponent(deviceId)}/factory-reset`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(data => alert(data.message));
}

document.getElementById('wifiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = {
        ssid: formData.get('ssid'),
        password: formData.get('password') || undefined,
        channel: parseInt(formData.get('channel')),
        enabled: formData.has('enabled')
    };
    
    fetch(`/admin/cpe/${encodeURIComponent(deviceId)}/wifi`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(data => {
        alert(data.message);
        if (data.success) location.reload();
    });
});
</script>
@endpush
@endsection