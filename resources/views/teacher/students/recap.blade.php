@extends('layouts.app')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-bold uppercase tracking-widest border border-indigo-100">Laporan Kehadiran</span>
                <span class="w-1 h-1 bg-indigo-200 rounded-full"></span>
                <span class="text-sm font-bold text-slate-500">{{ $class->name ?? 'N/A' }}</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Rekap Bulanan Siswa</h1>
            <p class="text-slate-500 font-medium mt-1">Tinjauan bulanan detail catatan kehadiran kelas Anda.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="px-6 py-3 bg-white border border-slate-100 rounded-2xl flex items-center gap-4 shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 leading-none">Periode</p>
                    <p class="font-extrabold text-slate-700">{{ Carbon\Carbon::create(null, $month)->translatedFormat('F') }} {{ $year }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($class)
        <!-- Filters -->
        <div class="p-8 bg-white rounded-[2rem] border border-slate-100 shadow-sm shadow-slate-100/50">
            <form action="{{ route('teacher.students.recap') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Bulan</label>
                    <select name="month" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-slate-700 outline-none transition-all">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Tahun</label>
                    <select name="year" class="w-full px-5 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-slate-700 outline-none transition-all">
                        @foreach(range(now()->year - 2, now()->year) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 py-3.5 bg-slate-900 text-white rounded-2xl font-bold hover:bg-black transition-all shadow-lg shadow-slate-200">
                        Filter Laporan
                    </button>
                    <a href="{{ route('teacher.students.recap') }}" class="px-6 py-3.5 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition-all flex items-center justify-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Recap Grid -->
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="sticky left-0 z-20 bg-slate-50/50 px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-widest border-r border-slate-100">Info Siswa</th>
                            @foreach(range(1, $daysInMonth) as $day)
                                <th class="px-2 py-6 text-center text-[10px] font-black text-slate-400 uppercase tracking-tighter border-r border-slate-100/50 min-w-[35px] {{ Carbon\Carbon::create($year, $month, $day)->isWeekend() ? 'bg-rose-50/30' : '' }}">
                                    {{ $day }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($students as $student)
                            <tr class="hover:bg-slate-50/30 transition-colors group">
                                <td class="sticky left-0 z-10 bg-white group-hover:bg-slate-50/30 px-8 py-5 border-r border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-xs uppercase tracking-tighter border border-white overflow-hidden">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->user->name) }}&background=f1f5f9&color=6366f1&bold=true" alt="">
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-[13px] leading-none mb-1 truncate max-w-[150px]">{{ $student->user->name }}</p>
                                            <p class="text-[10px] font-medium text-slate-400">{{ $student->nis }}</p>
                                        </div>
                                    </div>
                                </td>
                                @foreach(range(1, $daysInMonth) as $day)
                                    @php
                                        $dateString = Carbon\Carbon::create($year, $month, $day)->toDateString();
                                        $attendance = $student->attendances->first(function($a) use ($dateString) {
                                            return $a->date instanceof \Carbon\Carbon 
                                                ? $a->date->toDateString() == $dateString 
                                                : (string)$a->date == $dateString;
                                        });
                                        $isWeekend = Carbon\Carbon::parse($dateString)->isWeekend();
                                    @endphp
                                    <td class="px-1 py-5 text-center border-r border-slate-100/50 {{ $isWeekend ? 'bg-rose-50/10' : '' }}">
                                        @if($attendance)
                                            <div class="flex items-center justify-center">
                                                @switch($attendance->status)
                                                    @case('present')
                                                        <div class="w-6 h-6 rounded-lg bg-emerald-500 text-white flex items-center justify-center text-[10px] font-black shadow-sm shadow-emerald-200">H</div>
                                                        @break
                                                    @case('sick')
                                                        <div class="w-6 h-6 rounded-lg bg-amber-500 text-white flex items-center justify-center text-[10px] font-black shadow-sm shadow-amber-200">S</div>
                                                        @break
                                                    @case('permission')
                                                        <div class="w-6 h-6 rounded-lg bg-blue-500 text-white flex items-center justify-center text-[10px] font-black shadow-sm shadow-blue-200">I</div>
                                                        @break
                                                    @case('absent')
                                                        <div class="w-6 h-6 rounded-lg bg-rose-500 text-white flex items-center justify-center text-[10px] font-black shadow-sm shadow-rose-200">A</div>
                                                        @break
                                                @endswitch
                                            </div>
                                        @else
                                            @if(!$isWeekend && Carbon\Carbon::parse($dateString)->isPast())
                                                <div class="w-1.5 h-1.5 rounded-full bg-slate-200 mx-auto"></div>
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Legend -->
            <div class="px-8 py-8 bg-slate-50/50 border-t border-slate-100 flex flex-wrap items-center gap-x-10 gap-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-lg bg-emerald-500 text-white flex items-center justify-center text-[10px] font-black">H</div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Hadir</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-lg bg-amber-500 text-white flex items-center justify-center text-[10px] font-black">S</div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Sakit</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-lg bg-blue-500 text-white flex items-center justify-center text-[10px] font-black">I</div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Izin</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-lg bg-rose-500 text-white flex items-center justify-center text-[10px] font-black">A</div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Alpha</span>
                </div>
            </div>
        </div>
    @else
        <div class="bg-indigo-50 p-12 rounded-[2.5rem] border-2 border-dashed border-indigo-200 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 mb-6 border-4 border-white shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-2xl font-black text-indigo-900 leading-tight">Akses Dibatasi</h3>
            <p class="text-indigo-600/70 font-bold mt-3 max-w-sm">Rekap bulanan hanya tersedia untuk Wali Kelas.</p>
        </div>
    @endif
</div>
@endsection
