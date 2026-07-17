@extends('layouts.technician')

@section('title', 'Pengajuan Libur')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Pengajuan Libur</h1>
        <a href="{{ route('technician.leave.create') }}" class="inline-flex items-center px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm rounded-lg transition">
            <i class="fas fa-plus mr-2"></i> Ajukan
        </a>
    </div>

    @if (session('success'))
    <div class="flex items-center gap-3 rounded-xl bg-green-50 border border-green-100 px-4 py-3 text-sm text-green-700">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="flex items-center gap-3 rounded-xl bg-red-50 border border-red-100 px-4 py-3 text-sm text-red-700">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="divide-y divide-gray-100">
            @forelse($leaves as $leave)
            <div class="p-4 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        @php
                            $iconMap = [
                                'libur' => ['bg-blue-100', 'text-blue-600', 'fa-umbrella-beach'],
                                'izin'  => ['bg-yellow-100', 'text-yellow-600', 'fa-file-alt'],
                                'sakit' => ['bg-red-100', 'text-red-600', 'fa-notes-medical'],
                            ];
                            [$bg, $text, $icon] = $iconMap[$leave->type] ?? ['bg-gray-100', 'text-gray-600', 'fa-calendar'];
                        @endphp
                        <div class="w-12 h-12 {{ $bg }} rounded-full flex items-center justify-center">
                            <i class="fas {{ $icon }} {{ $text }} text-xl"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($leave->leave_date)->translatedFormat('d F Y') }}</p>
                            <p class="text-sm text-gray-500">{{ $leave->reason ?? 'Tidak ada alasan' }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="fas fa-clock mr-1"></i>Diajukan: {{ $leave->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex px-3 py-1 text-xs rounded-full {{ $bg }} {{ $text }}">
                            {{ ucfirst($leave->type) }}
                        </span>
                        <div class="mt-2">
                            @if (!\Carbon\Carbon::parse($leave->leave_date)->isToday())
                            <form action="{{ route('technician.leave.destroy', $leave->id) }}" method="POST" onsubmit="return confirm('Batalkan pengajuan libur ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm">
                                    <i class="fas fa-trash mr-1"></i> Batalkan
                                </button>
                            </form>
                            @else
                            <span class="text-xs text-gray-400">Hari ini</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-calendar-check text-4xl mb-2 text-gray-300"></i>
                <p>Belum ada pengajuan libur</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection