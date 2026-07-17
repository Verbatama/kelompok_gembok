@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-900 transition-colors duration-300" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="mb-6">
                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900 dark:text-gray-200">Settings</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">System Settings</h1>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-950/30 border-l-4 border-green-500 p-4 rounded-lg transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 dark:text-green-400 mr-3"></i>
                        <p class="text-green-700 dark:text-green-300">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 border border-transparent dark:border-slate-700 transition-colors duration-300">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-building text-blue-600 dark:text-blue-400 mr-2"></i>Company Information
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name</label>
                                <input type="text" name="company_name" value="{{ $settings['company_name'] ?? '' }}" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                                <textarea name="company_address" rows="3" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ $settings['company_address'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                                <input type="text" name="company_phone" value="{{ $settings['company_phone'] ?? '' }}" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <input type="email" name="company_email" value="{{ $settings['company_email'] ?? '' }}" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 border border-transparent dark:border-slate-700 transition-colors duration-300">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-cogs text-cyan-600 dark:text-cyan-400 mr-2"></i>System Configuration
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Currency Symbol</label>
                                <input type="text" name="currency_symbol" value="{{ $settings['currency_symbol'] ?? 'Rp' }}" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tax Rate (%)</label>
                                <input type="number" name="tax_rate" value="{{ $settings['tax_rate'] ?? '11' }}" step="0.1" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Invoice Footer Note</label>
                                <textarea name="invoice_footer" rows="3" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ $settings['invoice_footer'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 border border-transparent dark:border-slate-700 transition-colors duration-300">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-credit-card text-green-600 dark:text-green-400 mr-2"></i>Payment Gateway (Midtrans)
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Server Key</label>
                                <input type="password" name="midtrans_server_key" value="{{ $settings['midtrans_server_key'] ?? '' }}" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Client Key</label>
                                <input type="text" name="midtrans_client_key" value="{{ $settings['midtrans_client_key'] ?? '' }}" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Environment</label>
                                <select name="midtrans_environment" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    <option value="sandbox" {{ ($settings['midtrans_environment'] ?? '') == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                    <option value="production" {{ ($settings['midtrans_environment'] ?? '') == 'production' ? 'selected' : '' }}>Production</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 border border-transparent dark:border-slate-700 transition-colors duration-300">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fab fa-whatsapp text-green-500 dark:text-green-400 mr-2"></i>WhatsApp Gateway
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">API URL</label>
                                <input type="text" name="whatsapp_api_url" value="{{ $settings['whatsapp_api_url'] ?? '' }}" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">API Key</label>
                                <input type="password" name="whatsapp_api_key" value="{{ $settings['whatsapp_api_key'] ?? '' }}" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sender Number</label>
                                <input type="text" name="whatsapp_sender" value="{{ $settings['whatsapp_sender'] ?? '' }}" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white px-8 py-3 rounded-lg transition transform hover:scale-105 shadow-lg font-bold">
                        <i class="fas fa-save mr-2"></i>Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection