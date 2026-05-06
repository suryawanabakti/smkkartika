@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Daftar Hadir Mengajar Guru</h1>
                <p class="text-gray-600">{{ $dayName }}, {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <form action="{{ route('admin.attendance.teaching_recap') }}" method="GET" class="flex gap-2">
                    <input type="date" name="date" value="{{ $date }}"
                        class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                        Filter
                    </button>
                </form>
                <a href="{{ route('admin.attendance.teaching_recap.pdf', ['date' => $date]) }}" target="_blank"
                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span>Cetak PDF</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-collapse border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th rowspan="2" class="border border-gray-200 px-2 py-3 text-center uppercase tracking-wider font-semibold">No.</th>
                            <th rowspan="2" class="border border-gray-200 px-4 py-3 uppercase tracking-wider font-semibold">Nama</th>
                            <th rowspan="2" class="border border-gray-200 px-2 py-3 text-center uppercase tracking-wider font-semibold">Gol.</th>
                            <th rowspan="2" class="border border-gray-200 px-2 py-3 text-center uppercase tracking-wider font-semibold">Status</th>
                            <th colspan="11" class="border border-gray-200 py-2 text-center uppercase tracking-wider font-semibold">Jam / Paraf</th>
                            <th rowspan="2" class="border border-gray-200 px-4 py-3 uppercase tracking-wider font-semibold">Mata Pelajaran</th>
                            <th rowspan="2" class="border border-gray-200 px-4 py-3 uppercase tracking-wider font-semibold">Guru Pengganti</th>
                            <th colspan="3" class="border border-gray-200 py-2 text-center uppercase tracking-wider font-semibold">Kelas</th>
                            <th rowspan="2" class="border border-gray-200 px-2 py-3 text-center uppercase tracking-wider font-semibold">Jml</th>
                            <th colspan="2" class="border border-gray-200 py-2 text-center uppercase tracking-wider font-semibold">Jam</th>
                        </tr>
                        <tr>
                            @for($i = 1; $i <= 11; $i++)
                                <th class="border border-gray-200 py-1 text-center font-semibold">{{ $i }}</th>
                            @endfor
                            <th class="border border-gray-200 py-1 text-center font-semibold">X</th>
                            <th class="border border-gray-200 py-1 text-center font-semibold">XI</th>
                            <th class="border border-gray-200 py-1 text-center font-semibold">XII</th>
                            <th class="border border-gray-200 py-1 text-center font-semibold">Datang</th>
                            <th class="border border-gray-200 py-1 text-center font-semibold">Pulang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($teachers as $index => $teacher)
                            @php
                                $attendance = $attendances->get($teacher->user_id);
                                $checkIn = $attendance ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '';
                                $checkOut = $attendance && $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '';
                                
                                // Group schedules by subject to handle multiple subjects in one row or multiple rows
                                // For simplicity and matching the image, we'll try to aggregate if subjects are the same, 
                                // but teachers often teach different subjects. 
                                // Let's show each unique subject-class combination if they have multiple.
                                $teacherSchedules = $teacher->schedules;
                                $totalPeriods = $teacherSchedules->sum(function($s) { return ($s->period_end - $s->period_start) + 1; });
                            @endphp

                            @if($teacherSchedules->isEmpty())
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="border border-gray-200 px-2 py-3 text-center">{{ $index + 1 }}</td>
                                    <td class="border border-gray-200 px-4 py-3 font-medium">{{ $teacher->user->name }}</td>
                                    <td class="border border-gray-200 px-2 py-3 text-center">-</td>
                                    <td class="border border-gray-200 px-2 py-3 text-center">{{ $teacher->position ?? 'GT' }}</td>
                                    @for($i = 1; $i <= 11; $i++)
                                        <td class="border border-gray-200 py-1 text-center">-</td>
                                    @endfor
                                    <td class="border border-gray-200 px-4 py-3">-</td>
                                    <td class="border border-gray-200 px-4 py-3">-</td>
                                    <td class="border border-gray-200 py-1 text-center">-</td>
                                    <td class="border border-gray-200 py-1 text-center">-</td>
                                    <td class="border border-gray-200 py-1 text-center">-</td>
                                    <td class="border border-gray-200 px-2 py-3 text-center">0</td>
                                    <td class="border border-gray-200 px-2 py-3 text-center text-indigo-600 font-semibold">{{ $checkIn }}</td>
                                    <td class="border border-gray-200 px-2 py-3 text-center text-red-600 font-semibold">{{ $checkOut }}</td>
                                </tr>
                            @else
                                @php
                                    // For now, we'll show the first subject but you could loop if needed.
                                    // The image shows one row per teacher even if they teach multiple periods.
                                    $subjects = $teacherSchedules->pluck('subject')->unique()->implode(', ');
                                    $classes = $teacherSchedules->pluck('classRoom.name')->unique();
                                    $classX = $classes->filter(fn($c) => str_starts_with($c, 'X '))->implode(', ') ?: '-';
                                    $classXI = $classes->filter(fn($c) => str_starts_with($c, 'XI '))->implode(', ') ?: '-';
                                    $classXII = $classes->filter(fn($c) => str_starts_with($c, 'XII '))->implode(', ') ?: '-';
                                    
                                    $scheduledPeriods = [];
                                    $periodTimes = []; // schedule_id => check_in_time
                                    $tAttendances = $teachingAttendances->get($teacher->id) ?? collect();
                                    
                                    foreach($teacherSchedules as $s) {
                                        $tAttendance = $tAttendances->where('schedule_id', $s->id)->first();
                                        for($p = $s->period_start; $p <= $s->period_end; $p++) {
                                            $scheduledPeriods[] = $p;
                                            if ($tAttendance) {
                                                $periodTimes[$p] = \Carbon\Carbon::parse($tAttendance->check_in_time)->format('H:i');
                                            }
                                        }
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="border border-gray-200 px-2 py-3 text-center">{{ $index + 1 }}</td>
                                    <td class="border border-gray-200 px-4 py-3 font-medium">{{ $teacher->user->name }}</td>
                                    <td class="border border-gray-200 px-2 py-3 text-center">-</td>
                                    <td class="border border-gray-200 px-2 py-3 text-center">{{ $teacher->position ?? 'GT' }}</td>
                                    @for($i = 1; $i <= 11; $i++)
                                        <td class="border border-gray-200 py-1 text-center @if(in_array($i, $scheduledPeriods)) bg-indigo-50 @endif">
                                            @if(in_array($i, $scheduledPeriods))
                                                <span class="text-[10px] font-bold text-indigo-700">
                                                    {{ $periodTimes[$i] ?? ($checkIn ?: 'Sched') }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endfor
                                    <td class="border border-gray-200 px-4 py-3">{{ $subjects }}</td>
                                    <td class="border border-gray-200 px-4 py-3">-</td>
                                    <td class="border border-gray-200 py-1 text-center">{{ $classX }}</td>
                                    <td class="border border-gray-200 py-1 text-center">{{ $classXI }}</td>
                                    <td class="border border-gray-200 py-1 text-center">{{ $classXII }}</td>
                                    <td class="border border-gray-200 px-2 py-3 text-center font-bold">{{ $totalPeriods }}</td>
                                    <td class="border border-gray-200 px-2 py-3 text-center text-indigo-600 font-semibold">{{ $checkIn }}</td>
                                    <td class="border border-gray-200 px-2 py-3 text-center text-red-600 font-semibold">{{ $checkOut }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="25" class="px-6 py-12 text-center text-gray-500">
                                    Tidak ada data guru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
