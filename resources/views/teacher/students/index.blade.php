@extends('layouts.app')

@section('content')
<div class="space-y-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-black uppercase tracking-widest border border-indigo-100">Wali Kelas</span>
                <span class="w-1 h-1 bg-indigo-200 rounded-full"></span>
                <span class="text-sm font-bold text-slate-500">{{ $class->name ?? 'N/A' }}</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kehadiran Siswa</h1>
            <p class="text-slate-500 font-medium mt-1">Kelola catatan kehadiran siswa Anda hari ini.</p>
        </div>
        
        <div class="px-6 py-3 bg-white border border-slate-100 rounded-2xl flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 leading-none">Pencatatan untuk</p>
                <p class="font-extrabold text-slate-700">{{ Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
    </div>

    @if($class)
        <!-- Attendance Form -->
        <div class="bg-white rounded-2xl sm:rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <form action="{{ route('teacher.students.store') }}" method="POST">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-4 sm:px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Info Siswa</th>
                                <th class="px-4 sm:px-8 py-5 text-xs text-center font-black text-slate-400 uppercase tracking-widest">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($students as $index => $student)
                                <tr class="hover:bg-slate-50/30 transition-colors">
                                    <td class="px-4 sm:px-8 py-5">
                                        <div class="flex items-center gap-3 sm:gap-4">
                                            <img class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl shadow-sm border border-white" 
                                                 src="https://ui-avatars.com/api/?name={{ urlencode($student->user->name) }}&background=f1f5f9&color=64748b&bold=true" 
                                                 alt="{{ $student->user->name }}">
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-900 leading-tight text-sm sm:text-base truncate max-w-[120px] sm:max-w-none">{{ $student->user->name }}</p>
                                                <p class="text-[9px] sm:text-[10px] font-mono font-bold text-slate-400 mt-0.5 tracking-tighter">{{ $student->nis }}</p>
                                            </div>
                                        </div>
                                        <input type="hidden" name="attendance[{{ $index }}][student_id]" value="{{ $student->id }}">
                                    </td>
                                    <td class="px-4 sm:px-8 py-5">
                                        @php
                                            $currentStatus = $student->attendances->first()->status ?? 'present';
                                        @endphp
                                        <div class="flex items-center justify-center gap-1 sm:gap-4 p-1 bg-slate-50 rounded-xl sm:rounded-2xl w-fit mx-auto border border-slate-100">
                                            @foreach(['present' => 'H', 'sick' => 'S', 'permission' => 'I', 'absent' => 'A'] as $status => $label)
                                                <label class="relative cursor-pointer group">
                                                    <input type="radio" 
                                                           name="attendance[{{ $index }}][status]" 
                                                           value="{{ $status }}" 
                                                           class="peer sr-only"
                                                           {{ $currentStatus == $status ? 'checked' : '' }}>
                                                    <div class="w-8 h-8 sm:w-12 sm:h-12 flex items-center justify-center rounded-lg sm:rounded-xl font-black text-[10px] sm:text-xs transition-all duration-200
                                                                peer-checked:scale-110 peer-checked:shadow-lg
                                                                @if($status == 'present') bg-white text-slate-400 group-hover:bg-emerald-50 group-hover:text-emerald-500 peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:shadow-emerald-200 @endif
                                                                @if($status == 'sick') bg-white text-slate-400 group-hover:bg-amber-50 group-hover:text-amber-500 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:shadow-amber-200 @endif
                                                                @if($status == 'permission') bg-white text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-500 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:shadow-blue-200 @endif
                                                                @if($status == 'absent') bg-white text-slate-400 group-hover:bg-rose-50 group-hover:text-rose-500 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:shadow-rose-200 @endif">
                                                        {{ $label }}
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-4 sm:px-8 py-8 bg-slate-50/50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 sm:gap-8">
                        <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-emerald-500"></div><span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest">Hadir (H)</span></div>
                        <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-amber-500"></div><span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest">Sakit (S)</span></div>
                        <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-blue-500"></div><span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest">Izin (I)</span></div>
                        <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-rose-500"></div><span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest">Alpha (A)</span></div>
                    </div>
                    <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-slate-900 text-white rounded-2xl font-bold shadow-xl shadow-slate-200 hover:bg-black hover:scale-[1.02] active:scale-[0.98] transition-all text-sm sm:text-base">
                        Simpan Kehadiran
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="bg-indigo-50 p-12 rounded-[2.5rem] border-2 border-dashed border-indigo-200 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 mb-6 border-4 border-white shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-2xl font-black text-indigo-900 leading-tight">Akses Dibatasi</h3>
            <p class="text-indigo-600/70 font-bold mt-3 max-w-sm">Anda saat ini belum ditugaskan sebagai "Wali Kelas". Hanya guru yang ditugaskan dapat mencatat kehadiran siswa.</p>
        </div>
    @endif
</div>
@endsection
