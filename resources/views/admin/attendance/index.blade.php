@extends('layouts.app')

@section('title', 'Absensi')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .att-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    @media (max-width: 768px) {
        .att-grid { grid-template-columns: 1fr; }
    }

    .card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 1.25rem;
    }
    .card-title {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Clock */
    #clock-display { text-align: center; padding: 1.5rem 0; }
    #jam {
        font-size: 52px;
        font-weight: 700;
        letter-spacing: -2px;
        color: #111827;
        font-variant-numeric: tabular-nums;
        line-height: 1;
    }
    #tanggal { font-size: 15px; color: #6b7280; margin-top: 6px; }

    /* Camera */
    .camera-wrapper {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        background: #111827;
        aspect-ratio: 4/3;
    }
    #video {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
        transform: scaleX(-1);
    }
    #canvas { display: none; }
    #preview-photo {
        width: 100%; height: 100%;
        object-fit: cover;
        border-radius: 10px;
        display: none;
    }
    .cam-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0,0,0,0.55);
        color: #fff;
        flex-direction: column;
        gap: 10px;
        border-radius: 10px;
    }
    .cam-overlay svg { width: 36px; height: 36px; opacity: 0.8; }
    #btn-start-cam {
        background: #fff; color: #111;
        border: none; border-radius: 8px;
        padding: 8px 18px; font-size: 13px;
        font-weight: 600; cursor: pointer;
    }
    #btn-capture {
        width: 100%; margin-top: 12px; padding: 10px;
        background: #1d4ed8; color: #fff;
        border: none; border-radius: 8px;
        font-size: 14px; font-weight: 600;
        cursor: pointer; display: none;
    }
    #btn-retake {
        width: 100%; margin-top: 8px; padding: 9px;
        background: #f3f4f6; color: #374151;
        border: 1px solid #d1d5db; border-radius: 8px;
        font-size: 13px; cursor: pointer; display: none;
    }
    .photo-status {
        font-size: 12px; color: #6b7280;
        margin-top: 6px; text-align: center;
    }
    .photo-status.ok { color: #16a34a; }

    /* Map */
    #map {
        height: 220px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        z-index: 1;
    }
    #lokasi-info {
        margin-top: 10px; font-size: 12px;
        color: #6b7280; line-height: 1.5;
    }

    /* Action buttons */
    .btn-action {
        width: 100%; padding: 14px;
        border: none; border-radius: 10px;
        font-size: 16px; font-weight: 700;
        cursor: pointer;
        display: flex; align-items: center;
        justify-content: center; gap: 10px;
        transition: transform 0.1s, opacity 0.2s;
    }
    .btn-action:active { transform: scale(0.98); }
    .btn-action:disabled { opacity: 0.45; cursor: not-allowed; transform: none; }

    #btn-checkin {
        background: #2563eb; color: #fff;
        box-shadow: 0 4px 14px rgba(37,99,235,0.3);
    }
    #btn-checkout {
        background: #dc2626; color: #fff;
        box-shadow: 0 4px 14px rgba(220,38,38,0.3);
        display: none;
    }

    /* Status badge */
    .s-badge {
        display: inline-flex; align-items: center;
        gap: 6px; padding: 4px 12px;
        border-radius: 999px; font-size: 12px; font-weight: 600;
    }
    .s-badge.belum { background: #fef9c3; color: #a16207; }
    .s-badge.masuk { background: #dbeafe; color: #1d4ed8; }
    .s-badge.selesai { background: #dcfce7; color: #15803d; }
    .s-dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
    .s-badge.belum .s-dot { animation: blink 1.5s infinite; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

    .info-row {
        display: flex; justify-content: space-between;
        align-items: center; padding: 8px 0;
        border-bottom: 1px solid #f3f4f6; font-size: 13px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .lbl { color: #6b7280; }
    .info-row .val { color: #111827; font-weight: 500; }

    /* Alert */
    .flash {
        padding: 10px 14px; border-radius: 8px;
        font-size: 13px; margin-bottom: 1rem; display: none;
    }
    .flash.err  { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }
    .flash.ok   { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }

    @keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
</style>
@endpush

@section('content')
<div style="max-width:960px;margin:0 auto;padding:1.5rem 1rem;">

    <div style="margin-bottom:1.5rem;">
        <h1 style="font-size:22px;font-weight:700;color:#111827;margin:0;">Absensi</h1>
        <p style="font-size:14px;color:#6b7280;margin-top:4px;">
            Foto selfie + lokasi wajib sebelum check-in atau check-out
        </p>
    </div>

    <div id="flash-box" class="flash"></div>

    @if(session('success'))
        <div class="flash ok" style="display:block;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash err" style="display:block;">{{ session('error') }}</div>
    @endif

    <div class="att-grid">

        {{-- Kolom kiri: jam + kamera --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Jam --}}
            <div class="card">
                <div class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Waktu Sekarang
                </div>
                <div id="clock-display">
                    <div id="jam">--:--:--</div>
                    <div id="tanggal">Memuat...</div>
                </div>
            </div>

            {{-- Kamera --}}
            <div class="card">
                <div class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    Foto Selfie
                </div>

                <div class="camera-wrapper" id="cam-wrap">
                    <video id="video" autoplay playsinline></video>
                    <div class="cam-overlay" id="cam-overlay">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                        <p style="margin:0;font-size:13px;">Kamera belum aktif</p>
                        <button id="btn-start-cam" onclick="startCamera()">Aktifkan Kamera</button>
                    </div>
                </div>
                <img id="preview-photo" alt="Foto selfie" />
                <canvas id="canvas"></canvas>

                <button id="btn-capture" onclick="capturePhoto()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 2v2m0 16v2M4.22 4.22l1.42 1.42m12.72 12.72 1.42 1.42M2 12h2m16 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                    Ambil Foto
                </button>
                <button id="btn-retake" onclick="retakePhoto()">Ulangi Foto</button>
                <p class="photo-status" id="photo-status">Aktifkan kamera lalu ambil foto selfie</p>
            </div>

        </div>

        {{-- Kolom kanan: peta + status + tombol --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Peta --}}
            <div class="card">
                <div class="card-title" style="justify-content:space-between;">
                    <span style="display:flex;align-items:center;gap:8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Lokasi Anda
                    </span>
                    <button onclick="getLocation()" style="background:none;border:1px solid #d1d5db;border-radius:6px;padding:4px 10px;font-size:12px;cursor:pointer;color:#374151;">
                        Perbarui
                    </button>
                </div>
                <div id="map"></div>
                <div id="lokasi-info" style="margin-top:10px;font-size:12px;color:#ca8a04;">
                    ⏳ Mendeteksi lokasi...
                </div>
            </div>

            {{-- Status info --}}
            <div class="card">
                <div class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Status Absensi Hari Ini
                </div>
                <div style="margin-bottom:12px;">
                    <span class="s-badge {{ $sudahCheckout ? 'selesai' : ($sudahCheckin ? 'masuk' : 'belum') }}" id="s-badge">
                        <span class="s-dot"></span>
                        {{ $sudahCheckout ? 'Sudah Check-Out' : ($sudahCheckin ? 'Sudah Check-In' : 'Belum Absen') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="lbl">Nama</span>
                    <span class="val">{{ auth()->user()->name }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Check-In</span>
                    <span class="val">{{ $jamMasuk ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Check-Out</span>
                    <span class="val">{{ $jamKeluar ?? '-' }}</span>
                </div>
            </div>

            {{-- Tombol aksi --}}
            <div>
                {{-- Check-In --}}
                @if(!$sudahCheckin)
                <button id="btn-checkin" class="btn-action" onclick="submitAbsensi('check-in')" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Check In
                </button>
                @endif

                {{-- Check-Out --}}
                @if($sudahCheckin && !$sudahCheckout)
                <button id="btn-checkout" class="btn-action" style="display:flex;" onclick="submitAbsensi('check-out')" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Check Out
                </button>
                @endif

                {{-- Selesai --}}
                @if($sudahCheckout)
                <div style="text-align:center;padding:14px;background:#f0fdf4;border-radius:10px;border:1px solid #bbf7d0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24" style="margin-bottom:4px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <p style="margin:4px 0 0;font-size:13px;color:#15803d;font-weight:600;">Absensi hari ini sudah selesai</p>
                </div>
                @endif

                <p style="text-align:center;font-size:12px;color:#9ca3af;margin-top:8px;" id="hint-text">
                    @if(!$sudahCheckin)
                        Aktifkan kamera, ambil foto, dan pastikan lokasi terdeteksi
                    @elseif(!$sudahCheckout)
                        Ambil foto selfie dan pastikan lokasi terdeteksi untuk check-out
                    @endif
                </p>
            </div>

        </div>
    </div>

</div>

{{-- Form tersembunyi — field name sesuai controller --}}
<form id="form-absensi" method="POST" action="{{ route('admin.attendance.store') }}" style="display:none;">
    @csrf
    <input type="hidden" name="status"        id="input-status">
    <input type="hidden" name="image_selfie"  id="input-image">
    <input type="hidden" name="latitude"      id="input-lat">
    <input type="hidden" name="longitude"     id="input-lng">
</form>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let videoStream = null;
    let photoTaken  = false;
    let lat = null, lng = null;
    let map = null, marker = null;

    const sudahCheckin  = @json($sudahCheckin);
    const sudahCheckout = @json($sudahCheckout);

    /* ---- CLOCK ---- */
    function tickClock() {
        const now = new Date();
        document.getElementById('jam').textContent =
            now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:false });
        document.getElementById('tanggal').textContent =
            now.toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
    }
    setInterval(tickClock, 1000);
    tickClock();

    /* ---- MAP ---- */
    function initMap() {
        map = L.map('map', { zoomControl:true, attributionControl:false }).setView([-6.2, 106.816], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        getLocation();
    }

    function getLocation() {
        if (!navigator.geolocation) {
            document.getElementById('lokasi-info').innerHTML = '<span style="color:#b91c1c">Browser tidak mendukung geolokasi.</span>';
            return;
        }
        document.getElementById('lokasi-info').innerHTML = '<span style="color:#ca8a04">⏳ Mendeteksi lokasi...</span>';

        navigator.geolocation.getCurrentPosition(pos => {
            lat = pos.coords.latitude.toFixed(7);
            lng = pos.coords.longitude.toFixed(7);

            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(map).bindPopup('Lokasi Anda').openPopup();
            map.setView([lat, lng], 16);

            document.getElementById('input-lat').value = lat;
            document.getElementById('input-lng').value = lng;

            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                .then(r => r.json())
                .then(d => {
                    const addr = (d.display_name || `${lat}, ${lng}`).substring(0, 90);
                    document.getElementById('lokasi-info').innerHTML =
                        `<span style="color:#16a34a;font-weight:600;">✓ Lokasi terdeteksi</span><br>${addr}`;
                })
                .catch(() => {
                    document.getElementById('lokasi-info').innerHTML =
                        `<span style="color:#16a34a">✓ ${lat}, ${lng}</span>`;
                });

            checkReady();
        }, () => {
            document.getElementById('lokasi-info').innerHTML =
                '<span style="color:#b91c1c">❌ Gagal. Izinkan akses lokasi di browser.</span>';
        }, { enableHighAccuracy:true, timeout:12000 });
    }

    /* ---- CAMERA ---- */
    async function startCamera() {
        try {
            videoStream = await navigator.mediaDevices.getUserMedia({ video:{ facingMode:'user' }, audio:false });
            document.getElementById('video').srcObject = videoStream;
            document.getElementById('cam-overlay').style.display = 'none';
            document.getElementById('btn-capture').style.display = 'block';
        } catch {
            flash('Gagal mengakses kamera. Izinkan akses kamera di browser.', 'err');
        }
    }

    function capturePhoto() {
        const video  = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        canvas.width  = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        /* mirror supaya hasil foto tidak terbalik */
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(video, 0, 0);

        const dataUrl = canvas.toDataURL('image/jpeg', 0.82);
        document.getElementById('input-image').value = dataUrl;

        const preview = document.getElementById('preview-photo');
        preview.src = dataUrl;
        preview.style.display = 'block';
        document.getElementById('cam-wrap').style.display = 'none';
        document.getElementById('btn-capture').style.display = 'none';
        document.getElementById('btn-retake').style.display = 'block';

        if (videoStream) videoStream.getTracks().forEach(t => t.stop());

        photoTaken = true;
        const ps = document.getElementById('photo-status');
        ps.textContent = '✓ Foto berhasil diambil';
        ps.className = 'photo-status ok';
        checkReady();
    }

    function retakePhoto() {
        photoTaken = false;
        document.getElementById('preview-photo').style.display = 'none';
        document.getElementById('preview-photo').src = '';
        document.getElementById('input-image').value = '';
        document.getElementById('cam-wrap').style.display = 'block';
        document.getElementById('cam-overlay').style.display = 'flex';
        document.getElementById('btn-retake').style.display = 'none';
        const ps = document.getElementById('photo-status');
        ps.textContent = 'Aktifkan kamera lalu ambil foto selfie';
        ps.className = 'photo-status';
        checkReady();
        startCamera();
    }

    /* ---- READY CHECK ---- */
    function checkReady() {
        const ready = photoTaken && lat !== null;
        const btnCI = document.getElementById('btn-checkin');
        const btnCO = document.getElementById('btn-checkout');
        const hint  = document.getElementById('hint-text');
        if (btnCI) btnCI.disabled = !ready;
        if (btnCO) btnCO.disabled = !ready;
        if (hint) {
            hint.textContent = ready
                ? (sudahCheckin ? 'Siap! Klik Check Out untuk mencatat kepulangan.' : 'Siap! Klik Check In untuk mencatat kehadiran.')
                : 'Aktifkan kamera, ambil foto, dan pastikan lokasi terdeteksi';
        }
    }

    /* ---- SUBMIT ---- */
    function submitAbsensi(status) {
        if (!photoTaken) { flash('Ambil foto selfie terlebih dahulu.', 'err'); return; }
        if (!lat)        { flash('Lokasi belum terdeteksi. Klik "Perbarui".', 'err'); return; }

        document.getElementById('input-status').value = status;

        const btn = status === 'check-in'
            ? document.getElementById('btn-checkin')
            : document.getElementById('btn-checkout');

        btn.disabled = true;
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24" style="animation:spin 1s linear infinite"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg> Menyimpan...`;
        document.getElementById('form-absensi').submit();
    }

    /* ---- FLASH ---- */
    function flash(msg, type) {
        const el = document.getElementById('flash-box');
        el.className = `flash ${type}`;
        el.textContent = msg;
        el.style.display = 'block';
        setTimeout(() => el.style.display = 'none', 5000);
    }

    /* ---- BOOT ---- */
    document.addEventListener('DOMContentLoaded', () => {
        initMap();
    });
</script>
@endpush