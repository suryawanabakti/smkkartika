@extends('layouts.app')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-bold uppercase tracking-widest border border-indigo-100 italic">Portal Siswa</span>
                <span class="w-1 h-1 bg-indigo-200 rounded-full"></span>
                <span class="text-sm font-bold text-slate-500">{{ $student->classRoom->name }}</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Selamat datang kembali, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
            <p class="text-slate-500 font-medium mt-1">Periksa kehadiran harian dan performa bulanan Anda di sini.</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Present -->
        <div class="group p-8 bg-white border border-slate-100 rounded-[2.5rem] shadow-sm shadow-slate-200/50 hover:shadow-xl hover:shadow-emerald-100/50 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Hadir</p>
            <h3 class="text-4xl font-black text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">{{ $monthlyStats['present'] }} <span class="text-base text-slate-400 font-bold tracking-normal ml-1">Hari</span></h3>
        </div>

        <!-- Sick -->
        <div class="group p-8 bg-white border border-slate-100 rounded-[2.5rem] shadow-sm shadow-slate-200/50 hover:shadow-xl hover:shadow-amber-100/50 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Sakit</p>
            <h3 class="text-4xl font-black text-slate-900 tracking-tight group-hover:text-amber-600 transition-colors">{{ $monthlyStats['sick'] }} <span class="text-base text-slate-400 font-bold tracking-normal ml-1">Hari</span></h3>
        </div>

        <!-- Permission -->
        <div class="group p-8 bg-white border border-slate-100 rounded-[2.5rem] shadow-sm shadow-slate-200/50 hover:shadow-xl hover:shadow-blue-100/50 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Izin</p>
            <h3 class="text-4xl font-black text-slate-900 tracking-tight group-hover:text-blue-600 transition-colors">{{ $monthlyStats['permission'] }} <span class="text-base text-slate-400 font-bold tracking-normal ml-1">Hari</span></h3>
        </div>

        <!-- Absent -->
        <div class="group p-8 bg-white border border-slate-100 rounded-[2.5rem] shadow-sm shadow-slate-200/50 hover:shadow-xl hover:shadow-rose-100/50 hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center group-hover:bg-rose-500 group-hover:text-white transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Alfa / Tanpa Keterangan</p>
            <h3 class="text-4xl font-black text-slate-900 tracking-tight group-hover:text-rose-600 transition-colors">{{ $monthlyStats['absent'] }} <span class="text-base text-slate-400 font-bold tracking-normal ml-1">Hari</span></h3>
        </div>
    </div>

    <!-- Today's Status & Detailed History Link -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-white">
        <!-- Today's Presence Card -->
        <div class="lg:col-span-2 relative overflow-hidden bg-slate-900 rounded-[3rem] p-10 shadow-2xl shadow-slate-200">
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/10">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 leading-none mb-1">Status Hari Ini</p>
                        <h2 class="text-xl font-black">{{ now()->translatedFormat('l, d M Y') }}</h2>
                    </div>
                </div>

                <div class="flex items-center gap-8">
                    <div class="flex-1">
                        @if($todayAttendance)
                            <div class="flex items-center gap-4">
                                @switch($todayAttendance->status)
                                    @case('present')
                                        <div class="px-6 py-3 rounded-2xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-black text-lg">Hadir</div>
                                        @break
                                    @case('sick')
                                        <div class="px-6 py-3 rounded-2xl bg-amber-500/20 text-amber-400 border border-amber-500/30 font-black text-lg">Sakit</div>
                                        @break
                                    @case('permission')
                                        <div class="px-6 py-3 rounded-2xl bg-blue-500/20 text-blue-400 border border-blue-500/30 font-black text-lg">Izin</div>
                                        @break
                                    @case('absent')
                                        <div class="px-6 py-3 rounded-2xl bg-rose-500/20 text-rose-400 border border-rose-500/30 font-black text-lg">Alfa</div>
                                        @break
                                @endswitch
                                <p class="text-slate-400 font-medium italic">Telah diverifikasi oleh guru</p>
                            </div>
                        @else
                            <div class="px-6 py-3 rounded-2xl bg-slate-800 text-slate-400 border border-slate-700 font-black text-lg inline-block">Belum Tercatat</div>
                            <p class="mt-4 text-slate-500 font-medium">Harap tunggu guru Anda untuk mencatat kehadiran hari ini.</p>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="bg-indigo-600 rounded-[3rem] p-10 flex flex-col justify-between shadow-2xl shadow-indigo-100 overflow-hidden relative group">
            <div class="relative z-10">
                <h3 class="text-2xl font-black mb-4">Catatan Detail</h3>
                <p class="text-indigo-100/70 font-bold mb-8">Lihat riwayat kehadiran lengkap Anda dengan memfilter bulan dan tahun.</p>
                <a href="{{ route('student.attendance.index') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-white text-indigo-600 rounded-2xl font-black hover:bg-slate-50 transition-all shadow-xl shadow-indigo-700/20">
                    Lihat Riwayat
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -right-8 bottom-0 w-32 h-32 bg-white/10 rounded-full translate-y-1/2 translate-x-1/2"></div>
        </div>
    </div>
</div>
@endsection
