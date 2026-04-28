@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-5">
        <!-- Students -->
        <div class="p-4 sm:p-5 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center space-x-3">
            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg shrink-0">
                <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-9-3.833M18.732 7.961a5 5 0 11-9.047-4.461 5 5 0 019.047 4.461z"/></svg>
            </div>
            <div class="min-w-0 text-center">
                <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wider">Siswa</p>
                <p class="text-xl font-bold text-gray-800">{{ $stats['total_students'] }}</p>
            </div>
        </div>

        <!-- Teachers -->
        <div class="p-4 sm:p-5 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center space-x-3">
            <div class="p-2 bg-purple-100 text-purple-600 rounded-lg shrink-0">
                <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div class="min-w-0 text-center">
                <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wider">Guru</p>
                <p class="text-xl font-bold text-gray-800">{{ $stats['total_teachers'] }}</p>
            </div>
        </div>

        <!-- Staffs -->
        <div class="p-4 sm:p-5 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center space-x-3">
            <div class="p-2 bg-amber-100 text-amber-600 rounded-lg shrink-0">
                <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div class="min-w-0 text-center">
                <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wider">Staff</p>
                <p class="text-xl font-bold text-gray-800">{{ $stats['total_staffs'] }}</p>
            </div>
        </div>

        <!-- Classes -->
        <div class="p-4 sm:p-5 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center space-x-3">
            <div class="p-2 bg-green-100 text-green-600 rounded-lg shrink-0">
                <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div class="min-w-0 text-center">
                <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wider">Kelas</p>
                <p class="text-xl font-bold text-gray-800">{{ $stats['total_classes'] }}</p>
            </div>
        </div>

        <!-- Majors -->
        <div class="p-4 sm:p-5 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center space-x-3">
            <div class="p-2 bg-rose-100 text-rose-600 rounded-lg shrink-0">
                <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
            </div>
            <div class="min-w-0 text-center">
                <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wider">Jurusan</p>
                <p class="text-xl font-bold text-gray-800">{{ $stats['total_majors'] }}</p>
            </div>
        </div>
    </div>

    <!-- Attendance Trend Chart -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-black text-gray-800">Tren Kehadiran</h3>
                <p class="text-xs text-gray-500 font-medium">Statistik kehadiran 7 hari terakhir</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
                    <span class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Siswa</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-indigo-500 rounded-full"></span>
                    <span class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Pegawai</span>
                </div>
            </div>
        </div>
        <div class="h-[300px] relative">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($attendanceTrend['labels']) !!},
                    datasets: [
{{-- {
                            label: 'Siswa',
                            data: {!! json_encode($attendanceTrend['students']) !!},
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 4,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            tension: 0.4,
                            fill: true
                        }, --}}
                        {
                            label: 'Pegawai',
                            data: {!! json_encode($attendanceTrend['personnel']) !!},
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            borderWidth: 4,
                            pointBackgroundColor: '#6366f1',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { size: 12, weight: 'bold', family: 'Inter' },
                            bodyFont: { size: 12, family: 'Inter' },
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                precision: 0,
                                font: { family: 'Inter', size: 11, weight: 'bold' },
                                color: '#94a3b8'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { family: 'Inter', size: 11, weight: 'bold' },
                                color: '#94a3b8'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        });
    </script>

    <!-- Attendance Widget & Realtime Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Self Attendance Widget (Admin) -->
        <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-100 flex flex-col justify-between overflow-hidden relative group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl group-hover:bg-white/20 transition-all duration-500"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-100">Presensi Admin</span>
                    @if($myAttendance && $myAttendance->check_out_time)
                        <span class="px-2 py-0.5 bg-emerald-500/20 rounded-lg text-[8px] font-black uppercase tracking-widest border border-white/10">Selesai Berkerja</span>
                    @elseif($myAttendance)
                        <span class="px-2 py-0.5 bg-amber-500/20 rounded-lg text-[8px] font-black uppercase tracking-widest border border-white/10">Sedang Berkerja</span>
                    @endif
                </div>
                <h3 class="text-xl font-black mb-2">Halo, Admin!</h3>
                <p class="text-xs text-indigo-100 font-medium mb-6">Jangan lupa catat kehadiran Anda hari ini.</p>
            </div>

            <div class="relative z-10">
                @if(!$myAttendance)
                    <form action="{{ route('admin.attendance.store') }}" method="POST" id="admin-attendance-form">
                        @csrf
                        <input type="hidden" name="latitude" id="admin-lat">
                        <input type="hidden" name="longitude" id="admin-lng">
                        <button type="submit" id="admin-btn-absen" disabled
                            class="w-full py-3 bg-white text-indigo-600 rounded-xl font-black text-xs shadow-xl transition-all flex items-center justify-center gap-2 cursor-not-allowed opacity-50">
                            MENCARI LOKASI...
                        </button>
                    </form>
                @elseif(!$myAttendance->check_out_time)
                    <div class="space-y-2">
                        <div class="px-3 py-2 bg-white/10 rounded-xl flex items-center justify-between border border-white/5">
                            <span class="text-[10px] font-bold text-indigo-100 uppercase">Waktu Masuk</span>
                            <span class="font-black">{{ Carbon\Carbon::parse($myAttendance->check_in_time)->format('H:i') }}</span>
                        </div>
                        <form action="{{ route('admin.attendance.checkout') }}" method="POST" id="admin-attendance-form">
                            @csrf
                            <input type="hidden" name="latitude" id="admin-lat">
                            <input type="hidden" name="longitude" id="admin-lng">
                            <button type="submit" id="admin-btn-absen" disabled
                                class="w-full py-3 bg-indigo-500 text-white rounded-xl font-black text-xs shadow-xl transition-all border border-indigo-400 cursor-not-allowed opacity-50">
                                ABSEN PULANG
                            </button>
                        </form>
                    </div>
                @else
                    <div class="space-y-2">
                        <div class="px-3 py-2 bg-emerald-500 text-white rounded-xl flex items-center justify-between shadow-lg">
                            <span class="text-[10px] font-bold uppercase">Berhasil Absen</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                @endif
            </div>

            <script>
                (function() {
                    if (navigator.geolocation) {
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
                            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                        }

                        navigator.geolocation.watchPosition(function(pos) {
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;
                            const dist = haversine(lat, lng, schoolLat, schoolLng);
                            
                            const latInput = document.getElementById('admin-lat');
                            const lngInput = document.getElementById('admin-lng');
                            const btn = document.getElementById('admin-btn-absen');

                            if (latInput) latInput.value = lat;
                            if (lngInput) lngInput.value = lng;

                            if (btn) {
                                if (dist <= schoolRadius) {
                                    btn.disabled = false;
                                    btn.style.opacity = '1';
                                    btn.style.cursor = 'pointer';
                                    if (btn.innerText.includes('MENCARI')) {
                                        btn.innerText = 'ABSEN MASUK';
                                    } else if (btn.innerText.includes('PULANG')) {
                                        btn.innerText = 'ABSEN PULANG';
                                    }
                                } else {
                                    btn.disabled = true;
                                    btn.innerText = 'DILUAR RADIUS';
                                    btn.style.opacity = '0.5';
                                }
                            }
                        });
                    }
                })();
            </script>
        </div>

        <!-- Attendance Stats Today -->
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
{{-- <div class="p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
                <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest mb-4">Ringkasan Siswa</h3>
                <div class="space-y-3">
                    @foreach(['present' => ['Hadir', 'emerald'], 'absent' => ['Alfa', 'rose'], 'sick' => ['Sakit', 'amber'], 'permission' => ['Izin', 'indigo']] as $key => $meta)
                        <div class="flex items-center justify-between p-3 bg-{{ $meta[1] }}-50 rounded-xl">
                            <span class="text-xs font-bold text-{{ $meta[1] }}-700">{{ $meta[0] }}</span>
                            <span class="text-sm font-black text-{{ $meta[1] }}-800">{{ $stats['student_attendance_today'][$key] }}</span>
                        </div>
                    @endforeach
                </div>
            </div> --}}

            <div class="p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
                <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest mb-4">Ringkasan Pegawai</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-indigo-50 rounded-xl">
                        <span class="text-xs font-bold text-indigo-700">Total Aktif</span>
                        <span class="text-sm font-black text-indigo-800">{{ $stats['personnel_attendance_today']['total'] }}</span>
                    </div>
                    @foreach(['present' => ['Hadir', 'emerald'], 'sick' => ['Sakit', 'amber'], 'permission' => ['Izin', 'indigo']] as $key => $meta)
                        <div class="flex items-center justify-between p-3 bg-{{ $meta[1] }}-50 rounded-xl">
                            <span class="text-xs font-bold text-{{ $meta[1] }}-700">{{ $meta[0] }}</span>
                            <span class="text-sm font-black text-{{ $meta[1] }}-800">{{ $stats['personnel_attendance_today'][$key] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
