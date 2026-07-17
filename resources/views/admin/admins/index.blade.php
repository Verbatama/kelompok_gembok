@extends('layouts.app')

@section('title', 'Admins')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-800 transition-colors duration-300" 
     x-data="{ 
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
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Admins</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Manage administrator accounts</p>
                </div>
                <a href="{{ route('admin.admins.create') }}" class="bg-cyan-600 text-white px-6 py-3 rounded-lg hover:bg-cyan-700 transition transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>Add Admin
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/50 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-400 px-4 py-3 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('admin.admins.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, phone, email..." class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="is_active" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-cyan-600 text-white px-4 py-2 rounded-lg hover:bg-cyan-700 transition">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-600">
                        <thead class="bg-gray-50 dark:bg-slate-600">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Admin</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-700 divide-y divide-gray-200 dark:divide-slate-600">
                            @forelse($admins as $admin)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-600 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $admin->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Joined {{ $admin->join_date ? $admin->join_date->format('M Y') : '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $admin->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.admins.show', $admin) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-cyan-600 hover:bg-cyan-50 dark:hover:bg-slate-600 transition" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.admins.edit', $admin) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-yellow-600 dark:text-yellow-500 hover:bg-yellow-50 dark:hover:bg-slate-600 transition" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form id="delete-admin-{{ $admin->id }}" action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="contents">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete('delete-admin-{{ $admin->id }}', '{{ $admin->name }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 dark:text-red-500 hover:bg-red-50 dark:hover:bg-slate-600 transition" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-user-shield text-4xl mb-4 text-gray-300 dark:text-slate-500"></i>
                                        <p>No admins found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($admins->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700">
                    {{ $admins->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
<script>
function confirmDelete(formId, name) {
    if (confirm('Yakin ingin menghapus admin "' + name + '" ?')) {
        document.getElementById(formId).submit();
    }
}
</script>