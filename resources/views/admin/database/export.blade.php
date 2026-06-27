@extends('layouts.app')

@section('title', 'Export Customer')

@section('content')

    <div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen:false }">

        @include('admin.partials.sidebar')


        <div class="lg:pl-64">

            @include('admin.partials.topbar')


            <div class="p-6">

                <div class="max-w-3xl mx-auto">

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">


                        {{-- Header --}}
                        <div class="px-6 py-5 border-b border-gray-200">

                            <h2 class="text-xl font-semibold text-gray-800">
                                Export Customer
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">
                                Export data customer ke format Excel atau CSV.
                            </p>

                        </div>



                        {{-- Body --}}
                        <div class="p-6">


                            <form action="{{ route('admin.database.export.download') }}" method="GET" class="space-y-5">



                                {{-- Status --}}
                                <div>

                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Status Customer
                                    </label>


                                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg 
                                        focus:ring-2 focus:ring-blue-500">

                                        <option value="">
                                            Semua Customer
                                        </option>

                                        <option value="active">
                                            Active
                                        </option>

                                        <option value="inactive">
                                            Inactive
                                        </option>

                                        <option value="suspended">
                                            Suspended
                                        </option>

                                    </select>

                                </div>




                                {{-- Format --}}
                                <div>

                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Format Export
                                    </label>


                                    <select name="format" class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                        focus:ring-2 focus:ring-blue-500">


                                        <option value="xlsx">
                                            Excel (.xlsx)
                                        </option>


                                        <option value="csv">
                                            CSV (.csv)
                                        </option>


                                    </select>


                                </div>




                                {{-- Info --}}
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">

                                    <div class="flex items-start">

                                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>

                                        <p class="text-sm text-blue-700">

                                            Data customer akan diexport sesuai filter yang dipilih.

                                        </p>

                                    </div>

                                </div>




                                {{-- Button --}}
                                <div class="flex justify-end">


                                    <button type="submit" class="px-6 py-3 rounded-lg
                                        bg-green-600 text-white
                                        hover:bg-green-700
                                        transition
                                        font-medium">

                                        <i class="fas fa-file-export mr-2"></i>

                                        Export Customer

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