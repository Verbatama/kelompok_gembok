@extends('layouts.marketer')

@section('content')

<div class="p-6">

    <div class="mb-6 flex items-center justify-between">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Detail Prospect
            </h1>

            <p class="mt-1 text-gray-500">
                Informasi lengkap calon customer.
            </p>
        </div>

        <a href="{{ route('marketer.index') }}" class="rounded-lg bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">
            ← Kembali
        </a>

    </div>


    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">


        {{-- Data Prospect --}}
        <div class="rounded-xl bg-white p-6 shadow lg:col-span-2">

            <h2 class="mb-5 text-xl font-semibold text-gray-800">
                Data Customer
            </h2>


            <div class="space-y-4">


                <div class="flex border-b pb-3">
                    <span class="w-40 font-medium text-gray-600">
                        Nama
                    </span>

                    <span class="text-gray-800">
                        {{ $prospect->name }}
                    </span>
                </div>


                <div class="flex border-b pb-3">
                    <span class="w-40 font-medium text-gray-600">
                        No. HP
                    </span>

                    <span class="text-gray-800">
                        {{ $prospect->phone }}
                    </span>
                </div>


                <div class="flex border-b pb-3">
                    <span class="w-40 font-medium text-gray-600">
                        Email
                    </span>

                    <span class="text-gray-800">
                        {{ $prospect->email ?? '-' }}
                    </span>
                </div>


                <div class="flex border-b pb-3">
                    <span class="w-40 font-medium text-gray-600">
                        Alamat
                    </span>

                    <span class="text-gray-800">
                        {{ $prospect->address }}
                    </span>
                </div>


                <div class="flex border-b pb-3">
                    <span class="w-40 font-medium text-gray-600">
                        Diskon
                    </span>

                    <span class="text-gray-800">
                        {{ $prospect->discount ?? 0 }}%
                    </span>
                </div>


                <div class="flex border-b pb-3">
                    <span class="w-40 font-medium text-gray-600">
                        Dibuat
                    </span>

                    <span class="text-gray-800">
                        {{ $prospect->created_at->format('d M Y H:i') }}
                    </span>
                </div>


            </div>


            <div class="mt-6 flex gap-3">

                <a href="{{route('marketer.edit', $prospect->id)}}" class="rounded-lg bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600">
                    Edit
                </a>


                <form action="{{ route('marketer.destroy', $prospect->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">

                    @csrf
                    @method('DELETE')

                    <button class="rounded-lg bg-red-500 px-4 py-2 text-white hover:bg-red-600">
                        Hapus
                    </button>

                </form>

            </div>


        </div>



        {{-- Foto KTP --}}
        <div class="rounded-xl bg-white p-6 shadow">

            <h2 class="mb-5 text-xl font-semibold text-gray-800">
                Foto KTP
            </h2>


            @if($prospect->ktp_photo)

            <img src="{{ asset('storage/'.$prospect->ktp_photo) }}" alt="Foto KTP" class="w-full rounded-lg border object-cover">

            @else

            <div class="flex h-60 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                Tidak ada foto KTP
            </div>

            @endif


        </div>


    </div>


</div>

@endsection
