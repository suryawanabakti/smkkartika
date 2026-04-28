@extends('layouts.app')

@section('content')
    <div class="space-y-10 pb-12">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Halo,
                    {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
                <p class="text-slate-500 font-medium mt-1">Selamat datang di dashboard guru Anda.</p>
            </div>

            <div class="flex items-center gap-4">
                <div class="p-3 bg-white rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Hari Ini</p>
                        <p class="text-sm font-bold text-slate-700">{{ now()->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats & Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Self Attendance Card -->
            <div
                class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm shadow-slate-100/50 flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between mb-6">
                        <div
                            class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        @if ($myAttendance && $myAttendance->check_out_time)
                            <span
                                class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100">Selesai</span>
                        @elseif($myAttendance)
                            <span
                                class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-amber-100">Sudah
                                Masuk</span>
                        @else
                            <span
                                class="px-3 py-1 bg-rose-50 text-rose-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-rose-100">Belum
                                Absen</span>
                        @endif
                    </div>

                    <h3 class="text-xl font-extrabold text-slate-900 mb-2">Kehadiran Saya</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">Pastikan absen masuk dan absen pulang Anda
                        tercatat setiap hari.</p>
                </div>

                <div class="mt-8">
                    @if (!$myAttendance)
                        <form action="{{ route('teacher.attendance.store') }}" method="POST"
                            id="dashboard-attendance-form">
                            @csrf
                            <input type="hidden" name="latitude" id="dash-latitude">
                            <input type="hidden" name="longitude" id="dash-longitude">

                            <div id="dash-location-info"
                                class="mb-4 p-3 bg-amber-50 border border-amber-100 rounded-xl flex items-center gap-2">
                                <div
                                    class="w-5 h-5 rounded-full bg-amber-200 text-amber-600 flex items-center justify-center animate-pulse">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                </div>
                                <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Mencari Lokasi
                                    GPS...</span>
                            </div>

                            <button type="submit" id="dash-btn-absen" disabled
                                class="w-full py-4 bg-slate-300 text-white rounded-2xl font-bold shadow-lg transition-all flex items-center justify-center gap-2 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span id="dash-btn-text">Menunggu Lokasi...</span>
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
                                    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                                        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                                        Math.sin(dLng / 2) * Math.sin(dLng / 2);
                                    return 6371000 * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
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
                                        const txt = document.getElementById('dash-btn-text');

                                        if (inRange) {
                                            info.className =
                                                'mb-4 p-3 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center gap-2';
                                            info.innerHTML =
                                                `<div class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div><span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Dalam Radius (${Math.round(dist)}m)</span>`;
                                            btn.disabled = false;
                                            btn.className =
                                                'w-full py-4 bg-emerald-600 text-white rounded-2xl font-bold shadow-lg shadow-emerald-100 hover:bg-emerald-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer';
                                            txt.textContent = 'Absen Sekarang';
                                        } else {
                                            info.className =
                                                'mb-4 p-3 bg-rose-50 border border-rose-100 rounded-xl flex items-center gap-2';
                                            info.innerHTML =
                                                `<div class="w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></div><span class="text-[10px] font-black text-rose-600 uppercase tracking-widest">Di Luar Radius (${Math.round(dist)}m)</span>`;
                                            btn.disabled = true;
                                            btn.className =
                                                'w-full py-4 bg-slate-300 text-white rounded-2xl font-bold shadow-lg transition-all flex items-center justify-center gap-2 cursor-not-allowed';
                                            txt.textContent = 'Di Luar Radius Sekolah';
                                        }
                                    }, function(err) {
                                        const info = document.getElementById('dash-location-info');
                                        info.className =
                                            'mb-4 p-3 bg-rose-50 border border-rose-100 rounded-xl flex items-center gap-2';
                                        info.innerHTML =
                                            `<div class="w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><span class="text-[10px] font-black text-rose-600 uppercase tracking-widest">GPS Error</span>`;
                                    }, {
                                        enableHighAccuracy: true,
                                        timeout: 15000,
                                        maximumAge: 5000
                                    });
                                }
                            })();
                        </script>
                    @elseif(!$myAttendance->check_out_time)
                        {{-- Checked in, not checked out --}}
                        <div class="space-y-3">
                            <div
                                class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 flex items-center justify-between">
                                <div class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Masuk</div>
                                <div class="text-sm font-black text-emerald-800">
                                    {{ Carbon\Carbon::parse($myAttendance->check_in_time)->format('H:i') }}</div>
                            </div>

                            <form action="{{ route('teacher.attendance.checkout') }}" method="POST"
                                id="dashboard-checkout-form">
                                @csrf
                                <input type="hidden" name="latitude" id="dash-latitude">
                                <input type="hidden" name="longitude" id="dash-longitude">

                                <div id="dash-location-info"
                                    class="mb-3 p-3 bg-amber-50 border border-amber-100 rounded-xl flex items-center gap-2">
                                    <div
                                        class="w-5 h-5 rounded-full bg-amber-200 text-amber-600 flex items-center justify-center animate-pulse">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Mencari
                                        Lokasi GPS...</span>
                                </div>

                                <button type="submit" id="dash-btn-absen" disabled
                                    class="w-full py-3 bg-slate-300 text-white rounded-2xl font-bold shadow-lg transition-all flex items-center justify-center gap-2 cursor-not-allowed text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span id="dash-btn-text">Menunggu Lokasi...</span>
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
                                    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                                        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                                        Math.sin(dLng / 2) * Math.sin(dLng / 2);
                                    return 6371000 * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
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
                                        const txt = document.getElementById('dash-btn-text');

                                        if (inRange) {
                                            info.className =
                                                'mb-3 p-3 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center gap-2';
                                            info.innerHTML =
                                                `<div class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div><span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Dalam Radius (${Math.round(dist)}m)</span>`;
                                            btn.disabled = false;
                                            btn.className =
                                                'w-full py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer text-sm';
                                            txt.textContent = 'Absen Pulang';
                                        } else {
                                            info.className =
                                                'mb-3 p-3 bg-rose-50 border border-rose-100 rounded-xl flex items-center gap-2';
                                            info.innerHTML =
                                                `<div class="w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></div><span class="text-[10px] font-black text-rose-600 uppercase tracking-widest">Di Luar Radius (${Math.round(dist)}m)</span>`;
                                            btn.disabled = true;
                                            btn.className =
                                                'w-full py-3 bg-slate-300 text-white rounded-2xl font-bold shadow-lg transition-all flex items-center justify-center gap-2 cursor-not-allowed text-sm';
                                            txt.textContent = 'Di Luar Radius Sekolah';
                                        }
                                    }, function(err) {
                                        const info = document.getElementById('dash-location-info');
                                        info.className =
                                            'mb-3 p-3 bg-rose-50 border border-rose-100 rounded-xl flex items-center gap-2';
                                        info.innerHTML =
                                            `<div class="w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><span class="text-[10px] font-black text-rose-600 uppercase tracking-widest">GPS Error</span>`;
                                    }, {
                                        enableHighAccuracy: true,
                                        timeout: 15000,
                                        maximumAge: 5000
                                    });
                                }
                            })();
                        </script>
                    @else
                        {{-- Fully completed --}}
                        <div class="space-y-3">
                            <div
                                class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 flex items-center justify-between">
                                <div class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Masuk</div>
                                <div class="text-sm font-black text-emerald-800">
                                    {{ Carbon\Carbon::parse($myAttendance->check_in_time)->format('H:i') }}</div>
                            </div>
                            <div
                                class="p-3 bg-indigo-50 rounded-xl border border-indigo-100 flex items-center justify-between">
                                <div class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Pulang</div>
                                <div class="text-sm font-black text-indigo-800">
                                    {{ Carbon\Carbon::parse($myAttendance->check_out_time)->format('H:i') }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Personal Attendance Chart Card -->
            <div class="lg:col-span-2 bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm shadow-slate-100/50 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:bg-indigo-500/10 transition-all duration-500"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-900">Grafik Kehadiranku</h3>
                            <p class="text-xs font-medium text-slate-500 mt-1">Status kehadiran 7 hari terakhir Anda.</p>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-widest border border-emerald-100 shadow-sm">Live Trend</span>
                        </div>
                    </div>

                    <div id="personal-attendance-chart" class="min-h-[280px] w-full"></div>
                </div>
            </div>
        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const options = {
                series: [{
                    name: 'Status Kehadiran',
                    data: {!! json_encode($myChartData['values']) !!}
                }],
                chart: {
                    type: 'area',
                    height: 280,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    fontFamily: 'Inter, sans-serif'
                },
                colors: ['#6366f1'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 4
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [20, 100]
                    }
                },
                labels: {!! json_encode($myChartData['labels']) !!},
                xaxis: {
                    tooltip: {
                        enabled: false
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontWeight: 600,
                            fontSize: '11px'
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 1.2,
                    tickAmount: 2,
                    labels: {
                        formatter: function(val) {
                            if (val >= 1) return "Hadir";
                            if (val >= 0.5) return "S/I";
                            return "Alfa";
                        },
                        style: {
                            colors: '#64748b',
                            fontWeight: 700,
                            fontSize: '10px'
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 6,
                    padding: {
                        left: 20,
                        right: 20
                    }
                },
                tooltip: {
                    theme: 'light',
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const statuses = {!! json_encode($myChartData['statuses']) !!};
                        const status = statuses[dataPointIndex];
                        const date = w.globals.labels[dataPointIndex];
                        return '<div class="px-4 py-2 bg-white shadow-xl rounded-xl border border-slate-100">' +
                            '<div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">' +
                            date + '</div>' +
                            '<div class="flex items-center gap-2">' +
                            '<div class="w-2 h-2 rounded-full ' + (status === 'Hadir' ? 'bg-emerald-500' :
                                (status === 'Sakit' || status === 'Izin' ? 'bg-amber-500' :
                                    'bg-rose-500')) + '"></div>' +
                            '<div class="text-sm font-black text-slate-700">' + status + '</div>' +
                            '</div>' +
                            '</div>';
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#personal-attendance-chart"), options);
            chart.render();
        });
    </script>
@endsection
