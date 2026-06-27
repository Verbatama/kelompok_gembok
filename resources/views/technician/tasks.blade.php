@extends('layouts.technician')

@section('title', 'Tugas Saya')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Tugas Saya</h1>
            <div class="flex space-x-2">
                <form method="GET" action="{{ route('technician.tasks') }}">
                    <select name="status" onchange="this.form.submit()"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                        
                        <option value="">Semua Status</option>

                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                            Konfirmasi
                        </option>

                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>
                            Penjadwalan
                        </option>

                        <option value="installing" {{ request('status') == 'installing' ? 'selected' : '' }}>
                            Pemasangan
                        </option>

                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                            Selesai
                        </option>

                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                            Dibatalkan
                        </option>

                    </select>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="divide-y divide-gray-100">
                @forelse($tasks ?? [] as $task)
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="w-12 h-12 {{ $task->type == 'installation' ? 'bg-blue-100' : 'bg-red-100' }} rounded-full flex items-center justify-center">
                                        <i
                                            class="fas {{ $task->type == 'installation' ? 'fa-plug text-blue-600' : 'fa-wrench text-red-600' }} text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $task->customer_name ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-500">{{ $task->customer_address ?? '' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            <i
                                                class="fas fa-clock mr-1"></i>{{ $task->installation_date->format('d M Y') . " " . $task->installation_time }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="inline-flex px-3 py-1 text-xs rounded-full 
                                                                                                    {{ $task->status == 'completed' ? 'bg-green-100 text-green-700' :
                    ($task->status == 'in_progress' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                    <div class="mt-2">
                                        <a href="{{ route('technician.tasks.show', $task->id) }}"
                                            class="text-orange-600 hover:text-orange-700 text-sm">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-clipboard-check text-4xl mb-2 text-green-500"></i>
                        <p>Tidak ada tugas saat ini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection