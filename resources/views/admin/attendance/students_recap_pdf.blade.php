<!DOCTYPE html>
<html>
<head>
    <title>Rekap Kehadiran Siswa</title>
    <style>
        @page { margin: 20px; }
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 2px; text-align: center; font-size: 9px; }
        th.name-col, td.name-col { text-align: left; min-width: 150px; padding-left: 5px; }
        .holiday { background-color: #ffcccc; color: #cc0000; }
        .H { color: green; font-weight: bold; }
        .S { color: #d9a400; font-weight: bold; }
        .I { color: blue; font-weight: bold; }
        .A { color: red; font-weight: bold; }
        h2, h3 { text-align: center; margin: 5px 0; }
    </style>
</head>
<body>
    <h2>REKAP KEHADIRAN SISWA</h2>
    <h3>Bulan: {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}</h3>
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="name-col">NAMA / NIS</th>
                @for($i = 1; $i <= $daysInMonth; $i++)
                    @php
                        $date = $startDate->copy()->day($i);
                        $isSunday = $date->isSunday();
                    @endphp
                    <th class="{{ $isSunday ? 'holiday' : '' }}">{{ sprintf('%02d', $i) }}</th>
                @endfor
            </tr>
            <tr>
                @for($i = 1; $i <= $daysInMonth; $i++)
                    @php
                        $date = $startDate->copy()->day($i);
                        $isSunday = $date->isSunday();
                    @endphp
                    <th class="{{ $isSunday ? 'holiday' : '' }}">{{ strtoupper(substr($date->shortEnglishDayOfWeek, 0, 2)) }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td class="name-col">
                        <b>{{ $student->user->name }}</b><br>
                        <span style="font-size: 8px;">{{ $student->nis }} • {{ $student->classRoom->name }}</span>
                    </td>
                    @for($i = 1; $i <= $daysInMonth; $i++)
                        @php
                            $date = $startDate->copy()->day($i);
                            $isSunday = $date->isSunday();
                            $attendance = isset($attendanceData[$student->id][$i]) ? $attendanceData[$student->id][$i]->first(function($a) use ($date) {
                                $dateString = $date->toDateString();
                                return $a->date instanceof \Carbon\Carbon 
                                    ? $a->date->toDateString() == $dateString 
                                    : (string)$a->date == $dateString;
                            }) : null;
                            
                            $statusChar = '';
                            $cellClass = '';
                            
                            if ($isSunday) {
                                $statusChar = 'L';
                                $cellClass = 'holiday';
                            } elseif ($attendance) {
                                switch($attendance->status) {
                                    case 'present': $statusChar = 'H'; $cellClass = 'H'; break;
                                    case 'sick': $statusChar = 'S'; $cellClass = 'S'; break;
                                    case 'permission': $statusChar = 'I'; $cellClass = 'I'; break;
                                    case 'absent': $statusChar = 'A'; $cellClass = 'A'; break;
                                }
                            }
                        @endphp
                        <td class="{{ $cellClass }}">
                            {{ $statusChar }}
                        </td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
