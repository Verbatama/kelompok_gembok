@extends('layouts.marketer')

@section('title', 'Tambah Prospect')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">

        <div class="border-b border-slate-200 px-6 py-4">
            <h1 class="text-2xl font-bold text-slate-800">
                Tambah Prospect
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Masukkan data calon pelanggan.
            </p>
        </div>

        <form action="{{ route('marketer.store_prospect') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">

            @csrf

            <div>
                <label class="block text-sm font-medium mb-2">
                    Nama
                </label>

                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-medium mb-2">
                        No. HP
                    </label>

                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                    @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Email
                    </label>

                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div>
                <label class="block text-sm font-medium mb-2">
                    Alamat
                </label>

                <textarea name="address" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('address') }}</textarea>

                @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Diskon
                    </label>

                    <input type="number" name="discount" value="{{ old('discount') }}" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Foto KTP
                    </label>

                    <input type="file" name="ktp_foto" class="block w-full rounded-lg border border-slate-300 p-2">

                    @error('ktp_foto')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end">
                <button type="submit" class="rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700 transition">
                    Simpan Prospect
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
