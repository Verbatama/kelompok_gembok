```blade
@extends('layouts.marketer')

@section('content')
<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Daftar Prospect
            </h1>
            <p class="text-gray-500 mt-1">
                Kelola seluruh data calon customer.
            </p>
        </div>

        <a href="{{ route('marketer.create_prospect') }}" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            + Tambah Prospect
        </a>
    </div>

    @if(session('success'))
    <div class="mb-5 rounded-lg border border-green-200 bg-green-100 px-4 py-3 text-green-700">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-hidden rounded-xl bg-white shadow">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">#</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">No. HP</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Alamat</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">

                    @forelse($prospects as $prospect)

                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $prospect->name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $prospect->phone }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $prospect->email ?: '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ Str::limit($prospect->address, 50) }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">

                                <a href="{{ route('marketer.show', $prospect->id) }}" class="rounded-lg bg-blue-500 px-3 py-2 text-sm text-white hover:bg-blue-600">
                                    Detail
                                </a>

                                <a href="{{ route('marketer.edit', $prospect->id) }}" class="rounded-lg bg-yellow-500 px-3 py-2 text-sm text-white hover:bg-yellow-600">
                                    Edit
                                </a>

                                <form action="{{ route('marketer.destroy', $prospect->id) }}" method="POST" onsubmit="return confirm('Hapus prospect ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button class="rounded-lg bg-red-500 px-3 py-2 text-sm text-white hover:bg-red-600">
                                        Hapus
                                    </button>

                                </form>

                            </div>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="6" class="py-10 text-center text-gray-500">
                            Belum ada data prospect.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="mt-6">
        {{ $prospects->links() }}
    </div>

</div>
@endsection
```
