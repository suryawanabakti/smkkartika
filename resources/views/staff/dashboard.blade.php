@extends('layouts.app')

@section('content')
<div class="space-y-10 pb-12 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">HALO, <span class="text-emerald-600 uppercase">{{ explode(' ', Auth::user()->name)[0] }}</span>! 👋</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1 underline decoration-rose-500/30 underline-offset-8">Selamat datang di portal personel SMK Kartika</p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="p-3 bg-white rounded-2xl border border-slate-100 shadow-[0_10px_30px_-15px_rgba(0,0,0,0.05)] flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Hari Ini</p>
                    <p class="text-xs font-black text-slate-700 tracking-tight uppercase">{{ now()->translatedFormat('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats & Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Self Attendance Card -->
        <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.02)] flex flex-col justify-between h-full relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full -mr-16 -mt-16 transition-all duration-500 group-hover:bg-emerald-500/10"></div>
            
            <div class="relative">
                <div class="flex items-start justify-between mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-xl shadow-emerald-500/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    @if($myAttendance && $myAttendance->check_out_time)
                        <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-100">Selesai</span>
                    @elseif($myAttendance)
                        <span class="px-4 py-1.5 bg-amber-50 text-amber-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-amber-100 animate-pulse">Aktif</span>
                    @else
                        <span class="px-4 py-1.5 bg-rose-50 text-rose-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-rose-100">Belum Ada</span>
                    @endif
                </div>
                
                <h3 class="text-2xl font-black text-slate-900 mb-2 tracking-tight uppercase">Kehadiran <span class="text-emerald-600">SAYA</span></h3>
                <p class="text-slate-400 text-sm font-bold leading-relaxed mb-8">Pencatatan waktu kerja harian berbasis lokasi GPS sekolah.</p>
            </div>
            
            <div class="relative pt-6 border-t border-slate-50 mt-auto">
                @if(!$myAttendance)
                    <form action="{{ route('staff.attendance.store') }}" method="POST" id="dashboard-attendance-form">
                        @csrf
                        <input type="hidden" name="latitude" id="dash-latitude">
                        <input type="hidden" name="longitude" id="dash-longitude">
                        
                        <div id="dash-location-info" class="mb-5 p-4 bg-slate-50 border border-slate-100 rounded-2xl flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center">
                                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Mencari Koordinat...</span>
                        </div>

                        <button type="submit" id="dash-btn-absen" disabled
                            class="w-full py-5 bg-slate-100 text-slate-400 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2 cursor-not-allowed border border-slate-200">
                            Masuk Sekarang
                        </button>
                    </form>

                    <script>
                    (function() {
                        const schoolLat = {{ $schoolSettings['latitude'] }};
                        const schoolLng = {{ $schoolSettings['longitude'] }};
                        const schoolRadius = {{ $schoolSettings['radius'] }};

                        function haversine(lat1, lng1, lat2, lng2) {
                            const R = 6371000;
                            const dLat = (lat2 - lat1) * Math.PI / 180;
                            const dLng = (lng2 - lng1) * Math.PI / 180;
                            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                                      Math.sin(dLng/2) * Math.sin(dLng/2);
                            return 6371000 * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                        }

                        if (navigator.geolocation) {
                            navigator.geolocation.watchPosition(function(pos) {
                                const lat = pos.coords.latitude;
                                const lng = pos.coords.longitude;
                                const dist = haversine(lat, lng, schoolLat, schoolLng);
                                const inRange = dist <= schoolRadius;

                                document.getElementById('dash-latitude').value = lat;
                                document.getElementById('dash-longitude').value = lng;

                                const info = document.getElementById('dash-location-info');
                                const btn = document.getElementById('dash-btn-absen');

                                if (inRange) {
                                    info.classList.replace('bg-slate-50', 'bg-emerald-50');
                                    info.classList.replace('border-slate-100', 'border-emerald-100');
                                    info.innerHTML = `<div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div><span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">RADIUS OK (${Math.round(dist)}m)</span>`;
                                    btn.disabled = false;
                                    btn.className = 'group relative w-full py-5 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2 overflow-hidden shadow-xl shadow-emerald-500/20';
                                    btn.innerHTML = `<span class="absolute inset-0 w-full h-full bg-gradient-to-r from-emerald-400/20 to-rose-400/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700 ease-in-out"></span><span class="relative">Masuk Sekarang</span>`;
                                } else {
                                    info.classList.replace('bg-slate-50', 'bg-rose-50');
                                    info.classList.replace('border-slate-100', 'border-rose-100');
                                    info.innerHTML = `<div class="w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg></div><span class="text-[10px] font-black text-rose-600 uppercase tracking-widest">DI LUAR RADIUS (${Math.round(dist)}m)</span>`;
                                    btn.disabled = true;
                                    btn.className = 'w-full py-5 bg-slate-100 text-slate-300 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2 cursor-not-allowed border border-slate-200';
                                    btn.textContent = 'Di Luar Area Sekolah';
                                }
                            }, function(err) {
                                const info = document.getElementById('dash-location-info');
                                info.innerHTML = `<div class="w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><span class="text-[10px] font-black text-rose-600 uppercase tracking-widest">GPS ERROR</span>`;
                            }, { enableHighAccuracy: true, timeout: 15000, maximumAge: 5000 });
                        }
                    })();
                    </script>
                @elseif(!$myAttendance->check_out_time)
                    {{-- Checked in, not checked out --}}
                    <div class="space-y-4">
                        <div class="p-5 bg-emerald-50/50 rounded-2xl border border-emerald-100 flex items-center justify-between">
                            <div class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Jam Masuk</div>
                            <div class="text-sm font-black text-slate-800 tracking-tight">{{ Carbon\Carbon::parse($myAttendance->check_in_time)->format('H:i') }}</div>
                        </div>

                        <form action="{{ route('staff.attendance.checkout') }}" method="POST" id="dashboard-checkout-form">
                            @csrf
                            <input type="hidden" name="latitude" id="dash-latitude">
                            <input type="hidden" name="longitude" id="dash-longitude">

                            <div id="dash-location-info" class="mb-4 p-4 bg-slate-50 border border-slate-100 rounded-2xl flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center">
                                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                </div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Mencari Koordinat...</span>
                            </div>

                            <button type="submit" id="dash-btn-absen" disabled
                                class="w-full py-5 bg-slate-100 text-slate-300 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2 cursor-not-allowed border border-slate-200">
                                Pulang Sekarang
                            </button>
                        </form>
                    </div>

                    <script>
                    (function() {
                        const schoolLat = {{ $schoolSettings['latitude'] }};
                        const schoolLng = {{ $schoolSettings['longitude'] }};
                        const schoolRadius = {{ $schoolSettings['radius'] }};

                        function haversine(lat1, lng1, lat2, lng2) {
                            const R = 6371000;
                            const dLat = (lat2 - lat1) * Math.PI / 180;
                            const dLng = (lng2 - lng1) * Math.PI / 180;
                            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                                      Math.sin(dLng/2) * Math.sin(dLng/2);
                            return 6371000 * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                        }

                        if (navigator.geolocation) {
                            navigator.geolocation.watchPosition(function(pos) {
                                const lat = pos.coords.latitude;
                                const lng = pos.coords.longitude;
                                const dist = haversine(lat, lng, schoolLat, schoolLng);
                                const inRange = dist <= schoolRadius;

                                document.getElementById('dash-latitude').value = lat;
                                document.getElementById('dash-longitude').value = lng;

                                const info = document.getElementById('dash-location-info');
                                const btn = document.getElementById('dash-btn-absen');

                                if (inRange) {
                                    info.classList.replace('bg-slate-50', 'bg-emerald-50');
                                    info.classList.replace('border-slate-100', 'border-emerald-100');
                                    info.innerHTML = `<div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div><span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">RADIUS OK (${Math.round(dist)}m)</span>`;
                                    btn.disabled = false;
                                    btn.className = 'group relative w-full py-5 bg-rose-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2 overflow-hidden shadow-xl shadow-rose-500/20';
                                    btn.innerHTML = `<span class="absolute inset-0 w-full h-full bg-gradient-to-r from-rose-400/20 to-emerald-400/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700 ease-in-out"></span><span class="relative">Pulang Sekarang</span>`;
                                } else {
                                    info.classList.replace('bg-slate-50', 'bg-rose-50');
                                    info.classList.replace('border-slate-100', 'border-rose-100');
                                    info.innerHTML = `<div class="w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg></div><span class="text-[10px] font-black text-rose-600 uppercase tracking-widest">DI LUAR RADIUS (${Math.round(dist)}m)</span>`;
                                    btn.disabled = true;
                                    btn.className = 'w-full py-5 bg-slate-100 text-slate-300 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2 cursor-not-allowed border border-slate-200';
                                    btn.textContent = 'Di Luar Area Sekolah';
                                }
                            }, function(err) {
                                const info = document.getElementById('dash-location-info');
                                info.innerHTML = `<div class="w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><span class="text-[10px] font-black text-rose-600 uppercase tracking-widest">GPS ERROR</span>`;
                            }, { enableHighAccuracy: true, timeout: 15000, maximumAge: 5000 });
                        }
                    })();
                    </script>
                @else
                    {{-- Fully completed --}}
                    <div class="space-y-4">
                        <div class="p-5 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center justify-between">
                            <div class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Masuk Hari Ini</div>
                            <div class="text-sm font-black text-slate-800 tracking-tight tracking-tight uppercase">{{ Carbon\Carbon::parse($myAttendance->check_in_time)->format('H:i') }}</div>
                        </div>
                        <div class="p-5 bg-rose-50 rounded-2xl border border-rose-100 flex items-center justify-between">
                            <div class="text-[10px] font-black text-rose-600 uppercase tracking-widest">Pulang Hari Ini</div>
                            <div class="text-sm font-black text-slate-800 tracking-tight tracking-tight uppercase">{{ Carbon\Carbon::parse($myAttendance->check_out_time)->format('H:i') }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Info Card -->
        <div class="lg:col-span-2 bg-slate-900 p-10 rounded-[2.5rem] text-white relative overflow-hidden group min-h-[400px]">
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-emerald-500/20 rounded-full -mr-48 -mt-48 blur-3xl group-hover:bg-emerald-500/30 transition-all duration-700"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-rose-500/10 rounded-full -ml-32 -mb-32 blur-3xl group-hover:bg-rose-500/20 transition-all duration-700"></div>
            
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div>
                    <div class="flex items-center gap-4 mb-10">
                        <div class="px-4 py-2 bg-white/10 backdrop-blur-md rounded-xl text-[10px] font-black uppercase tracking-[0.2em] border border-white/10">SMK KARTIKA</div>
                        <span class="w-1 h-1 bg-emerald-400 rounded-full"></span>
                        <span class="text-white/80 font-black text-[10px] uppercase tracking-widest">Dashboard Staff</span>
                    </div>
                    <h3 class="text-4xl font-black mb-10 tracking-tight leading-[1.1]">PORTAL <span class="text-emerald-400">KEHADIRAN</span> PERSONEL TERPADU</h3>
                </div>

                <div class="space-y-6">
                    <p class="text-white/50 text-sm font-bold leading-relaxed max-w-lg">
                        Sistem absensi otomatis menggunakan validasi geofencing. Pastikan koneksi GPS aktif untuk merekam waktu kerja secara akurat sesuai peraturan sekolah.
                    </p>
                    <div class="flex items-center gap-6 pt-10">
                        <a href="{{ route('staff.attendance.index') }}" class="group px-8 py-4 bg-white text-slate-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-50 transition-all flex items-center gap-3">
                            Riwayat Absensi
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
