<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Absen Mengajar Guru</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 14px;
        }
        .header h2 {
            margin: 0;
            font-size: 12px;
            font-weight: normal;
        }
        .info {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-left {
            text-align: left;
        }
        .bg-gray {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>YAYASAN KARTIKA JAYA CABANG XX HASANUDDIN</h2>
        <h1>SMK KARTIKA XX-1 HASANUDDIN MAKASSAR</h1>
        <h2>DAFTAR HADIR MENGAJAR GURU</h2>
        <h3>TAHUN PELAJARAN 2025 - 2026</h3>
    </div>

    <div class="info">
        <table style="border: none; width: auto;">
            <tr style="border: none;">
                <td style="border: none; text-align: left;">Hari</td>
                <td style="border: none;">: {{ $dayName }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; text-align: left;">Tanggal</td>
                <td style="border: none;">: {{ \Carbon\Carbon::parse($date)->format('d - m - Y') }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2" style="width: 150px;">Nama</th>
                <th rowspan="2">Gol.</th>
                <th rowspan="2">Status</th>
                <th colspan="11">Jam / Paraf</th>
                <th rowspan="2">Mata Pelajaran</th>
                <th rowspan="2">Guru Pengganti</th>
                <th colspan="3">Kelas</th>
                <th rowspan="2">Jml</th>
                <th colspan="2">Jam</th>
            </tr>
            <tr>
                @for($i = 1; $i <= 11; $i++)
                    <th>{{ $i }}</th>
                @endfor
                <th>X</th>
                <th>XI</th>
                <th>XII</th>
                <th>Datang</th>
                <th>Pulang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $index => $teacher)
                @php
                    $attendance = $attendances->get($teacher->user_id);
                    $checkIn = $attendance ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '';
                    $checkOut = $attendance && $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '';
                    
                    $teacherSchedules = $teacher->schedules;
                    $totalPeriods = $teacherSchedules->sum(function($s) { return ($s->period_end - $s->period_start) + 1; });
                    
                    $subjects = $teacherSchedules->pluck('subject')->unique()->implode(', ');
                    $classes = $teacherSchedules->pluck('classRoom.name')->unique();
                    $classX = $classes->filter(fn($c) => str_contains($c, 'X '))->implode(', ') ?: '-';
                    $classXI = $classes->filter(fn($c) => str_contains($c, 'XI '))->implode(', ') ?: '-';
                    $classXII = $classes->filter(fn($c) => str_contains($c, 'XII '))->implode(', ') ?: '-';
                    
                    $scheduledPeriods = [];
                    $periodTimes = [];
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
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $teacher->user->name }}</td>
                    <td>-</td>
                    <td>{{ $teacher->position ?? 'GT' }}</td>
                    @for($i = 1; $i <= 11; $i++)
                        <td @if(in_array($i, $scheduledPeriods)) style="background-color: #eee;" @endif>
                            @if(in_array($i, $scheduledPeriods))
                                {{ $periodTimes[$i] ?? ($checkIn ?: '') }}
                            @else
                                -
                            @endif
                        </td>
                    @endfor
                    <td class="text-left">{{ $subjects }}</td>
                    <td>-</td>
                    <td>{{ $classX }}</td>
                    <td>{{ $classXI }}</td>
                    <td>{{ $classXII }}</td>
                    <td>{{ $totalPeriods }}</td>
                    <td>{{ $checkIn }}</td>
                    <td>{{ $checkOut }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Makassar, {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</p>
        <p style="margin-top: 40px;">( .................................................. )</p>
    </div>
</body>
</html>
