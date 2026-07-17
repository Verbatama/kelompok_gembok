@extends('layouts.app')

@section('title', 'RADIUS Server')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-900 transition-colors duration-300" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">RADIUS Server Management</h1>
                    <p class="text-gray-600 dark:text-gray-400">Kelola autentikasi PPPoE via FreeRADIUS</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.radius.users') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-users mr-2"></i>Users
                    </a>
                    <a href="{{ route('admin.radius.groups') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-layer-group mr-2"></i>Groups
                    </a>
                </div>
            </div>

            @if(!$enabled)
            <div class="bg-yellow-50 dark:bg-yellow-950/20 border-l-4 border-yellow-400 dark:border-yellow-600 p-4 rounded-lg transition-colors">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-yellow-400 dark:text-yellow-500 mr-3 mt-1"></i>
                    <div>
                        <h3 class="text-yellow-800 dark:text-yellow-400 font-medium">RADIUS Tidak Aktif</h3>
                        <p class="text-yellow-700 dark:text-yellow-300 text-sm mt-1">
                            Aktifkan RADIUS dengan mengatur <code class="bg-yellow-100 dark:bg-yellow-900/50 px-1 rounded text-yellow-900 dark:text-yellow-200">RADIUS_ENABLED=true</code> di file .env
                        </p>
                    </div>
                </div>
            </div>
            @else

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow p-6 transition-colors duration-300">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <i class="fas fa-wifi text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Online Users</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ count($onlineUsers) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow overflow-hidden transition-colors duration-300">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                        <i class="fas fa-signal text-green-500 mr-2"></i>Online Users
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                        <thead class="bg-gray-50 dark:bg-slate-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">NAS IP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Framed IP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Start Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Download</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Upload</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                            @forelse($onlineUsers as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">{{ $user->username }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->nasipaddress }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600 dark:text-gray-300">{{ $user->framedipaddress }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->acctstarttime }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 dark:text-blue-400">{{ number_format($user->acctinputoctets / 1048576, 2) }} MB</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-purple-600 dark:text-purple-400">{{ number_format($user->acctoutputoctets / 1048576, 2) }} MB</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <form action="{{ route('admin.radius.disconnect') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="username" value="{{ $user->username }}">
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition" onclick="return confirm('Disconnect user ini?')">
                                            <i class="fas fa-plug mr-1"></i> Disconnect
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <i class="fas fa-inbox text-2xl text-gray-300 dark:text-slate-600"></i>
                                        <span>Tidak ada user online</span>
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
@endsection