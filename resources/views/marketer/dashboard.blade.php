@extends('layouts.marketer')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-3xl font-bold text-slate-800">Dashboard</h1>
        <p class="mt-1 text-sm text-slate-500">
            Selamat datang di dashboard marketer.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">

        <!-- Prospect -->
        <div class="rounded-xl bg-white p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Prospect</p>
                    <h2 class="mt-2 text-4xl font-bold text-slate-800">
                        {{ $jumlahProspect }}
                    </h2>
                </div>

                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100">
                    <i class="fas fa-user-plus text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Pasang -->
        <div class="rounded-xl bg-white p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Sudah Pasang</p>
                    <h2 class="mt-2 text-4xl font-bold text-green-600">
                        {{ $jumlahPasang }}
                    </h2>
                </div>

                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-green-100">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Cancel -->
        <div class="rounded-xl bg-white p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Cancel</p>
                    <h2 class="mt-2 text-4xl font-bold text-red-600">
                        {{ $jumlahCancel }}
                    </h2>
                </div>

                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-red-100">
                    <i class="fas fa-times-circle text-2xl text-red-600"></i>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
