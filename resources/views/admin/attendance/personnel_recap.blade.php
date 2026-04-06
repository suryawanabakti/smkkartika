@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-800">Rekap Kehadiran Pegawai</h1>
            <p class="text-xs sm:text-sm text-gray-500">Tinjauan bulanan kehadiran staf</p>
        </div>
        
        <div class="px-4 py-3 bg-white rounded-xl border border-gray-100 shadow-sm">
            <form action="{{ route('admin.attendance.personnel.recap') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bulan</label>
                    <select name="month" class="px-2 py-1.5 border border-gray-100 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 font-bold">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tahun</label>
                    <select name="year" class="px-2 py-1.5 border border-gray-100 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 font-bold">
                        @foreach(range(date('Y') - 5, date('Y') + 1) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-1.5 bg-gray-800 text-white rounded-lg text-xs font-bold hover:bg-gray-900 transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.attendance.personnel.recap.pdf', request()->all()) }}" target="_blank" class="px-4 py-1.5 bg-red-600 text-white rounded-lg text-xs font-bold hover:bg-red-700 transition-colors flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Cetak PDF
                </a>
            </form>
        </div>
    </div>

    <!-- Recap Grid -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-gray-100">
                        <th rowspan="2" class="px-6 py-4 text-left font-bold text-slate-600 uppercase tracking-tight sticky left-0 bg-slate-50 z-20 border-r border-gray-100 min-w-[220px]">
                            NAMA / NIP
                        </th>
                        @for($i = 1; $i <= $daysInMonth; $i++)
                            @php
                                $date = $startDate->copy()->day($i);
                                $isSunday = $date->isSunday();
                            @endphp
                            <th class="px-1 py-2 text-center font-bold border-r border-gray-50 {{ $isSunday ? 'bg-red-50 text-red-500' : 'text-slate-500' }}">
                                {{ sprintf('%02d', $i) }}
                            </th>
                        @endfor
                    </tr>
                    <tr class="bg-slate-50/50 border-b border-gray-100">
                        @for($i = 1; $i <= $daysInMonth; $i++)
                            @php
                                $date = $startDate->copy()->day($i);
                                $isSunday = $date->isSunday();
                            @endphp
                            <th class="px-1 py-1 text-center font-semibold border-r border-gray-50 {{ $isSunday ? 'bg-red-50 text-red-400' : 'text-slate-400 font-mono text-[9px]' }}">
                                {{ strtoupper($date->shortEnglishDayOfWeek) }}
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($personnel as $person)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4 sticky left-0 bg-white group-hover:bg-slate-50 z-10 border-r border-gray-100 shadow-[4px_0_8px_-4px_rgba(0,0,0,0.05)]">
                                <div class="font-bold text-slate-800 truncate">{{ $person->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $person->teacher->nip ?? 'A000000000001' }}</div>
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
                                    $cellClass = '';
                                    
                                    if ($isSunday) {
                                        $statusChar = 'L';
                                        $cellClass = 'bg-red-50/40 text-red-200';
                                    } elseif ($attendance) {
                                        switch($attendance->status) {
                                            case 'present': $statusChar = 'H'; $cellClass = 'text-green-600 font-extrabold'; break;
                                            case 'sick': $statusChar = 'S'; $cellClass = 'bg-yellow-50 text-yellow-600 font-extrabold'; break;
                                            case 'permission': $statusChar = 'I'; $cellClass = 'bg-blue-50 text-blue-600 font-extrabold'; break;
                                            case 'absent': $statusChar = 'A'; $cellClass = 'bg-red-50 text-red-600 font-extrabold animate-pulse'; break;
                                        }
                                    }
                                @endphp
                                <td class="px-1 py-1 text-center border-r border-gray-50 min-w-[38px] {{ $cellClass }} transition-all duration-300">
                                    {{ $statusChar }}
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Legend Card -->
    <div class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
        <h3 class="text-xs font-bold text-gray-400 uppercase mb-4">Legenda & Kode Status</h3>
        <div class="flex flex-wrap gap-x-8 gap-y-4">
            <div class="flex items-center gap-3 text-xs">
                <div class="w-8 h-8 rounded-xl bg-green-50 text-green-600 flex items-center justify-center font-black border border-green-100">H</div>
                <div class="flex flex-col">
                    <span class="font-bold text-gray-700">Hadir</span>
                    <span class="text-gray-400 text-[10px]">Hadir</span>
                </div>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <div class="w-8 h-8 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center font-black border border-yellow-100">S</div>
                <div class="flex flex-col">
                    <span class="font-bold text-gray-700">Sakit</span>
                    <span class="text-gray-400 text-[10px]">Sakit</span>
                </div>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black border border-blue-100">I</div>
                <div class="flex flex-col">
                    <span class="font-bold text-gray-700">Izin</span>
                    <span class="text-gray-400 text-[10px]">Izin</span>
                </div>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <div class="w-8 h-8 rounded-xl bg-red-100 text-red-600 flex items-center justify-center font-black border border-red-200">A</div>
                <div class="flex flex-col">
                    <span class="font-bold text-gray-700">Alfa</span>
                    <span class="text-gray-400 text-[10px]">Alfa</span>
                </div>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <div class="w-8 h-8 rounded-xl bg-red-50 text-red-500 flex items-center justify-center font-black border border-red-100">L</div>
                <div class="flex flex-col">
                    <span class="font-bold text-gray-700">Libur</span>
                    <span class="text-gray-400 text-[10px]">Minggu/Hari Libur</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
