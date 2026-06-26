<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Mandiri</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body class="bg-gray-100 min-h-screen py-10 px-4">

    <div class="max-w-4xl mx-auto">
        
        <div class="bg-white rounded-2xl p-6 shadow-md mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Sistem Absensi Mandiri</h1>
                <p class="text-gray-500">Silakan ambil selfie dan tentukan status absensi Anda.</p>
            </div>
            <div class="text-center md:text-right bg-blue-50 text-blue-700 px-6 py-3 rounded-xl border border-blue-100">
                <div id="clock" class="text-3xl font-black tracking-wider">00:00:00</div>
                <div id="date" class="text-sm font-medium mt-1 text-blue-600">Hari, Tanggal</div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-white rounded-2xl p-6 shadow-md">
                <form action="{{ route('absensi.store') }}" method="POST" id="form-absen">
                    @csrf
                    
                    <div class="mb-5">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-gray-700 font-semibold" for="nama">Nama Karyawan</label>
                            <button type="button" id="btn-ganti-nama" onclick="clearSavedName()" class="text-xs text-red-600 hover:underline hidden">
                                Ganti Pengguna?
                            </button>
                        </div>
                        <input type="text" name="nama" id="nama" placeholder="Masukkan nama lengkap..." 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-800 transition duration-150" required>
                    </div>

                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <input type="hidden" name="selfie" id="selfie-base64">
                    <input type="hidden" name="status" id="status-absen" value="check-in"> 

                    <div class="mb-5">
                        <label class="block text-gray-700 font-semibold mb-2">Kamera Selfie</label>
                        <div class="relative w-full aspect-[4/3] bg-gray-900 rounded-xl overflow-hidden border border-gray-200 shadow-inner flex items-center justify-center">
                            <video id="video" autoplay playsinline class="w-full h-full object-cover"></video>
                            <img id="preview" class="hidden w-full h-full object-cover absolute top-0 left-0">
                            <canvas id="canvas" class="hidden"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <button type="button" onclick="take_snapshot()" id="btn-capture" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-xl transition duration-200 cursor-pointer text-center text-sm">
                            Ambil Foto
                        </button>
                        <button type="button" onclick="reset_camera()" id="btn-reset" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2.5 px-4 rounded-xl transition duration-200 hidden cursor-pointer text-center text-sm">
                            Foto Ulang
                        </button>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Lokasi Anda (Peta)</label>
                        <div id="map" class="w-full h-48 rounded-xl border border-gray-200 shadow-sm z-0"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" onclick="submitAbsen('check-in')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-emerald-600/20 transition duration-200 cursor-pointer text-center">
                            Check In
                        </button>
                        <button type="button" onclick="submitAbsen('check-out')" class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-rose-600/20 transition duration-200 cursor-pointer text-center">
                            Check Out
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-md flex flex-col">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Riwayat Absen Hari Ini</h2>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 text-gray-400 text-sm font-semibold">
                                <th class="pb-3">Nama</th>
                                <th class="pb-3">Jam</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3 text-center">Foto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm text-gray-600">
                            @forelse($riwayatAbsen as $row)
                                <tr>
                                    <td class="py-3 font-medium text-gray-800">{{ $row->nama }}</td>
                                    <td class="py-3">{{ date('H:i', strtotime($row->created_at)) }} WIB</td>
                                    <td class="py-3">
                                        <span class="px-2.5 py-1 rounded-md text-xs font-bold {{ ($row->status ?? 'check-in') == 'check-in' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                            {{ strtoupper($row->status ?? 'check-in') }}
                                        </span>
                                    </td>
                                    <td class="py-3 flex justify-center">
                                        @if(isset($row->image_selfie) && $row->image_selfie)
                                            <img src="{{ asset($row->image_selfie) }}" class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                        @else
                                            <span class="text-xs text-gray-400">Tidak ada foto</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-gray-400 italic">Belum ada absensi masuk hari ini.</td>
                                }
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $riwayatAbsen->links() }}
                </div>
            </div>

        </div>
    </div>

    <script>
        let map;
        let marker;

        const namaInput = document.getElementById('nama');
        const btnGantiNama = document.getElementById('btn-ganti-nama');

        // Fungsi Memeriksa Nama yang Tersimpan di Browser
        function checkSavedUser() {
            const savedName = localStorage.getItem('absensi_user_name');
            if (savedName) {
                namaInput.value = savedName;
                namaInput.readOnly = true;
                namaInput.classList.add('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
                btnGantiNama.classList.remove('hidden');
            }
        }

        // Fungsi Menghapus Nama Terkunci
        function clearSavedName() {
            localStorage.removeItem('absensi_user_name');
            namaInput.value = '';
            namaInput.readOnly = false;
            namaInput.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
            btnGantiNama.classList.add('hidden');
            namaInput.focus();
        }

        // 1. Digital Jam Realtime
        function updateClock() {
            const now = new Date();
            const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            
            document.getElementById('clock').textContent = now.toLocaleTimeString('id-ID');
            document.getElementById('date').textContent = now.toLocaleDateString('id-ID', optionsDate);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Run cek user saat web selesai dimuat
        checkSavedUser();

        // 2. Setup Kamera Stream HTML5
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const preview = document.getElementById('preview');
        const btnCapture = document.getElementById('btn-capture');
        const btnReset = document.getElementById('btn-reset');
        const selfieInput = document.getElementById('selfie-base64');

        navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: "user" }, 
            audio: false 
        })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => {
            alert("Gagal mengakses kamera: " + err.message + ". Pastikan izin kamera diberikan.");
        });

        function take_snapshot() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const dataUri = canvas.toDataURL('image/jpeg', 0.9);
            selfieInput.value = dataUri;
            
            preview.src = dataUri;
            preview.classList.remove('hidden');
            video.classList.add('hidden');
            
            btnCapture.classList.add('hidden');
            btnReset.classList.remove('hidden');
        }

        function reset_camera() {
            selfieInput.value = '';
            preview.classList.add('hidden');
            video.classList.remove('hidden');
            
            btnCapture.classList.remove('hidden');
            btnReset.classList.add('hidden');
        }

        // 3. Geolocation & Leaflet Map
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                map = L.map('map').setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup('Lokasi Anda Saat Ini')
                    .openPopup();

            }, function(error) {
                console.warn('Gagal memuat koordinat lokasi: ' + error.message);
                initDefaultMap(-6.200000, 106.816666);
            });
        } else {
            console.warn('Browser tidak support fitur Geolocation.');
            initDefaultMap(-6.200000, 106.816666);
        }

        function initDefaultMap(lat, lng) {
            map = L.map('map').setView([lat, lng], 11);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        }

        // 4. Handle Submit Multi-tombol
        function submitAbsen(type) {
            const nama = namaInput.value.trim();
            
            if (!nama) {
                alert('Silakan isi nama Anda terlebih dahulu.');
                return;
            }
            if (!selfieInput.value) {
                alert('Silakan ambil foto selfie terlebih dahulu sebelum mengirim absensi.');
                return;
            }

            // Simpan nama ke localStorage biar tidak perlu ngetik lagi saat checkout
            localStorage.setItem('absensi_user_name', nama);

            document.getElementById('status-absen').value = type;
            document.getElementById('form-absen').submit();
        }
    </script>
</body>
</html>