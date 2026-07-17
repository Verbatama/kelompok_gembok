@extends('layouts.app')

@section('title', 'Edit Admin')

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
            <div class="mb-6">
                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-cyan-600 dark:hover:text-cyan-400">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('admin.admins.index') }}" class="hover:text-cyan-600 dark:hover:text-cyan-400">Admins</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900 dark:text-white">Edit</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Admin</h1>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 max-w-3xl">
                <form action="{{ route('admin.admins.update', $admin) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-user mr-2 text-cyan-600 dark:text-cyan-400"></i>Full Name *
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" required class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('name') border-red-500 dark:border-red-500 @enderror">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                       

                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-envelope mr-2 text-cyan-600 dark:text-cyan-400"></i>Email Address
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('email') border-red-500 dark:border-red-500 @enderror">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-lock mr-2 text-cyan-600 dark:text-cyan-400"></i>New Password
                            </label>
                            <input type="password" name="password" id="password" placeholder="Leave blank to keep current password" class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('password') border-red-500 dark:border-red-500 @enderror">
                            @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        

                       

                    <!-- Pengaturan Jam Absen -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-slate-600">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-clock mr-2 text-cyan-600 dark:text-cyan-400"></i>Pengaturan Jam Absen
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Check In Limit -->
                            <div>
                                <label for="check_in_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Batas Jam Check In
                                </label>
                                <input type="time" name="check_in_limit" id="check_in_limit" value="{{ old('check_in_limit', $admin->check_in_limit) }}" class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('check_in_limit') border-red-500 dark:border-red-500 @enderror">
                                @error('check_in_limit')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div></div>

                            

                           

                            <!-- Bonus Check Out Mulai -->
                            <div>
                                <label for="bonus_check_out_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Bonus Check Out Mulai
                                </label>
                                <input type="time" name="bonus_check_out_mulai" id="bonus_check_out_mulai" value="{{ old('bonus_check_out_mulai', $admin->bonus_check_out_mulai) }}" class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('bonus_check_out_mulai') border-red-500 dark:border-red-500 @enderror">
                                @error('bonus_check_out_mulai')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bonus Check Out Selesai -->
                            <div>
                                <label for="bonus_check_out_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Bonus Check Out Selesai
                                </label>
                                <input type="time" name="bonus_check_out_selesai" id="bonus_check_out_selesai" value="{{ old('bonus_check_out_selesai', $admin->bonus_check_out_selesai) }}" class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('bonus_check_out_selesai') border-red-500 dark:border-red-500 @enderror">
                                @error('bonus_check_out_selesai')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.admins.index') }}" class="px-6 py-3 border border-gray-300 dark:border-slate-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-600 transition">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition transform hover:scale-105 shadow-lg">
                            <i class="fas fa-save mr-2"></i>Update Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection