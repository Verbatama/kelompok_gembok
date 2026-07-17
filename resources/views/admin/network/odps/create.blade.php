@extends('layouts.app')

@section('title', 'Create ODP')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-800 transition-colors duration-300" x-data="{ sidebarOpen: false, isDark: localStorage.getItem('theme') === 'dark' }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="mb-6">
                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('admin.network.odps.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">ODP Management</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900 dark:text-white">Create</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Add New ODP</h1>
            </div>

            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 max-w-3xl">
                <form action="{{ route('admin.network.odps.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-tag mr-2 text-blue-600 dark:text-blue-400"></i>ODP Name *
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 dark:border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-barcode mr-2 text-blue-600 dark:text-blue-400"></i>ODP Code *
                            </label>
                            <input type="text" name="code" id="code" value="{{ old('code') }}" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('code') border-red-500 dark:border-red-500 @enderror">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="capacity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-network-wired mr-2 text-blue-600 dark:text-blue-400"></i>Capacity (Ports) *
                            </label>
                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity', 8) }}" min="1" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('capacity') border-red-500 dark:border-red-500 @enderror">
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="location_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-map-pin mr-2 text-blue-600 dark:text-blue-400"></i>Location Name
                            </label>
                            <input type="text" name="location_name" id="location_name" value="{{ old('location_name') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('location_name') border-red-500 dark:border-red-500 @enderror">
                            @error('location_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Latitude
                            </label>
                            <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('latitude') border-red-500 dark:border-red-500 @enderror">
                            @error('latitude')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Longitude
                            </label>
                            <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('longitude') border-red-500 dark:border-red-500 @enderror">
                            @error('longitude')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-toggle-on mr-2 text-blue-600 dark:text-blue-400"></i>Status *
                            </label>
                            <select name="status" id="status" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 dark:border-red-500 @enderror">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }} class="dark:bg-slate-600">Active</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }} class="dark:bg-slate-600">Maintenance</option>
                                <option value="full" {{ old('status') == 'full' ? 'selected' : '' }} class="dark:bg-slate-600">Full</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.network.odps.index') }}" class="px-6 py-3 border border-gray-300 dark:border-slate-600 rounded-lg text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 transition">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transition transform hover:scale-105 shadow-lg">
                            <i class="fas fa-save mr-2"></i>Save ODP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection