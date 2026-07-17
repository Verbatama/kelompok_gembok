@extends('layouts.app')

@section('title', 'WhatsApp Gateway')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-900 transition-colors duration-300" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">WhatsApp Gateway</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola notifikasi WhatsApp</p>
                </div>
                <div>
                    <span id="connection-status" class="px-4 py-2 rounded-lg text-sm font-medium {{ $connected ? 'bg-green-100 dark:bg-green-950/40 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-900/40' : 'bg-red-100 dark:bg-red-950/40 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-900/40' }}">
                        <i class="fas fa-{{ $connected ? 'check-circle' : 'times-circle' }} mr-2"></i>
                        {{ $connected ? 'Connected' : 'Disconnected' }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md overflow-hidden transition-colors duration-300">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                        <h5 class="text-white font-bold text-lg">
                            <i class="fab fa-whatsapp mr-2"></i>Kirim Pesan
                        </h5>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.whatsapp.send') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor Telepon</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 bg-gray-100 dark:bg-slate-700 border border-r-0 border-gray-300 dark:border-slate-600 rounded-l-lg text-gray-600 dark:text-gray-400">+62</span>
                                    <input type="text" name="phone" class="flex-1 px-4 py-2 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-r-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:outline-none placeholder-gray-400" placeholder="8123456789" required>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Contoh: 81234567890</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pesan</label>
                                <textarea name="message" rows="4" class="w-full px-4 py-2 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:outline-none placeholder-gray-400" placeholder="Tulis pesan..." required></textarea>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gunakan *text* untuk bold, _text_ untuk italic</p>
                            </div>
                            <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition {{ !$connected ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$connected ? 'disabled' : '' }}>
                                <i class="fab fa-whatsapp mr-2"></i>Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md overflow-hidden transition-colors duration-300">
                    <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 px-6 py-4">
                        <h5 class="text-white font-bold text-lg">
                            <i class="fas fa-bolt mr-2"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('admin.whatsapp.test') }}" class="w-full text-left px-4 py-3 border border-green-200 dark:border-green-900/40 bg-green-50 dark:bg-green-950/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-950/40 transition block">
                            <div class="flex items-center">
                                <i class="fas fa-vial text-green-500 mr-3 w-5"></i>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Test Notifikasi</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Kirim test ke pelanggan tertentu</p>
                                </div>
                            </div>
                        </a>
                        
                        <button onclick="sendBulkInvoice()" class="w-full text-left px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                            <div class="flex items-center">
                                <i class="fas fa-file-invoice text-blue-500 mr-3 w-5"></i>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Kirim Notifikasi Invoice Baru</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Kirim ke semua invoice yang belum dikirim</p>
                                </div>
                            </div>
                        </button>
                        
                        <button onclick="sendBulkReminder()" class="w-full text-left px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                            <div class="flex items-center">
                                <i class="fas fa-bell text-yellow-500 mr-3 w-5"></i>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Kirim Pengingat Pembayaran</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Kirim ke invoice yang belum dibayar</p>
                                </div>
                            </div>
                        </button>
                        
                        <button onclick="sendSuspensionNotice()" class="w-full text-left px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                            <div class="flex items-center">
                                <i class="fas fa-ban text-red-500 mr-3 w-5"></i>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Kirim Notifikasi Penangguhan</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Kirim ke pelanggan yang ditangguhkan</p>
                                </div>
                            </div>
                        </button>
                        
                        <button onclick="checkStatus()" class="w-full text-left px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                            <div class="flex items-center">
                                <i class="fas fa-sync text-green-500 mr-3 w-5"></i>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Cek Status Koneksi</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Periksa koneksi WhatsApp Gateway</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md overflow-hidden mb-6 transition-colors duration-300">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center">
                    <h5 class="font-bold text-gray-900 dark:text-white"><i class="fas fa-history mr-2 text-cyan-600 dark:text-cyan-400"></i>Riwayat Notifikasi</h5>
                    <a href="{{ route('admin.whatsapp.logs') }}" class="text-cyan-600 dark:text-cyan-400 hover:text-cyan-800 dark:hover:text-cyan-300 text-sm">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        <div class="text-center p-3 bg-blue-50 dark:bg-blue-950/20 rounded-lg border border-transparent dark:border-blue-900/30">
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($stats['total'] ?? 0) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                        </div>
                        <div class="text-center p-3 bg-green-50 dark:bg-green-950/20 rounded-lg border border-transparent dark:border-green-900/30">
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($stats['sent'] ?? 0) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Terkirim</p>
                        </div>
                        <div class="text-center p-3 bg-red-50 dark:bg-red-950/20 rounded-lg border border-transparent dark:border-red-900/30">
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($stats['failed'] ?? 0) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Gagal</p>
                        </div>
                        <div class="text-center p-3 bg-cyan-50 dark:bg-cyan-950/20 rounded-lg border border-transparent dark:border-cyan-900/30">
                            <p class="text-2xl font-bold text-cyan-600 dark:text-cyan-400">{{ number_format($stats['today'] ?? 0) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Hari Ini</p>
                        </div>
                    </div>
                    
                    @if(isset($recentLogs) && $recentLogs->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-slate-700/50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Waktu</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Penerima</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Tipe</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                                @foreach($recentLogs as $log)
                                <tr class="hover:bg-gray-50/70 dark:hover:bg-slate-700/30 transition">
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                        {{ $log->created_at->format('d/m H:i') }}
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $log->customer->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $log->phone }}</div>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        @php
                                            $typeColors = [
                                                'invoice' => 'bg-blue-100 dark:bg-blue-950/50 text-blue-800 dark:text-blue-300',
                                                'reminder' => 'bg-yellow-100 dark:bg-yellow-950/50 text-yellow-800 dark:text-yellow-300',
                                                'suspension' => 'bg-red-100 dark:bg-red-950/50 text-red-800 dark:text-red-300',
                                                'voucher' => 'bg-purple-100 dark:bg-purple-950/50 text-purple-800 dark:text-purple-300',
                                            ];
                                        @endphp
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $typeColors[$log->type] ?? 'bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-gray-300' }}">
                                            {{ ucfirst($log->type) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        @if($log->status == 'sent')
                                            <span class="text-green-600 dark:text-green-400 font-medium"><i class="fas fa-check-circle mr-1"></i> Terkirim</span>
                                        @else
                                            <span class="text-red-600 dark:text-red-400 font-medium"><i class="fas fa-times-circle mr-1"></i> Gagal</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-inbox text-3xl mb-2 text-gray-400 dark:text-gray-600"></i>
                        <p class="text-sm">Belum ada riwayat notifikasi</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md overflow-hidden mb-6 transition-colors duration-300">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                    <h5 class="font-bold text-gray-900 dark:text-white"><i class="fas fa-file-alt mr-2 text-cyan-600 dark:text-cyan-400"></i>Template Pesan</h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 dark:bg-slate-700/40 border border-transparent dark:border-slate-700 rounded-lg p-4">
                            <h6 class="font-semibold text-gray-900 dark:text-white mb-2">Invoice Notification</h6>
                            <pre class="text-xs text-gray-600 dark:text-gray-300 whitespace-pre-wrap font-mono">Halo *{nama}*,

Tagihan internet Anda telah terbit:

📋 Invoice: {invoice}
📦 Paket: {paket}
💰 Total: Rp {amount}
📅 Jatuh Tempo: {due_date}

Terima kasih,
*{app_name}*</pre>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-700/40 border border-transparent dark:border-slate-700 rounded-lg p-4">
                            <h6 class="font-semibold text-gray-900 dark:text-white mb-2">Payment Confirmation</h6>
                            <pre class="text-xs text-gray-600 dark:text-gray-300 whitespace-pre-wrap font-mono">Halo *{nama}*,

✅ Pembayaran diterima!

📋 Invoice: {invoice}
💰 Jumlah: Rp {amount}
📅 Tanggal: {paid_date}

Terima kasih,
*{app_name}*</pre>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-700/40 border border-transparent dark:border-slate-700 rounded-lg p-4">
                            <h6 class="font-semibold text-gray-900 dark:text-white mb-2">Payment Reminder</h6>
                            <pre class="text-xs text-gray-600 dark:text-gray-300 whitespace-pre-wrap font-mono">⚠️ *Pengingat Pembayaran*

Halo *{nama}*,

Tagihan belum dibayar:

📋 Invoice: {invoice}
💰 Total: Rp {amount}
📅 Jatuh Tempo: {due_date}

*{app_name}*</pre>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md overflow-hidden transition-colors duration-300">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                    <h5 class="font-bold text-gray-900 dark:text-white"><i class="fas fa-cog mr-2 text-cyan-600 dark:text-cyan-400"></i>Konfigurasi</h5>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                                <tr>
                                    <td class="py-3 font-medium text-gray-700 dark:text-gray-300 w-48">API URL</td>
                                    <td class="py-3"><code class="bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded text-sm text-gray-900 dark:text-white font-mono break-all">{{ config('services.whatsapp.api_url') ?: 'Not configured' }}</code></td>
                                </tr>
                                <tr>
                                    <td class="py-3 font-medium text-gray-700 dark:text-gray-300">Sender Number</td>
                                    <td class="py-3"><code class="bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded text-sm text-gray-900 dark:text-white font-mono">{{ config('services.whatsapp.sender') ?: 'Not configured' }}</code></td>
                                </tr>
                                <tr>
                                    <td class="py-3 font-medium text-gray-700 dark:text-gray-300">Status</td>
                                    <td class="py-3">
                                        @if($connected)
                                        <span class="px-3 py-1 bg-green-100 dark:bg-green-950/40 text-green-800 dark:text-green-300 rounded-full text-sm font-medium border border-transparent dark:border-green-900/30">Connected</span>
                                        @else
                                        <span class="px-3 py-1 bg-red-100 dark:bg-red-950/40 text-red-800 dark:text-red-300 rounded-full text-sm font-medium border border-transparent dark:border-red-900/30">Disconnected</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/40 rounded-lg p-4">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            <i class="fas fa-info-circle mr-2"></i>
                            Konfigurasi WhatsApp Gateway dapat diubah di file <code class="bg-blue-100 dark:bg-slate-700 px-1.5 py-0.5 rounded text-gray-900 dark:text-white font-mono">.env</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function checkStatus() {
    showLoading('Memeriksa koneksi...');
    fetch('{{ route("admin.whatsapp.status") }}')
        .then(response => response.json())
        .then(data => {
            Swal.close();
            const badge = document.getElementById('connection-status');
            if (data.connected) {
                badge.className = 'px-4 py-2 rounded-lg text-sm font-medium bg-green-100 dark:bg-green-950/40 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-900/40';
                badge.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Connected';
                showToast('success', 'WhatsApp Gateway terhubung!');
            } else {
                badge.className = 'px-4 py-2 rounded-lg text-sm font-medium bg-red-100 dark:bg-red-950/40 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-900/40';
                badge.innerHTML = '<i class="fas fa-times-circle mr-2"></i> Disconnected';
                showToast('error', 'WhatsApp Gateway tidak terhubung!');
            }
        })
        .catch(error => {
            Swal.close();
            showError('Error: ' + error.message);
        });
}

function sendBulkInvoice() {
    confirmAction('Kirim notifikasi invoice ke semua pelanggan?', () => {
        showToast('info', 'Fitur ini akan mengirim notifikasi ke semua invoice baru');
    });
}

function sendBulkReminder() {
    confirmAction('Kirim pengingat pembayaran ke semua invoice yang belum dibayar?', () => {
        showToast('info', 'Fitur ini akan mengirim pengingat ke semua invoice yang belum dibayar');
    });
}

function sendSuspensionNotice() {
    confirmAction('Kirim notifikasi penangguhan ke pelanggan yang ditangguhkan?', () => {
        showToast('info', 'Fitur ini akan mengirim notifikasi penangguhan');
    });
}
</script>
@endpush
@endsection