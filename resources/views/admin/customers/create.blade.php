@extends('layouts.app')

@section('title', 'Create Customer')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-slate-800 transition-colors duration-300" 
     x-data="{ 
         sidebarOpen: false, 
         isDark: localStorage.getItem('theme') === 'dark' 
     }">
    @include('admin.partials.sidebar')

    <div class="lg:pl-64">
        @include('admin.partials.topbar')

        <div class="p-6">
            <div class="mb-6">
                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('admin.customers.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">Customers</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900 dark:text-white">Create</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create New Customer</h1>
            </div>

            <div class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6 max-w-3xl">
                <form action="{{ route('admin.customers.store') }}"
                 method="POST"
                 enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-user mr-2 text-blue-600"></i>Full Name *
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-id-badge mr-2 text-blue-600"></i>Username
                            </label>
                            <input type="text" name="username" id="username" value="{{ old('username') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('username') border-red-500 @enderror">
                            @error('username')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-phone mr-2 text-blue-600"></i>Phone Number
                            </label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                            @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-envelope mr-2 text-blue-600"></i>Email Address
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>Address
                            </label>
                            <textarea name="address" id="address" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                            @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                            <div class="md:col-span-2">
                            <label for="discount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-percent mr-2 text-green-600"></i>Discount (Rp)
                            </label>
                            <input
                             type="number"
                             name="discount"
                             id="discount"
                             value="{{ old('discount', 0) }}"
                             min="0"
                             step="1000"
                            placeholder="Contoh: 10000"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('discount') border-red-500 @enderror">

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                  Masukkan nominal diskon dalam Rupiah. Nilai ini akan menjadi potongan pada tagihan customer.
             </p>

            @error('discount')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
         @enderror
    </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                     Foto KTP
                                </label>

                                <input
                                    type="file"
                                    name="ktp_photo"
                                    accept="image/*"
                                    class="w-full border rounded-lg p-2">

                                    @error('ktp_photo')
                                         <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                
                            <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Foto Rumah
                            </label>

                                <input
                                    id="house_photos"
                                    type="file"
                                   name="house_photos[]"
                                    multiple
                                   accept="image/*"
                                   class="w-full border rounded-lg p-2">

                                <p id="photo-count" class="text-sm text-gray-500 mt-2">
                                    Belum ada foto dipilih.
                                  </p>

                                    <div id="photo-preview" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mt-3"></div>

                                    @error('house_photos.*')
                                     <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                 @enderror
                            </div>

                        <div>
                            <label for="package_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-box mr-2 text-blue-600"></i>Package
                            </label>
                            <select name="package_id" id="package_id"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Package</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-toggle-on mr-2 text-blue-600"></i>Status *
                            </label>
                            <select name="status" id="status" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 border-t border-gray-200 dark:border-slate-600 pt-6 mt-2">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                                <i class="fas fa-network-wired mr-2 text-cyan-600"></i>PPPoE Configuration (Mikrotik)
                            </h3>
                        </div>

                        <div>
                            <label for="pppoe_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-user-shield mr-2 text-cyan-600"></i>PPPoE Username
                            </label>
                            <input type="text" name="pppoe_username" id="pppoe_username" value="{{ old('pppoe_username') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                placeholder="Contoh: pppoe-customer001">
                        </div>

                        <div>
                            <label for="pppoe_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-key mr-2 text-cyan-600"></i>PPPoE Password
                            </label>
                            <input type="text" name="pppoe_password" id="pppoe_password" value="{{ old('pppoe_password') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                placeholder="Password untuk koneksi PPPoE">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kosongkan jika tidak menggunakan Mikrotik</p>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end space-x-4 border-t border-gray-200 dark:border-slate-600 pt-6">
                        <a href="{{ route('admin.customers.index') }}" class="px-6 py-3 border border-gray-300 dark:border-slate-500 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-600 transition">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transition transform hover:scale-105 shadow-lg">
                            <i class="fas fa-save mr-2"></i>Create Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
const input = document.getElementById('house_photos');
const preview = document.getElementById('photo-preview');
const count = document.getElementById('photo-count');

input.addEventListener('change', function () {

    preview.innerHTML = '';

    if (this.files.length === 0) {
        count.innerHTML = 'Belum ada foto dipilih.';
        return;
    }

    count.innerHTML = `Total ${this.files.length} foto dipilih`;

    Array.from(this.files).forEach(file => {

        const reader = new FileReader();

        reader.onload = function(e) {

            preview.innerHTML += `
                <div class="border rounded-lg p-2 bg-white dark:bg-slate-700">
                    <img src="${e.target.result}"
                         class="w-full h-32 object-cover rounded">

                    <p class="text-xs mt-2 break-all text-center">
                        ${file.name}
                    </p>
                </div>
            `;

        };

        reader.readAsDataURL(file);

    });

});
</script>
@endsection