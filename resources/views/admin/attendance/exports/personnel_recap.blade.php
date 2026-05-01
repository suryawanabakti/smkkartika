<table>
    <thead>
        <tr>
            <th colspan="{{ $daysInMonth + 1 }}" style="font-weight: bold; font-size: 14px; text-align: center;">
                REKAP KEHADIRAN PEGAWAI
            </th>
        </tr>
        <tr>
            <th colspan="{{ $daysInMonth + 1 }}" style="font-weight: bold; font-size: 12px; text-align: center;">
                Bulan: {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th rowspan="2" style="font-weight: bold; text-align: left;">NAMA / NIP</th>
            @for($i = 1; $i <= $daysInMonth; $i++)
                <th style="font-weight: bold; text-align: center;">{{ sprintf('%02d', $i) }}</th>
            @endfor
        </tr>
        <tr>
            @for($i = 1; $i <= $daysInMonth; $i++)
                @php
                    $date = $startDate->copy()->day($i);
                    $isSunday = $date->isSunday();
                @endphp
                <th style="font-weight: bold; text-align: center; {{ $isSunday ? 'background-color: #ffcccc; color: #cc0000;' : '' }}">
                    {{ strtoupper(substr($date->shortEnglishDayOfWeek, 0, 2)) }}
                </th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach($personnel as $person)
            <tr>
                <td>
                    <b>{{ $person->name }}</b><br>
                    {{ $person->teacher->nip ?? 'A000000000001' }}
                </td>
                @for($i = 1; $i <= $daysInMonth; $i++)
                    @php
                        $date = $startDate->copy()->day($i);
                        $isSunday = $date->isSunday();
                        $attendance = isset($attendanceData[$person->id][$i]) ? $attendanceData[$person->id][$i]->first(function($a) use ($date) {
                            $dateString = $date->toDateString();
                            return $a->date instanceof \Carbon\Carbon 
                                ? $a->date->toDateString() == $dateString 
                                : (string)$a->date == $dateString;
                        }) : null;
                        
                        $statusChar = '';
                        $cellColor = '';
                        
                        if ($isSunday) {
                            $statusChar = 'L';
                            $cellColor = 'background-color: #ffcccc; color: #cc0000;';
                        } elseif ($attendance) {
                            switch($attendance->status) {
                                case 'present': $statusChar = 'H'; $cellColor = 'color: #008000; font-weight: bold;'; break;
                                case 'sick': $statusChar = 'S'; $cellColor = 'color: #d9a400; font-weight: bold;'; break;
                                case 'permission': $statusChar = 'I'; $cellColor = 'color: #0000FF; font-weight: bold;'; break;
                                case 'absent': $statusChar = 'A'; $cellColor = 'color: #FF0000; font-weight: bold;'; break;
                            }
                        }
                    @endphp
                    <td style="text-align: center; {{ $cellColor }}">
                        {{ $statusChar }}
                    </td>
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th colspan="4" style="font-weight: bold; text-align: left;">KETERANGAN / CATATAN</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Tanggal</th>
            <th style="font-weight: bold;">Nama Pegawai</th>
            <th style="font-weight: bold;">Status</th>
            <th style="font-weight: bold;">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @php $hasDescription = false; @endphp
        @foreach($personnel as $person)
            @for($i = 1; $i <= $daysInMonth; $i++)
                @php
                    $attendance = isset($attendanceData[$person->id][$i]) ? $attendanceData[$person->id][$i]->first(function($a) use ($startDate, $i) {
                            $dateString = $startDate->copy()->day($i)->toDateString();
                            return $a->date instanceof \Carbon\Carbon 
                                ? $a->date->toDateString() == $dateString 
                                : (string)$a->date == $dateString;
                        }) : null;
                @endphp
                @if($attendance && $attendance->description)
                    @php $hasDescription = true; @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                        <td>{{ $person->name }}</td>
                        <td>
                            @switch($attendance->status)
                                @case('present') Hadir @break
                                @case('sick') Sakit @break
                                @case('permission') Izin @break
                                @case('absent') Alfa @break
                            @endswitch
                        </td>
                        <td>{{ $attendance->description }}</td>
                    </tr>
                @endif
            @endfor
        @endforeach
        @if(!$hasDescription)
            <tr>
                <td colspan="4">Tidak ada keterangan tambahan.</td>
            </tr>
        @endif
    </tbody>
</table>
