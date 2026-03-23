@extends('layouts.app')

@section('content')
<div class="space-y-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-bold uppercase tracking-widest border border-indigo-100">Pengaturan</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Lokasi Sekolah</h1>
            <p class="text-slate-500 font-medium mt-1">Atur koordinat dan radius absensi sekolah.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Map -->
        <div class="lg:col-span-3 bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100">
                <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Peta Lokasi Sekolah</h3>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Klik atau drag marker untuk mengubah posisi</p>
            </div>
            <div id="school-map" style="height: 450px; width: 100%;"></div>
        </div>

        <!-- Settings Form -->
        <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-100 shadow-sm p-8">
            <form action="{{ route('admin.settings.school.update') }}" method="POST" id="settings-form">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- School Name -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Nama Sekolah</label>
                        <input type="text" name="school_name" value="{{ $settings['school_name'] }}"
                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 font-bold text-slate-700 outline-none transition-all"
                            required>
                        @error('school_name') <p class="text-xs text-rose-500 font-bold mt-2">{{ $message }}</p> @enderror
                    </div>

                    <!-- Latitude -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Latitude (Lintang)</label>
                        <input type="text" name="school_latitude" id="input-lat" value="{{ $settings['school_latitude'] }}"
                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 font-bold font-mono text-slate-700 outline-none transition-all"
                            required>
                        @error('school_latitude') <p class="text-xs text-rose-500 font-bold mt-2">{{ $message }}</p> @enderror
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Longitude (Bujur)</label>
                        <input type="text" name="school_longitude" id="input-lng" value="{{ $settings['school_longitude'] }}"
                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 font-bold font-mono text-slate-700 outline-none transition-all"
                            required>
                        @error('school_longitude') <p class="text-xs text-rose-500 font-bold mt-2">{{ $message }}</p> @enderror
                    </div>

                    <!-- Radius -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Radius Absensi (meter)</label>
                        <input type="number" name="school_radius" id="input-radius" value="{{ $settings['school_radius'] }}" min="50" max="5000" step="10"
                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 font-bold text-slate-700 outline-none transition-all"
                            required>
                        <p class="text-[10px] text-slate-400 font-medium mt-2">Jarak maksimal guru dari sekolah untuk bisa absen (50 - 5000 meter)</p>
                        @error('school_radius') <p class="text-xs text-rose-500 font-bold mt-2">{{ $message }}</p> @enderror
                    </div>

                    <!-- Min Check-in Time -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Jam Masuk Minimal</label>
                        <input type="time" name="min_check_in_time" value="{{ $settings['min_check_in_time'] }}"
                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 font-bold text-slate-700 outline-none transition-all"
                            required>
                        <p class="text-[10px] text-slate-400 font-medium mt-2">Guru bisa absen masuk mulai jam ini</p>
                        @error('min_check_in_time') <p class="text-xs text-rose-500 font-bold mt-2">{{ $message }}</p> @enderror
                    </div>

                    <!-- Min Check-out Time -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Jam Pulang Minimal</label>
                        <input type="time" name="min_check_out_time" value="{{ $settings['min_check_out_time'] }}"
                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 font-bold text-slate-700 outline-none transition-all"
                            required>
                        <p class="text-[10px] text-slate-400 font-medium mt-2">Guru bisa absen pulang mulai jam ini</p>
                        @error('min_check_out_time') <p class="text-xs text-rose-500 font-bold mt-2">{{ $message }}</p> @enderror
                    </div>

                    <!-- Info -->
                    <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-2xl">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-indigo-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-xs text-indigo-700 font-medium leading-relaxed">Seret marker pada peta atau ubah koordinat secara manual. Perubahan radius akan langsung terlihat pada lingkaran di peta.</p>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-bold shadow-lg shadow-emerald-100 hover:bg-emerald-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let lat = parseFloat(document.getElementById('input-lat').value) || -5.1436;
    let lng = parseFloat(document.getElementById('input-lng').value) || 119.4667;
    let radius = parseInt(document.getElementById('input-radius').value) || 200;

    const map = L.map('school-map').setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    // School marker (draggable)
    const schoolIcon = L.divIcon({
        html: `<div style="background: #dc2626; width: 36px; height: 36px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 4px 12px rgba(220,38,38,0.4); display: flex; align-items: center; justify-content: center; cursor: grab;">
                 <svg style="transform: rotate(45deg); width: 18px; height: 18px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
               </div>`,
        className: '',
        iconSize: [36, 36],
        iconAnchor: [18, 36],
    });

    const marker = L.marker([lat, lng], { icon: schoolIcon, draggable: true }).addTo(map);

    // Radius circle
    let circle = L.circle([lat, lng], {
        radius: radius,
        color: '#dc2626',
        fillColor: '#dc2626',
        fillOpacity: 0.08,
        weight: 2,
        dashArray: '8 4',
    }).addTo(map);

    // Update on drag
    marker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        document.getElementById('input-lat').value = pos.lat.toFixed(7);
        document.getElementById('input-lng').value = pos.lng.toFixed(7);
        circle.setLatLng(pos);
    });

    // Update on click
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        document.getElementById('input-lat').value = e.latlng.lat.toFixed(7);
        document.getElementById('input-lng').value = e.latlng.lng.toFixed(7);
        circle.setLatLng(e.latlng);
    });

    // Update radius on input change
    document.getElementById('input-radius').addEventListener('input', function() {
        const newRadius = parseInt(this.value) || 200;
        circle.setRadius(newRadius);
    });

    // Update marker on manual coordinate input
    document.getElementById('input-lat').addEventListener('change', function() {
        const newLat = parseFloat(this.value);
        const curLng = parseFloat(document.getElementById('input-lng').value);
        if (!isNaN(newLat) && !isNaN(curLng)) {
            marker.setLatLng([newLat, curLng]);
            circle.setLatLng([newLat, curLng]);
            map.setView([newLat, curLng]);
        }
    });

    document.getElementById('input-lng').addEventListener('change', function() {
        const curLat = parseFloat(document.getElementById('input-lat').value);
        const newLng = parseFloat(this.value);
        if (!isNaN(curLat) && !isNaN(newLng)) {
            marker.setLatLng([curLat, newLng]);
            circle.setLatLng([curLat, newLng]);
            map.setView([curLat, newLng]);
        }
    });
});
</script>
@endsection
