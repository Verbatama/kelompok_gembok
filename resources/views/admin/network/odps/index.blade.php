@extends('layouts.app')

@section('title', 'ODP Management')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-800 transition-colors duration-300" x-data="{ sidebarOpen: false, isDark: localStorage.getItem('theme') === 'dark' }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">ODP Management</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Manage Optical Distribution Points</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.network.map') }}" class="bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-slate-600 px-4 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-600 transition">
                        <i class="fas fa-map-marked-alt mr-2"></i>Map View
                    </a>
                    <a href="{{ route('admin.network.odps.create') }}" class="bg-gradient-to-r from-blue-500 to-cyan-600 text-white px-6 py-2 rounded-lg hover:from-blue-600 hover:to-purple-700 transition shadow-lg">
                        <i class="fas fa-plus mr-2"></i>Add ODP
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('admin.network.odps.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, code, location..." class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-600">
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ODP Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Capacity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-700 divide-y divide-gray-200 dark:divide-slate-600">
                            @forelse($odps as $odp)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-600 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                                <i class="fas fa-network-wired"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $odp->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $odp->code }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $odp->location_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-900 dark:text-white mr-2">{{ $odp->capacity - $odp->available_ports }}/{{ $odp->capacity }}</span>
                                            <div class="w-24 h-2 bg-gray-200 dark:bg-slate-600 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-500" style="width: {{ ($odp->capacity > 0) ? (($odp->capacity - $odp->available_ports) / $odp->capacity) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $odp->status === 'active' ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : '' }}
                                            {{ $odp->status === 'maintenance' ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300' : '' }}
                                            {{ $odp->status === 'full' ? 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' : '' }}">
                                            {{ ucfirst($odp->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('admin.network.odps.edit', $odp) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.network.odps.show', $odp) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form id="delete-odp-{{ $odp->id }}" action="{{ route('admin.network.odps.destroy', $odp) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete('delete-odp-{{ $odp->id }}', '{{ $odp->name }}')" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-network-wired text-4xl mb-4 text-gray-300 dark:text-slate-600"></i>
                                        <p>No ODPs found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 dark:bg-slate-800 px-6 py-4 border-t border-gray-100 dark:border-slate-700">
                    {{ $odps->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection