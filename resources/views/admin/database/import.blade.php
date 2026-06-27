@extends('layouts.app')

@section('title', 'Import Customer')

@section('content')

    <div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen:false }">

        {{-- Sidebar --}}
        @include('admin.partials.sidebar')

        {{-- Content Area --}}
        <div class="lg:pl-64">

            {{-- Topbar --}}
            @include('admin.partials.topbar')

            <div class="p-6">

                {{-- Card Tengah --}}
                <div class="max-w-3xl mx-auto">

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

                        {{-- Header --}}
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800">
                                Import Customer
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">
                                Upload file Excel atau CSV untuk menambahkan data customer.
                            </p>
                        </div>

                        {{-- Body --}}
                        <div class="p-6">

                            @if(session('success'))
                                <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-700">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-700">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-700">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.database.import.store') }}"
                                enctype="multipart/form-data" class="space-y-5">

                                @csrf

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        File Customer
                                    </label>

                                    <label class="flex flex-col items-center justify-center w-full h-40
                                                  border-2 border-dashed border-gray-300 rounded-xl
                                                  cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">

                                        <div class="text-center">

                                            <svg class="mx-auto w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1117 8h1a4 4 0 010 8h-1m-4-4l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>

                                            <p class="mt-2 text-sm text-gray-600">
                                                Klik untuk upload file
                                            </p>

                                            <p class="text-xs text-gray-400">
                                                XLSX atau CSV
                                            </p>

                                        </div>

                                        <input type="file" name="file" class="hidden" accept=".xlsx,.csv" required>

                                    </label>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                                    <p class="font-medium text-gray-700">
                                        Column format file harus:
                                    </p>

                                    <div class="mt-2 p-3 bg-white border rounded font-mono text-xs break-all">
                                        username | pppoe_username | pppoe_password | static_ip |
                                        mac_address | name | phone | email | address |
                                        package_id | status | join_date |
                                        latitude | longitude
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="px-6 py-2.5 rounded-lg
                                                   bg-blue-600 text-white
                                                   hover:bg-blue-700
                                                   transition font-medium">
                                        Import Customer
                                    </button>
                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection