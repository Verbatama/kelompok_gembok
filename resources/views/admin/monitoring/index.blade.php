@extends('layouts.app')

@section('title', 'Customer Monitoring')

@section('content')
<div class="min-h-screen bg-gray-50">

    @include('admin.partials.sidebar')

    <div class="ml-64">

        @include('admin.partials.topbar')

        <main class="p-6">

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    Customer Monitoring
                </h1>
                <p class="text-sm text-gray-500">
                    Monitoring status PPPoE pelanggan
                </p>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Total Customer</p>
                    <h3 class="text-3xl font-bold text-gray-900">
                        {{ $customers->count() }}
                    </h3>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Online</p>
                    <h3 class="text-3xl font-bold text-green-600">
                        {{ $customers->where('online', true)->count() }}
                    </h3>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Offline</p>
                    <h3 class="text-3xl font-bold text-red-600">
                        {{ $customers->where('online', false)->count() }}
                    </h3>
                </div>

            </div>

            {{-- Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Customer Status
                    </h2>
                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Customer
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Username PPPoE
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Paket
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">

                            @forelse($customers as $customer)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">
                                            {{ $customer->name }}
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            {{ $customer->phone }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $customer->pppoe_username }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $customer->package?->name ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4">

                                        @if($customer->online)

                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                <span class="w-2 h-2 mr-2 bg-green-500 rounded-full"></span>
                                                Online
                                            </span>

                                        @else

                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                <span class="w-2 h-2 mr-2 bg-red-500 rounded-full"></span>
                                                Offline
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                        Tidak ada data customer.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

</div>
@endsection