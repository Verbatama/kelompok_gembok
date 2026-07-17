@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">

    <div class="bg-white shadow-md rounded-xl p-6">

        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Edit Prospect Customer
        </h1>

        @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-red-700">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif


        <form action="{{ route('marketer.update', $prospect->id) }}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')


            <div class="grid gap-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nama
                    </label>

                    <input type="text" name="name" value="{{ old('name', $prospect->name) }}" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>


                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nomor Telepon
                    </label>

                    <input type="text" name="phone" value="{{ old('phone', $prospect->phone) }}" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>


                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Email
                    </label>

                    <input type="email" name="email" value="{{ old('email', $prospect->email) }}" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>


                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Alamat
                    </label>

                    <textarea name="address" rows="3" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">{{ old('address', $prospect->address) }}</textarea>
                </div>


                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Discount
                    </label>

                    <input type="number" name="discount" value="{{ old('discount', $prospect->discount) }}" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>


                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Foto KTP
                    </label>


                    @if($prospect->ktp_photo)
                    <div class="mb-3">
                        <img src="{{ asset('storage/'.$prospect->ktp_photo) }}" class="w-40 rounded-lg border">
                    </div>
                    @endif


                    <input type="file" name="ktp_photo" accept="image/*" class="block w-full text-sm text-gray-600
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-lg file:border-0
                                  file:bg-blue-600 file:text-white
                                  hover:file:bg-blue-700">

                    <p class="text-xs text-gray-500 mt-1">
                        Kosongkan jika tidak ingin mengganti foto.
                    </p>
                </div>


                <div class="flex justify-end gap-3 mt-4">

                    <a href="{{ route('marketer.index') }}" class="px-5 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                        Batal
                    </a>


                    <button type="submit" class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        Simpan Perubahan
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
@endsection
