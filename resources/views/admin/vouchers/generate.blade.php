@extends('layouts.app')

@section('title', 'Generate Vouchers')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-900 transition-colors duration-300" x-data="{ 
    sidebarOpen: false, 
    voucherType: '{{ request('type', 'online') }}',
    printVouchers: false
}">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="mb-6">
                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-cyan-400">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('admin.vouchers.index') }}" class="hover:text-blue-600 dark:hover:text-cyan-400">Vouchers</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900 dark:text-white">Generate</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Generate Vouchers</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Create vouchers for online sales or Mikrotik hotspot</p>
                
                <div class="mt-4 bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900 rounded-lg p-4 flex items-start">
                    <i class="fas fa-info-circle text-blue-500 dark:text-cyan-400 mt-0.5 mr-3 text-lg"></i>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-400">Panduan Fitur (Simpan Database vs Sync Mikrotik)</h4>
                        <ul class="text-sm text-blue-700 dark:text-blue-300 mt-1 list-disc list-inside space-y-1">
                            <li><strong>Simpan Database (Tanpa Centang Sync):</strong> Voucher hanya dibuat dan disimpan di sistem lokal Gembok. Belum bisa digunakan login di jaringan Mikrotik.</li>
                            <li><strong>Sync ke Mikrotik (Dicentang):</strong> Data voucher akan langsung dikirim (Push) ke router Mikrotik sehingga pelanggan bisa langsung menggunakannya. <em>(Proses ini mungkin membutuhkan waktu sedikit lebih lama tergantung jumlah voucher yang dibuat).</em></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 max-w-2xl border border-transparent dark:border-slate-700 transition-colors duration-300">
                <form action="{{ route('admin.vouchers.generate.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                <i class="fas fa-tags mr-2 text-blue-600 dark:text-cyan-400"></i>Voucher Type
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition"
                                       :class="voucherType === 'online' ? 'border-cyan-500 bg-cyan-50 dark:bg-cyan-950/20 dark:border-cyan-500' : 'border-gray-200 dark:border-slate-700 hover:border-gray-300 dark:hover:border-slate-600'">
                                    <input type="radio" name="type" value="online" x-model="voucherType" class="sr-only">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-950/50 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-shopping-cart text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">Online Sales</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">For web purchases</p>
                                        </div>
                                    </div>
                                </label>
                                <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition"
                                       :class="voucherType === 'hotspot' ? 'border-cyan-500 bg-cyan-50 dark:bg-cyan-950/20 dark:border-cyan-500' : 'border-gray-200 dark:border-slate-700 hover:border-gray-300 dark:hover:border-slate-600'">
                                    <input type="radio" name="type" value="hotspot" x-model="voucherType" class="sr-only">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 dark:bg-green-950/50 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-wifi text-green-600 dark:text-green-400"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">Hotspot Mikrotik</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Sync to router</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div x-show="voucherType === 'online'" x-cloak>
                            <label for="pricing_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-box mr-2 text-blue-600 dark:text-cyan-400"></i>Voucher Package
                            </label>
                            <select name="pricing_id" id="pricing_id" :required="voucherType === 'online'"
                                class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                <option value="" class="dark:bg-slate-900">Select Package</option>
                                @foreach($pricings as $pricing)
                                    <option value="{{ $pricing->id }}" class="dark:bg-slate-900">
                                        {{ $pricing->package_name }} - Rp {{ number_format($pricing->customer_price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="voucherType === 'hotspot'" x-cloak class="space-y-4">
                            <div>
                                <label for="profile_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-layer-group mr-2 text-green-600 dark:text-green-400"></i>Hotspot Profile
                                </label>
                                <select name="profile_id" id="profile_id" :required="voucherType === 'hotspot'"
                                    class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    <option value="" class="dark:bg-slate-900">Select Profile</option>
                                    @foreach($hotspotProfiles as $profile)
                                        <option value="{{ $profile->id }}" class="dark:bg-slate-900">
                                            {{ $profile->name }} 
                                            @if($profile->rate_limit) ({{ $profile->rate_limit }}) @endif
                                            @if($profile->price > 0) - Rp {{ number_format($profile->price, 0, ',', '.') }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                @if($hotspotProfiles->isEmpty())
                                    <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        No profiles found. <a href="{{ route('admin.vouchers.hotspot.profiles.create') }}" class="underline">Create one</a>
                                    </p>
                                @endif
                            </div>

                            <div>
                                <label for="limit_uptime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-clock mr-2 text-green-600 dark:text-green-400"></i>Limit Uptime (Optional)
                                </label>
                                <input type="text" name="limit_uptime" id="limit_uptime" placeholder="e.g., 1h, 3h, 1d"
                                    class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent placeholder-gray-400 dark:placeholder-gray-500">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Override profile session timeout</p>
                            </div>
                        </div>

                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-calculator mr-2 text-blue-600 dark:text-cyan-400"></i>Quantity
                            </label>
                            <input type="number" name="quantity" id="quantity" value="10" min="1" max="1000" required
                                class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Maximum 1000 vouchers per generation</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="prefix" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-font mr-2 text-blue-600 dark:text-cyan-400"></i>Code Prefix
                                </label>
                                <input type="text" name="prefix" id="prefix" value="VC" maxlength="10"
                                    class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent uppercase">
                            </div>
                            <div>
                                <label for="length" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-ruler mr-2 text-blue-600 dark:text-cyan-400"></i>Code Length
                                </label>
                                <input type="number" name="length" id="length" value="6" min="4" max="12"
                                    class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                        </div>

                        <div x-show="voucherType === 'hotspot'" x-cloak class="space-y-3 p-4 bg-gray-50 dark:bg-slate-900/50 rounded-lg border border-transparent dark:border-slate-700/60">
                            <label class="flex items-center cursor-pointer select-none">
                                <input type="checkbox" name="sync_to_mikrotik" value="1" checked
                                    class="rounded border-gray-300 dark:border-slate-700 text-cyan-600 focus:ring-cyan-500 dark:bg-slate-900 mr-3">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Sync to Mikrotik immediately</span>
                            </label>
                            <label class="flex items-center cursor-pointer select-none">
                                <input type="checkbox" name="print_vouchers" value="1" x-model="printVouchers"
                                    class="rounded border-gray-300 dark:border-slate-700 text-cyan-600 focus:ring-cyan-500 dark:bg-slate-900 mr-3">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Open print page after generation</span>
                            </label>
                        </div>

                        <div class="pt-4 flex items-center justify-end space-x-4 border-t dark:border-slate-700">
                            <a href="{{ route('admin.vouchers.index') }}" class="px-6 py-3 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-lg hover:from-cyan-600 hover:to-blue-700 transition transform hover:scale-105 shadow-lg">
                                <i class="fas fa-magic mr-2"></i>Generate Vouchers
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection