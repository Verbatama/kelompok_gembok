@extends('layouts.technician')

@section('title', 'Ajukan Libur')

@section('content')
<div class="space-y-6">
    <div class="flex items-center space-x-4">
        <a href="{{ route('technician.leave.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white shadow-sm border border-gray-100 text-gray-600 hover:bg-gray-50">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Ajukan Libur</h1>
    </div>

    @if (session('error'))
    <div class="flex items-center gap-3 rounded-xl bg-red-50 border border-red-100 px-4 py-3 text-sm text-red-700">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="rounded-xl bg-red-50 border border-red-100 px-4 py-3 text-sm text-red-700">
        <p class="font-medium mb-1"><i class="fas fa-exclamation-circle mr-1"></i> Terjadi kesalahan:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <form method="POST" action="{{ route('technician.leave.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="leave_date" value="{{ old('leave_date') }}" min="{{ now()->toDateString() }}" required
                       class="w-full rounded-lg border border-gray-200 bg-white text-gray-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pengajuan</label>
                <select name="type" required class="w-full rounded-lg border border-gray-200 bg-white text-gray-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <option value="" disabled {{ old('type') ? '' : 'selected' }}>Pilih jenis...</option>
                    <option value="libur" {{ old('type') == 'libur' ? 'selected' : '' }}>Libur</option>
                    <option value="izin" {{ old('type') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ old('type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alasan <span class="text-gray-400 font-normal">(opsional)</span></label>
                <textarea name="reason" rows="4" maxlength="500" placeholder="Tuliskan alasan pengajuan..."
                          class="w-full rounded-lg border border-gray-200 bg-white text-gray-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500">{{ old('reason') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-paper-plane mr-1"></i> Ajukan
                </button>
                <a href="{{ route('technician.leave.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-lg text-sm font-medium transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection