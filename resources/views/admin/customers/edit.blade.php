@extends('layouts.app')

@section('title', 'Edit Customer')

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
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.customers.show', $customer) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Customer</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Update customer information</p>
                    </div>
                </div>
            </div>

            <div class="max-w-3xl">
                <form action="{{ route('admin.customers.update', $customer) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="bg-white dark:bg-slate-700 rounded-xl shadow-md p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 @error('name') border-red-500 @enderror">
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username</label>
                            <input type="text" name="username" value="{{ old('username', $customer->username) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 @error('username') border-red-500 @enderror">
                            @error('username')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 @error('phone') border-red-500 @enderror">
                                @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 @error('email') border-red-500 @enderror">
                                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 @error('address') border-red-500 @enderror">{{ old('address', $customer->address) }}</textarea>
                            @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-percent text-green-600 mr-2"></i>Discount (Rp)
                            </label>
                            <input
                                type="number"
                                name="discount"
                                id="discount"
                                value="{{ old('discount', $customer->discount) }}"
                                min="0"
                                step="1000"
                                placeholder="Contoh: 10000"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-green-500 @error('discount') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Nominal diskon yang akan menjadi potongan pada tagihan customer.
                            </p>
                            @error('discount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <!-- Foto KTP -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Foto KTP
                            </label>

                            @if($customer->ktp_photo)
                                <div class="mb-3">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Foto saat ini:</p>
                                    <a href="{{ Storage::url($customer->ktp_photo) }}" target="_blank">
                                        <img src="{{ Storage::url($customer->ktp_photo) }}"
                                             class="w-40 h-28 object-cover rounded-lg border border-gray-200 dark:border-slate-600 hover:opacity-80 transition">
                                    </a>
                                </div>
                            @endif

                            <input
                                type="file"
                                name="ktp_photo"
                                accept="image/*"
                                class="w-full border rounded-lg p-2">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Kosongkan jika tidak ingin mengganti foto KTP.
                            </p>

                            @error('ktp_photo')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto Rumah -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Foto Rumah
                            </label>

                            @if($customer->housePhotos && $customer->housePhotos->count())
                                <div class="mb-3">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Foto yang sudah ada:</p>
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                        @foreach($customer->housePhotos as $item)
                                            <a href="{{ Storage::url($item->photo) }}" target="_blank">
                                                <img src="{{ Storage::url($item->photo) }}"
                                                     class="w-full h-24 object-cover rounded-lg border border-gray-200 dark:border-slate-600 hover:opacity-80 transition">
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <input
                                id="house_photos"
                                type="file"
                                name="house_photos[]"
                                multiple
                                accept="image/*"
                                class="w-full border rounded-lg p-2">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Foto baru akan ditambahkan, foto lama tidak akan terhapus otomatis.
                            </p>

                            <p id="photo-count" class="text-sm text-gray-500 mt-2">
                                Belum ada foto dipilih.
                            </p>

                            <div id="photo-preview" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mt-3"></div>

                            @error('house_photos.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Package</label>
                            <select name="package_id" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500">
                                <option value="">No Package</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('package_id', $customer->package_id) == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" required class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500">
                                <option value="active" {{ old('status', $customer->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $customer->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('status', $customer->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>

                        <div class="border-t border-gray-200 dark:border-slate-600 pt-6 mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                <i class="fas fa-ethernet text-cyan-600 mr-2"></i>PPPoE Configuration
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">PPPoE Username</label>
                                    <input type="text" name="pppoe_username" value="{{ old('pppoe_username', $customer->pppoe_username) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500" placeholder="pppoe_customer001">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">PPPoE Password</label>
                                    <input type="text" name="pppoe_password" value="{{ old('pppoe_password', $customer->pppoe_password) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500" placeholder="********">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Static IP (Optional)</label>
                                    <input type="text" name="static_ip" value="{{ old('static_ip', $customer->static_ip) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500" placeholder="10.10.10.100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">MAC Address</label>
                                    <input type="text" name="mac_address" value="{{ old('mac_address', $customer->mac_address) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-500 dark:bg-slate-600 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500" placeholder="00:11:22:33:44:55">
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>
                                PPPoE credentials will be synced with Mikrotik automatically when saved.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-slate-600">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="px-6 py-2 border border-gray-300 dark:border-slate-500 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-600 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-6 py-2 rounded-lg hover:shadow-lg transition">
                            <i class="fas fa-save mr-2"></i>Update Customer
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

    count.innerHTML = `Total ${this.files.length} foto baru dipilih`;

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