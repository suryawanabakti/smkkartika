@extends('layouts.app')

@section('content')
<div class="space-y-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Log Kehadiran Saya</h1>
            <p class="text-slate-500 font-medium mt-1">Pantau catatan kehadiran harian Anda.</p>
        </div>
        
        @if($todayAttendance && $todayAttendance->check_out_time)
            <div class="px-6 py-3 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-700 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500/70 leading-none">Selesai</p>
                    <p class="font-bold">Masuk & Pulang Tercatat</p>
                </div>
            </div>
        @elseif($todayAttendance)
            <div class="px-6 py-3 bg-amber-50 border border-amber-100 rounded-2xl flex items-center gap-3 text-amber-700 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-500/70 leading-none">Status</p>
                    <p class="font-bold">Sudah Masuk — Belum Pulang</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Map & Attendance Section -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 sm:gap-8">
        <!-- Map -->
        <div class="lg:col-span-3 bg-white rounded-2xl sm:rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-4 sm:px-8 py-4 sm:py-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Peta Lokasi</h3>
                    <p class="text-[10px] sm:text-xs text-slate-400 font-medium mt-0.5">Lokasi Anda dan {{ $schoolSettings['name'] }}</p>
                </div>
                <div id="distance-badge" class="hidden px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-[9px] sm:text-xs font-black uppercase tracking-widest border w-fit">
                    <span id="distance-text"></span>
                </div>
            </div>
            <div id="attendance-map" style="height: 300px; md:height: 400px; width: 100%;"></div>
        </div>

        <!-- Attendance Action Card -->
        <div class="lg:col-span-2 bg-white rounded-2xl sm:rounded-[2rem] border border-slate-100 shadow-sm p-6 sm:p-8 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-100">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-extrabold text-slate-900">Absensi GPS</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400 font-bold">Radius: {{ $schoolSettings['radius'] }}m</p>
                    </div>
                </div>

                <!-- Location Status -->
                <div id="location-status" class="mb-6">
                    <div class="p-4 bg-amber-50 border border-amber-100 rounded-2xl flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center animate-pulse">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] sm:text-xs font-black text-amber-700 uppercase tracking-widest leading-none">Mencari Lokasi...</p>
                            <p class="text-[9px] sm:text-[10px] text-amber-500 font-medium mt-1 leading-none">Mengakses GPS perangkat Anda</p>
                        </div>
                    </div>
                </div>

                <!-- Coordinate Info -->
                <div id="coord-info" class="hidden space-y-3 mb-6">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Lokasi Anda</p>
                        <p class="text-[10px] sm:text-xs font-mono font-bold text-slate-600" id="user-coords">-</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Lokasi Sekolah</p>
                        <p class="text-[10px] sm:text-xs font-mono font-bold text-slate-600">{{ $schoolSettings['latitude'] }}, {{ $schoolSettings['longitude'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Work Hours Info -->
            <div class="grid grid-cols-2 gap-3 mb-6">
                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 text-center">
                    <p class="text-[9px] sm:text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Jam Masuk</p>
                    <p class="text-xs sm:text-sm font-black text-emerald-700">{{ $workHours['min_check_in'] }} WITA</p>
                </div>
                <div class="p-3 bg-rose-50 rounded-xl border border-rose-100 text-center">
                    <p class="text-[9px] sm:text-[10px] font-black text-rose-500 uppercase tracking-widest mb-1">Jam Pulang</p>
                    <p class="text-xs sm:text-sm font-black text-rose-700">{{ $workHours['min_check_out'] }} WITA</p>
                </div>
            </div>

            <!-- Attendance Form -->
            @if(!$todayAttendance)
                {{-- STATE 1: Belum check-in --}}
                <form id="attendance-form" action="{{ route('teacher.attendance.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="latitude" id="input-latitude">
                    <input type="hidden" name="longitude" id="input-longitude">
                    <button type="submit" id="btn-absen" disabled
                        class="w-full py-3.5 sm:py-4 bg-slate-300 text-white rounded-2xl font-bold shadow-lg transition-all flex items-center justify-center gap-2 cursor-not-allowed text-sm sm:text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        <span id="btn-text">Menunggu Lokasi...</span>
                    </button>
                </form>
            @elseif(!$todayAttendance->check_out_time)
                {{-- STATE 2: Sudah check-in, belum check-out --}}
                <div class="space-y-4">
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center justify-between">
                        <div class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Masuk</div>
                        <div class="text-base sm:text-lg font-black text-emerald-800">{{ Carbon\Carbon::parse($todayAttendance->check_in_time)->format('H:i') }}</div>
                    </div>

                    <form id="checkout-form" action="{{ route('teacher.attendance.checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="latitude" id="input-latitude">
                        <input type="hidden" name="longitude" id="input-longitude">
                        <button type="submit" id="btn-absen" disabled
                            class="w-full py-3.5 sm:py-4 bg-slate-300 text-white rounded-2xl font-bold shadow-lg transition-all flex items-center justify-center gap-2 cursor-not-allowed text-sm sm:text-base">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span id="btn-text">Menunggu Lokasi...</span>
                        </button>
                    </form>
                </div>
            @else
                {{-- STATE 3: Sudah check-in dan check-out --}}
                <div class="space-y-3">
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center justify-between">
                        <div class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Masuk</div>
                        <div class="text-base sm:text-lg font-black text-emerald-800">{{ Carbon\Carbon::parse($todayAttendance->check_in_time)->format('H:i') }}</div>
                    </div>
                    <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100 flex items-center justify-between">
                        <div class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Pulang</div>
                        <div class="text-base sm:text-lg font-black text-indigo-800">{{ Carbon\Carbon::parse($todayAttendance->check_out_time)->format('H:i') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Attendance History Table -->
    <div class="bg-white rounded-2xl sm:rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 sm:px-8 py-4 sm:py-6 border-b border-slate-100">
            <h3 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Riwayat Kehadiran</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-4 sm:px-8 py-4 text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-4 sm:px-8 py-4 text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest">Masuk/Pulang</th>
                        <th class="px-4 sm:px-8 py-4 text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-4 sm:px-8 py-4 text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest hidden md:table-cell">Lokasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 font-medium text-slate-700">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-4 sm:px-8 py-4 sm:py-5">
                                <div class="flex items-center gap-3">
                                    <div class="hidden sm:flex w-8 h-8 rounded-lg bg-slate-50 items-center justify-center text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-sm sm:text-base">{{ Carbon\Carbon::parse($attendance->date)->translatedFormat('d M Y') }}</span>
                                        <span class="sm:hidden text-[10px] text-slate-400 tracking-tight uppercase">{{ Carbon\Carbon::parse($attendance->date)->translatedFormat('l') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-8 py-4 sm:py-5 text-xs sm:text-sm font-mono text-slate-500">
                                <div class="flex flex-col sm:flex-row sm:gap-2">
                                    <span class="text-emerald-600 font-bold sm:font-medium">{{ Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') }}</span>
                                    <span class="hidden sm:inline text-slate-300">/</span>
                                    <span class="text-rose-600 font-bold sm:font-medium">
                                        @if($attendance->check_out_time)
                                            {{ Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') }}
                                        @else
                                            —
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-8 py-4 sm:py-5">
                                @switch($attendance->status)
                                    @case('present')
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 sm:px-3 sm:py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] sm:text-[10px] font-black uppercase tracking-widest border border-emerald-100">Hadir</span>
                                        @break
                                    @case('sick')
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 sm:px-3 sm:py-1 bg-amber-50 text-amber-600 rounded-lg text-[9px] sm:text-[10px] font-black uppercase tracking-widest border border-amber-100">Sakit</span>
                                        @break
                                    @case('permission')
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 sm:px-3 sm:py-1 bg-blue-50 text-blue-600 rounded-lg text-[9px] sm:text-[10px] font-black uppercase tracking-widest border border-blue-100">Izin</span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 sm:px-3 sm:py-1 bg-rose-50 text-rose-600 rounded-lg text-[9px] sm:text-[10px] font-black uppercase tracking-widest border border-rose-100">Alfa</span>
                                @endswitch
                            </td>
                            <td class="px-4 sm:px-8 py-4 sm:py-5 hidden md:table-cell">
                                @if($attendance->latitude && $attendance->longitude)
                                    <span class="text-xs font-mono text-slate-400">{{ $attendance->latitude }}, {{ $attendance->longitude }}</span>
                                @else
                                    <span class="text-xs text-slate-300 italic">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-medium italic">
                                Belum ada catatan kehadiran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($attendances->hasPages())
            <div class="px-6 sm:px-8 py-4 sm:py-6 bg-slate-50/50 border-t border-slate-50">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const schoolLat = {{ $schoolSettings['latitude'] }};
    const schoolLng = {{ $schoolSettings['longitude'] }};
    const schoolRadius = {{ $schoolSettings['radius'] }};
    const schoolName = @json($schoolSettings['name']);

    // Initialize map centered on school
    const map = L.map('attendance-map').setView([schoolLat, schoolLng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    // School marker (red)
    const schoolIcon = L.divIcon({
        html: `<div style="background: #dc2626; width: 32px; height: 32px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 4px 12px rgba(220,38,38,0.4); display: flex; align-items: center; justify-content: center;">
                 <svg style="transform: rotate(45deg); width: 16px; height: 16px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
               </div>`,
        className: '',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32],
    });

    L.marker([schoolLat, schoolLng], { icon: schoolIcon })
        .addTo(map)
        .bindPopup(`<strong>${schoolName}</strong><br><small>Lokasi Sekolah</small>`);

    // School radius circle
    L.circle([schoolLat, schoolLng], {
        radius: schoolRadius,
        color: '#dc2626',
        fillColor: '#dc2626',
        fillOpacity: 0.08,
        weight: 2,
        dashArray: '8 4',
    }).addTo(map);

    // User marker (blue) - will be updated
    const userIcon = L.divIcon({
        html: `<div style="background: #2563eb; width: 28px; height: 28px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 12px rgba(37,99,235,0.4); position: relative;">
                 <div style="position: absolute; inset: 0; border-radius: 50%; border: 2px solid rgba(37,99,235,0.3); animation: ping 1.5s cubic-bezier(0,0,0.2,1) infinite;"></div>
               </div>`,
        className: '',
        iconSize: [28, 28],
        iconAnchor: [14, 14],
        popupAnchor: [0, -14],
    });

    let userMarker = null;

    // Haversine distance calculation
    function haversine(lat1, lng1, lat2, lng2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLng/2) * Math.sin(dLng/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    function updateLocationUI(lat, lng) {
        const distance = haversine(lat, lng, schoolLat, schoolLng);
        const isInRange = distance <= schoolRadius;

        // Update coordinate info
        document.getElementById('coord-info').classList.remove('hidden');
        document.getElementById('user-coords').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

        // Update distance badge
        const badge = document.getElementById('distance-badge');
        const distText = document.getElementById('distance-text');
        badge.classList.remove('hidden');
        
        if (isInRange) {
            badge.className = 'px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest border bg-emerald-50 text-emerald-600 border-emerald-100';
            distText.textContent = `✓ ${Math.round(distance)}m - Dalam Radius`;
        } else {
            badge.className = 'px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest border bg-rose-50 text-rose-600 border-rose-100';
            distText.textContent = `✗ ${Math.round(distance)}m - Di Luar Radius`;
        }

        // Update location status
        const statusEl = document.getElementById('location-status');
        if (isInRange) {
            statusEl.innerHTML = `
                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-emerald-700 uppercase tracking-widest">Dalam Area Sekolah</p>
                        <p class="text-[10px] text-emerald-500 font-medium mt-0.5">Jarak: ${Math.round(distance)}m (maks ${schoolRadius}m)</p>
                    </div>
                </div>`;
        } else {
            statusEl.innerHTML = `
                <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-rose-700 uppercase tracking-widest">Di Luar Area Sekolah</p>
                        <p class="text-[10px] text-rose-500 font-medium mt-0.5">Jarak: ${Math.round(distance)}m (maks ${schoolRadius}m)</p>
                    </div>
                </div>`;
        }

        // Update button
        const btn = document.getElementById('btn-absen');
        const btnText = document.getElementById('btn-text');
        const isCheckout = !!document.getElementById('checkout-form');
        const actionLabel = isCheckout ? 'Absen Pulang' : 'Absen Masuk';
        if (btn) {
            if (isInRange) {
                btn.disabled = false;
                btn.className = 'w-full py-4 bg-emerald-600 text-white rounded-2xl font-bold shadow-lg shadow-emerald-100 hover:bg-emerald-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer';
                btnText.textContent = actionLabel;
            } else {
                btn.disabled = true;
                btn.className = 'w-full py-4 bg-slate-300 text-white rounded-2xl font-bold shadow-lg transition-all flex items-center justify-center gap-2 cursor-not-allowed';
                btnText.textContent = 'Di Luar Radius Sekolah';
            }
        }

        // Update hidden inputs
        const latInput = document.getElementById('input-latitude');
        const lngInput = document.getElementById('input-longitude');
        if (latInput) latInput.value = lat;
        if (lngInput) lngInput.value = lng;
    }

    // Get user location
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                if (userMarker) {
                    userMarker.setLatLng([lat, lng]);
                } else {
                    userMarker = L.marker([lat, lng], { icon: userIcon })
                        .addTo(map)
                        .bindPopup('<strong>Lokasi Anda</strong>');
                    
                    // Fit bounds to show both markers
                    const bounds = L.latLngBounds([
                        [schoolLat, schoolLng],
                        [lat, lng]
                    ]);
                    map.fitBounds(bounds, { padding: [60, 60] });
                }

                updateLocationUI(lat, lng);
            },
            function(error) {
                const statusEl = document.getElementById('location-status');
                let message = 'Tidak dapat mengakses lokasi.';
                if (error.code === 1) message = 'Akses lokasi ditolak. Harap izinkan akses GPS di pengaturan browser Anda.';
                if (error.code === 2) message = 'Lokasi tidak tersedia. Pastikan GPS perangkat Anda aktif.';
                if (error.code === 3) message = 'Waktu permintaan lokasi habis. Silakan coba lagi.';

                statusEl.innerHTML = `
                    <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-black text-rose-700 uppercase tracking-widest">Error GPS</p>
                            <p class="text-[10px] text-rose-500 font-medium mt-0.5">${message}</p>
                        </div>
                    </div>`;
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 5000 }
        );
    }
});
</script>

<style>
@keyframes ping {
    75%, 100% { transform: scale(2.5); opacity: 0; }
}
</style>
@endsection
