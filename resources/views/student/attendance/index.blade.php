@extends('layouts.app')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-bold uppercase tracking-widest border border-indigo-100">Riwayat</span>
                <span class="w-1 h-1 bg-indigo-200 rounded-full"></span>
                <span class="text-sm font-bold text-slate-500">{{ $student->classRoom->name }}</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Kehadiran</h1>
            <p class="text-slate-500 font-medium mt-1">Tinjau catatan kehadiran Anda untuk periode yang dipilih.</p>
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

    <!-- Filters -->
    <div class="p-4 sm:p-8 bg-white rounded-2xl sm:rounded-[2rem] border border-slate-100 shadow-sm shadow-slate-100/50">
        <form action="{{ route('student.attendance.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 items-end">
            <div>
                <label class="block text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest mb-2 sm:mb-3">Bulan</label>
                <select name="month" class="w-full px-4 sm:px-5 py-2.5 sm:py-3.5 bg-slate-50 border-none rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-slate-700 outline-none transition-all text-sm">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest mb-2 sm:mb-3">Tahun</label>
                <select name="year" class="w-full px-4 sm:px-5 py-2.5 sm:py-3.5 bg-slate-50 border-none rounded-xl sm:rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-slate-700 outline-none transition-all text-sm">
                    @foreach(range(now()->year - 2, now()->year) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2 sm:gap-3">
                <button type="submit" class="flex-1 py-2.5 sm:py-3.5 bg-slate-900 text-white rounded-xl sm:rounded-2xl font-bold hover:bg-black transition-all shadow-lg shadow-slate-200 text-sm">
                    Filter
                </button>
                <a href="{{ route('student.attendance.index') }}" class="px-4 sm:px-6 py-2.5 sm:py-3.5 bg-slate-100 text-slate-600 rounded-xl sm:rounded-2xl font-bold hover:bg-slate-200 transition-all flex items-center justify-center text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white rounded-2xl sm:rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-4 sm:px-8 py-4 sm:py-6 text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-4 sm:px-8 py-4 sm:py-6 text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest hidden sm:table-cell">Hari</th>
                        <th class="px-4 sm:px-8 py-4 sm:py-6 text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-4 sm:px-8 py-4 sm:py-6 text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest hidden md:table-cell">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-4 sm:px-8 py-4 sm:py-5">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-700">{{ $attendance->date->format('d M Y') }}</span>
                                    <span class="sm:hidden text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $attendance->date->translatedFormat('l') }}</span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-8 py-4 sm:py-5 hidden sm:table-cell">
                                <span class="text-sm font-bold text-slate-500 uppercase tracking-tighter">{{ $attendance->date->translatedFormat('l') }}</span>
                            </td>
                            <td class="px-4 sm:px-8 py-4 sm:py-5">
                                <div class="flex items-center justify-center">
                                    @switch($attendance->status)
                                        @case('present')
                                            <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] sm:text-[10px] font-black uppercase tracking-widest border border-emerald-100">Hadir</span>
                                            @break
                                        @case('sick')
                                            <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-amber-50 text-amber-600 rounded-lg text-[9px] sm:text-[10px] font-black uppercase tracking-widest border border-amber-100">Sakit</span>
                                            @break
                                        @case('permission')
                                            <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-blue-50 text-blue-600 rounded-lg text-[9px] sm:text-[10px] font-black uppercase tracking-widest border border-blue-100">Izin</span>
                                            @break
                                        @case('absent')
                                            <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-rose-50 text-rose-600 rounded-lg text-[9px] sm:text-[10px] font-black uppercase tracking-widest border border-rose-100">Alfa</span>
                                            @break
                                    @endswitch
                                </div>
                            </td>
                            <td class="px-4 sm:px-8 py-4 sm:py-5 hidden md:table-cell">
                                <p class="text-xs text-slate-400 font-medium italic">Otomatis diverifikasi</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-16 sm:py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4 border border-slate-100">
                                        <svg class="w-6 h-6 sm:w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Tidak ada catatan ditemukan</h3>
                                    <p class="text-xs sm:text-sm text-slate-500 font-medium max-w-xs mt-1">Tidak ada catatan kehadiran untuk periode yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
