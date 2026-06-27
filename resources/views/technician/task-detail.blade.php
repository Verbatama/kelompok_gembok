@extends('layouts.technician')

@section('title', 'Detail Tugas')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Tugas</h1>
            </div>

            <a href="{{ route('technician.tasks') }}"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>

        {{-- Status --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">

                    <div class="w-14 h-14 rounded-full flex items-center justify-center
                        {{ $task->type == 'installation'
        ? 'bg-blue-100'
        : 'bg-red-100' }}">

                        <i class="fas
                            {{ $task->type == 'installation'
        ? 'fa-plug text-blue-600'
        : 'fa-wrench text-red-600' }}
                            text-xl">
                        </i>
                    </div>

                    <div>
                        <h2 class="font-semibold text-lg text-gray-800">
                            {{ ucfirst($task->type ?? 'Task') }}
                        </h2>

                        <p class="text-sm text-gray-500">
                            Dibuat:
                            {{ $task->created_at?->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>

                <span class="inline-flex px-4 py-2 text-sm rounded-full
                    {{ $task->status == 'completed'
        ? 'bg-green-100 text-green-700'
        : ($task->status == 'in_progress'
            ? 'bg-yellow-100 text-yellow-700'
            : 'bg-gray-100 text-gray-700') }}">
                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                </span>
            </div>
        </div>

        {{-- Informasi Pelanggan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="border-b px-6 py-4">
                <h3 class="font-semibold text-gray-800">
                    <i class="fas fa-user mr-2 text-orange-500"></i>
                    Informasi Pelanggan
                </h3>
            </div>

            <div class="p-6 grid md:grid-cols-2 gap-6">

                <div>
                    <label class="text-sm text-gray-500">Nama Pelanggan</label>
                    <p class="font-medium text-gray-800">
                        {{ $task->customer_name ?? '-' }}
                    </p>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Nomor Telepon</label>
                    <p class="font-medium text-gray-800">
                        {{ $task->customer_phone ?? '-' }}
                    </p>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Alamat</label>
                    <p class="font-medium text-gray-800">
                        {{ $task->customer_address ?? '-' }}
                    </p>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Paket</label>
                    <p class="font-medium text-gray-800">
                        {{ (\App\Models\Package::find($task->package_id)?->name ?? '-') . ': Rp ' . number_format(\App\Models\Package::find($task->package_id)?->price ?? 0, 0, ',', '.') }}
                    </p>
                </div>

            </div>
        </div>

        {{-- Informasi Tugas --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="border-b px-6 py-4">
                <h3 class="font-semibold text-gray-800">
                    <i class="fas fa-clipboard-list mr-2 text-orange-500"></i>
                    Informasi Tugas
                </h3>
            </div>

            <div class="p-6 grid md:grid-cols-2 gap-6">

                <div>
                    <label class="text-sm text-gray-500">Jenis Tugas</label>
                    <p class="font-medium text-gray-800">
                        {{ ucfirst($task->type ?? 'Pemasangan') }}
                    </p>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Jadwal</label>
                    <p class="font-medium text-gray-800">
                        {{ $task->installation_date
        ? \Carbon\Carbon::parse($task->installation_date)->format('d M Y')
        : '-' }}
                    </p>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Teknisi</label>
                    <p class="font-medium text-gray-800">
                        {{ $technician->name ?? '-' }}
                    </p>
                </div>

                <div>
                    <label class="text-sm text-gray-500">Status</label>
                    <p class="font-medium text-gray-800">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </p>
                </div>

            </div>
        </div>

        {{-- Catatan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="border-b px-6 py-4">
                <h3 class="font-semibold text-gray-800">
                    <i class="fas fa-sticky-note mr-2 text-orange-500"></i>
                    Catatan
                </h3>
            </div>

            <div class="p-6">
                <p class="text-gray-700">
                    {{"User: " . $task->customer_notes ?? '-.' }}
                </p>
                <p class="text-gray-700">
                    {{ "Admin: " . $task->admin_notes ?? '-' }}
                </p>
            </div>
        </div>

        {{-- Action --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-wrap gap-3">

                @if($task->status == 'pending')
                    <button class="px-5 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg">
                        <i class="fas fa-play mr-2"></i>
                        Mulai Tugas
                    </button>
                @endif

                @if($task->status == 'in_progress')
                    <button class="px-5 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg">
                        <i class="fas fa-check mr-2"></i>
                        Selesaikan Tugas
                    </button>
                @endif

            </div>
        </div>

    </div>
@endsection