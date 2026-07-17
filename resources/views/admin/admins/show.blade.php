@extends('layouts.app')

@section('title', 'Admin Detail')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-800 transition-colors duration-300" x-data="{
        sidebarOpen: false,
        isDark: localStorage.getItem('theme') === 'dark',
        toggleTheme() {
            this.isDark = !this.isDark;
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', this.isDark);
        }
    }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-cyan-600 dark:hover:text-cyan-400">Dashboard</a>
                        <i class="fas fa-chevron-right text-xs"></i>
                        <a href="{{ route('admin.admins.index') }}" class="hover:text-cyan-600 dark:hover:text-cyan-400">Admins</a>
                        <i class="fas fa-chevron-right text-xs"></i>
                        <span class="text-gray-900 dark:text-white">Detail</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Admin Detail</h1>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.admins.edit', $admin) }}" class="px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition transform hover:scale-105 shadow-lg">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.admins.index') }}" class="px-6 py-3 border border-gray-300 dark:border-slate-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-600 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 text-center">
                        <div class="h-24 w-24 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-3xl mx-auto mb-4">
                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $admin->name }}</h2>
                        

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-600 text-left space-y-3">
                            <div class="flex items-center text-sm">
                                <i class="fas fa-phone w-6 text-cyan-600 dark:text-cyan-400"></i>
                                <span class="text-gray-700 dark:text-gray-300">{{ $admin->phone ?? '-' }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-envelope w-6 text-cyan-600 dark:text-cyan-400"></i>
                                <span class="text-gray-700 dark:text-gray-300">{{ $admin->email ?? '-' }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-calendar w-6 text-cyan-600 dark:text-cyan-400"></i>
                                <span class="text-gray-700 dark:text-gray-300">Joined {{ $admin->join_date ? $admin->join_date->format('d M Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Details -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-info-circle mr-2 text-cyan-600 dark:text-cyan-400"></i>General Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Full Name</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $admin->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Username</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $admin->username ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Gaji Pokok</p>
                                <p class="text-sm text-gray-900 dark:text-white">Rp {{ number_format($admin->gaji_pokok ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Status</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $admin->is_active ? 'Active' : 'Inactive' }}</p>
                            </div>
                        </div>

                        @if($admin->notes)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-slate-600">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Notes</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $admin->notes }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-clock mr-2 text-cyan-600 dark:text-cyan-400"></i>Pengaturan Jam Absen
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Batas Jam Check In</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $admin->check_in_limit ?? '-' }}</p>
                            </div>
                            <div></div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Bonus Check In Mulai</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $admin->bonus_check_in_mulai ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Bonus Check In Selesai</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $admin->bonus_check_in_selesai ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Bonus Check Out Mulai</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $admin->bonus_check_out_mulai ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Bonus Check Out Selesai</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $admin->bonus_check_out_selesai ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection