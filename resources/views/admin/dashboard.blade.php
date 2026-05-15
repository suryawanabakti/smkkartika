@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-2">


            <!-- Teachers -->
            <div class="p-4 sm:p-5 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center space-x-3">
                <div class="p-2 bg-purple-100 text-purple-600 rounded-lg shrink-0">
                    <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="min-w-0 text-center">
                    <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wider">Guru</p>
                    <p class="text-xl font-bold text-gray-800">{{ $stats['total_teachers'] }}</p>
                </div>
            </div>

            <!-- Staffs -->
            <div class="p-4 sm:p-5 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center space-x-3">
                <div class="p-2 bg-amber-100 text-amber-600 rounded-lg shrink-0">
                    <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="min-w-0 text-center">
                    <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wider">Staff</p>
                    <p class="text-xl font-bold text-gray-800">{{ $stats['total_staffs'] }}</p>
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
                        }, --}} {
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
                                titleFont: {
                                    size: 12,
                                    weight: 'bold',
                                    family: 'Inter'
                                },
                                bodyFont: {
                                    size: 12,
                                    family: 'Inter'
                                },
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
                                    font: {
                                        family: 'Inter',
                                        size: 11,
                                        weight: 'bold'
                                    },
                                    color: '#94a3b8'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        family: 'Inter',
                                        size: 11,
                                        weight: 'bold'
                                    },
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
            <div
                class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-100 flex flex-col justify-between overflow-hidden relative group">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl group-hover:bg-white/20 transition-all duration-500">
                </div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[10px] font-black uppercase tracking-widest text-indigo-100">Presensi Admin</span>
                        @if ($myAttendance && $myAttendance->check_out_time)
                            <span
                                class="px-2 py-0.5 bg-emerald-500/20 rounded-lg text-[8px] font-black uppercase tracking-widest border border-white/10">Selesai
                                Berkerja</span>
                        @elseif($myAttendance)
                            @if(in_array($myAttendance->status, ['sick', 'permission']))
                                <span
                                    class="px-2 py-0.5 bg-blue-500/20 rounded-lg text-[8px] font-black uppercase tracking-widest border border-white/10">{{ $myAttendance->status == 'sick' ? 'Sakit' : 'Izin' }}</span>
                            @else
                                <span
                                    class="px-2 py-0.5 bg-amber-500/20 rounded-lg text-[8px] font-black uppercase tracking-widest border border-white/10">Sedang
                                    Berkerja</span>
                            @endif
                        @endif
                    </div>
                    <h3 class="text-xl font-black mb-2">Halo, Admin!</h3>
                    <p class="text-xs text-indigo-100 font-medium mb-6">Jangan lupa catat kehadiran Anda hari ini.</p>
                </div>

                <div class="relative z-10">
                    @php
                        $isPastPulang = now()->format('H:i') > $schoolSettings['min_check_out'];
                    @endphp

                    @if (!$myAttendance)
                        <form action="{{ route('admin.attendance.store') }}" method="POST" id="admin-attendance-form">
                            @csrf
                            <input type="hidden" name="latitude" id="admin-lat">
                            <input type="hidden" name="longitude" id="admin-lng">

                            <div id="admin-status-container" class="hidden mb-3 space-y-2">
                                <select name="status" id="admin-status-select"
                                    class="w-full py-2 bg-white/20 border-white/20 rounded-xl text-[10px] font-bold text-white placeholder-white/50 focus:ring-0 focus:border-white/40">
                                    <option value="present" class="text-gray-800" id="admin-opt-present">Hadir</option>
                                    <option value="sick" class="text-gray-800">Sakit</option>
                                    <option value="permission" class="text-gray-800">Izin</option>
                                    <option value="absent" class="text-gray-800">Alfa</option>
                                </select>
                                <input type="text" name="description" placeholder="Keterangan (Opsional)"
                                    class="w-full py-2 bg-white/10 border-white/10 rounded-xl text-[10px] font-bold text-white placeholder-white/50 focus:ring-0 focus:border-white/40">
                            </div>

                            <button type="submit" id="admin-btn-absen" disabled
                                class="w-full py-3 bg-white text-indigo-600 rounded-xl font-black text-xs shadow-xl transition-all flex items-center justify-center gap-2 cursor-not-allowed opacity-50">
                                MENCARI LOKASI...
                            </button>
                        </form>
                    @elseif(!$myAttendance->check_out_time)
                        @if(in_array($myAttendance->status, ['sick', 'permission']))
                            <div class="space-y-2">
                                <div class="px-3 py-4 bg-white/10 rounded-xl text-center border border-white/10">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-100">Status: {{ $myAttendance->status == 'sick' ? 'Sakit' : 'Izin' }}</p>
                                    <p class="text-[9px] font-bold text-white/70 mt-1">Tidak perlu absen pulang</p>
                                </div>
                            </div>
                        @else
                            <div class="space-y-2">
                                <div
                                    class="px-3 py-2 bg-white/10 rounded-xl flex items-center justify-between border border-white/5">
                                    <span class="text-[10px] font-bold text-indigo-100 uppercase">Waktu Masuk</span>
                                    <span
                                        class="font-black">{{ Carbon\Carbon::parse($myAttendance->check_in_time)->format('H:i') }}</span>
                                </div>
                                <form action="{{ route('admin.attendance.checkout') }}" method="POST"
                                    id="admin-attendance-form">
                                    @csrf
                                    <input type="hidden" name="latitude" id="admin-lat">
                                    <input type="hidden" name="longitude" id="admin-lng">
                                    <button type="submit" id="admin-btn-absen" disabled
                                        class="w-full py-3 bg-indigo-500 text-white rounded-xl font-black text-xs shadow-xl transition-all border border-indigo-400 cursor-not-allowed opacity-50">
                                        ABSEN PULANG
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="space-y-2">
                            <div
                                class="px-3 py-2 bg-emerald-500 text-white rounded-xl flex items-center justify-between shadow-lg">
                                <span class="text-[10px] font-bold uppercase">Berhasil Absen</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
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
                                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                                    Math.sin(dLng / 2) * Math.sin(dLng / 2);
                                return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                            }

                            navigator.geolocation.watchPosition(function(pos) {
                                const lat = pos.coords.latitude;
                                const lng = pos.coords.longitude;
                                const dist = haversine(lat, lng, schoolLat, schoolLng);

                                const latInput = document.getElementById('admin-lat');
                                const lngInput = document.getElementById('admin-lng');
                                const btn = document.getElementById('admin-btn-absen');
                                const statusContainer = document.getElementById('admin-status-container');
                                const statusSelect = document.getElementById('admin-status-select');
                                const optPresent = document.getElementById('admin-opt-present');

                                if (latInput) latInput.value = lat;
                                if (lngInput) lngInput.value = lng;

                                if (btn) {
                                    btn.disabled = false;
                                    btn.style.opacity = '1';
                                    btn.style.cursor = 'pointer';

                                    if (statusContainer) statusContainer.classList.remove('hidden');

                                    if (dist <= schoolRadius) {
                                        if (optPresent) {
                                            optPresent.disabled = false;
                                            optPresent.textContent = 'Hadir (Dalam Radius)';
                                        }
                                        if (btn.innerText.includes('MENCARI')) {
                                            btn.innerText = 'ABSEN MASUK';
                                        } else if (btn.innerText.includes('PULANG')) {
                                            btn.innerText = 'ABSEN PULANG';
                                        }
                                    } else {
                                        if (btn.innerText.includes('PULANG')) {
                                            btn.disabled = true;
                                            btn.innerText = 'DILUAR RADIUS';
                                            btn.style.opacity = '0.5';
                                        } else {
                                            if (optPresent) {
                                                optPresent.disabled = true;
                                                optPresent.textContent = 'Hadir (Hanya di Lokasi)';
                                                if (statusSelect.value === 'present') {
                                                    statusSelect.value = 'sick';
                                                }
                                            }
                                            btn.innerText = 'KIRIM KETERANGAN';
                                        }
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
                    @foreach (['present' => ['Hadir', 'emerald'], 'absent' => ['Alfa', 'rose'], 'sick' => ['Sakit', 'amber'], 'permission' => ['Izin', 'indigo']] as $key => $meta)
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
                            <span
                                class="text-sm font-black text-indigo-800">{{ $stats['personnel_attendance_today']['total'] }}</span>
                        </div>
                        @foreach (['present' => ['Hadir', 'emerald'], 'sick' => ['Sakit', 'amber'], 'permission' => ['Izin', 'indigo']] as $key => $meta)
                            <div class="flex items-center justify-between p-3 bg-{{ $meta[1] }}-50 rounded-xl">
                                <span class="text-xs font-bold text-{{ $meta[1] }}-700">{{ $meta[0] }}</span>
                                <span
                                    class="text-sm font-black text-{{ $meta[1] }}-800">{{ $stats['personnel_attendance_today'][$key] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Attendance List -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mt-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-black text-gray-800">Riwayat Absensi Admin</h3>
                </div>
                <a href="{{ route('admin.attendance.personnel') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-xs font-bold hover:bg-indigo-100 transition-colors whitespace-nowrap">
                    Lihat Semua
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-3 text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                Pegawai</th>
                            <th
                                class="pb-3 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                Waktu Masuk</th>
                            <th
                                class="pb-3 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                Waktu Pulang</th>
                            <th class="pb-3 text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentAttendance as $attendance)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($attendance->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-gray-800 truncate">
                                                {{ $attendance->user->name ?? 'Unknown User' }}</p>
                                            <p class="text-xs text-gray-500">{{ $attendance->date->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-sm font-medium text-gray-600 whitespace-nowrap">
                                    {{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}
                                </td>
                                <td class="py-4 px-4 text-sm font-medium text-gray-600 whitespace-nowrap">
                                    {{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}
                                </td>
                                <td class="py-4 whitespace-nowrap">
                                    @switch($attendance->status)
                                        @case('present')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-700 uppercase tracking-wider">Hadir</span>
                                        @break

                                        @case('sick')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-700 uppercase tracking-wider">Sakit</span>
                                        @break

                                        @case('permission')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 uppercase tracking-wider">Izin</span>
                                        @break

                                        @default
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-rose-50 text-rose-700 uppercase tracking-wider">Alfa</span>
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-500 text-sm font-medium">
                                        Belum ada data absensi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    @endsection
