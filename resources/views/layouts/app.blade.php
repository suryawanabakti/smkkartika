<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <title>{{ config('app.name', 'Laravel') }} - {{ Auth::user()->role->name ?? 'Portal' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-full antialiased text-slate-900" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-72 bg-white text-slate-600 transition-transform duration-300 transform lg:relative lg:translate-x-0 border-r border-slate-200 shadow-xl shadow-slate-200/50"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center px-6 h-24 border-b border-slate-100 shrink-0 bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center p-2 shadow-sm border border-slate-200 group overflow-hidden">
                            <img src="{{ asset('logo.png') }}"
                                class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500"
                                alt="Logo">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg font-black tracking-tight text-slate-900 leading-none">SMK <span
                                    class="text-emerald-600">KARTIKA</span></span>
                            <span
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1 underline decoration-rose-500/50 underline-offset-4">Management</span>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-8 space-y-1 overflow-y-auto custom-scrollbar">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="flex lg:hidden items-center w-full px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 
    text-slate-500 hover:bg-slate-50 hover:text-emerald-600">

                        <!-- Icon -->
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>

                        <span x-text="sidebarOpen ? 'Tutup Sidebar' : 'Buka Sidebar'"></span>
                    </button>
                    @if (Auth::user()->isAdmin())
                        <!-- Admin Navigation -->
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard Admin
                        </a>

                        <div class="px-4 py-4 text-[10px] font-extrabold tracking-widest text-slate-500 uppercase">
                            Akademik</div>
                        <a href="{{ route('admin.majors.index') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.majors.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Data Jurusan
                        </a>
                        <a href="{{ route('admin.classrooms.index') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.classrooms.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                            Data Kelas
                        </a>

                        <div class="px-4 py-4 text-[10px] font-extrabold tracking-widest text-slate-500 uppercase">
                            Pengguna</div>
                        <a href="{{ route('admin.teachers.index') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.teachers.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Data Guru
                        </a>
                        <a href="{{ route('admin.students.index') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.students.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-9-3.833M18.732 7.961a5 5 0 11-9.047-4.461 5 5 0 019.047 4.461z" />
                            </svg>
                            Data Siswa
                        </a>
                        <a href="{{ route('admin.staffs.index') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.staffs.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Data Staff
                        </a>
                        <a href="{{ route('admin.admins.index') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.admins.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A3.323 3.323 0 0010.605 7.042L9.31 5.747a3.323 3.323 0 00-4.699 4.699l1.295 1.295a3.323 3.323 0 004.016 5.618l4.699-4.699z" />
                            </svg>
                            Data Admin
                        </a>

                        <div class="px-4 py-4 text-[10px] font-extrabold tracking-widest text-slate-500 uppercase">
                            Presensi</div>
                        <a href="{{ route('admin.attendance.personnel') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.attendance.personnel') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Harian Pegawai
                        </a>
                        <a href="{{ route('admin.attendance.students') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.attendance.students') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Harian Siswa
                        </a>

                        <div class="px-4 py-4 text-[10px] font-extrabold tracking-widest text-slate-500 uppercase">
                            Laporan</div>
                        <a href="{{ route('admin.attendance.personnel.recap') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.attendance.personnel.recap') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m32 4v-2a4 4 0 00-4-4h-5a4 4 0 00-4 4v2m-13-4h13m-13 0a5 5 0 110-10 5 5 0 010 10zm13 0a5 5 0 110-10 5 5 0 010 10z" />
                            </svg>
                            Rekap Pegawai
                        </a>
                        <a href="{{ route('admin.attendance.students.recap') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.attendance.students.recap') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Rekap Siswa
                        </a>

                        <div class="px-4 py-4 text-[10px] font-extrabold tracking-widest text-slate-500 uppercase">
                            Pengaturan</div>
                        <a href="{{ route('admin.settings.school') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Lokasi Sekolah
                        </a>
                    @endif

                    @if (Auth::user()->isStudent())
                        <!-- Student Navigation -->
                        <a href="{{ route('student.dashboard') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('student.dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard Siswa
                        </a>

                        <div class="px-4 py-4 text-[10px] font-extrabold tracking-widest text-slate-500 uppercase">
                            Catatan Saya</div>
                        <a href="{{ route('student.attendance.index') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('student.attendance.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Kehadiran Saya
                        </a>
                    @endif
                    @if (Auth::user()->isTeacher())
                        <!-- Teacher Navigation -->
                        <a href="{{ route('teacher.dashboard') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('teacher.dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard Guru
                        </a>

                        <div class="px-4 py-4 text-[10px] font-extrabold tracking-widest text-slate-500 uppercase">
                            Layanan Mandiri</div>
                        <a href="{{ route('teacher.attendance.index') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('teacher.attendance.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Kehadiran Saya
                        </a>

                        <div class="px-4 py-4 text-[10px] font-extrabold tracking-widest text-slate-500 uppercase">
                            Manajemen Kelas</div>
                        <a href="{{ route('teacher.students.index') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('teacher.students.index') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-9-3.833M18.732 7.961a5 5 0 11-9.047-4.461 5 5 0 019.047 4.461z" />
                            </svg>
                            Absensi Siswa
                        </a>
                        <a href="{{ route('teacher.students.recap') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('teacher.students.recap') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m32-2v2m-9-7a4 4 0 11-8 0 4 4 0 018 0zM2 9a4 4 0 118 0 4 4 0 01-8 0zm9 2a4 4 0 100-8 4 4 0 000 8zm-9 4a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Rekap Siswa
                        </a>
                    @endif

                    @if (Auth::user()->isStaff())
                        <!-- Staff Navigation -->
                        <a href="{{ route('staff.dashboard') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('staff.dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard Staff
                        </a>

                        <div class="px-4 py-4 text-[10px] font-extrabold tracking-widest text-slate-500 uppercase">
                            Layanan Mandiri</div>
                        <a href="{{ route('staff.attendance.index') }}"
                            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('staff.attendance.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Kehadiran Saya
                        </a>
                    @endif

                </nav>

                <!-- Sidebar User Info -->
                <div class="p-4 bg-slate-50 border-t border-slate-100 shrink-0">
                    <div class="flex items-center gap-4 px-2 py-3 rounded-2xl group active:scale-95 transition-all">
                        <img class="w-12 h-12 rounded-xl border-2 border-white shadow-md transition-transform group-hover:scale-105"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10b981&color=fff&bold=true"
                            alt="{{ Auth::user()->name }}">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-black text-slate-900 truncate">{{ Auth::user()->name }}</p>
                            <p
                                class="text-[10px] font-bold text-emerald-600 truncate uppercase tracking-widest mt-0.5">
                                {{ Auth::user()->role->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-slate-50 relative custom-scrollbar">
            <!-- Top Navbar -->
            <header
                class="sticky top-0 z-40 flex items-center justify-between px-4 sm:px-8 h-20 bg-white/80 backdrop-blur-md border-b border-slate-100">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 text-slate-500 rounded-xl lg:hidden hover:bg-slate-100 hover:text-slate-600 focus:outline-none transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex items-center space-x-4 sm:space-x-6 lg:ml-auto">
                    <div class="flex flex-col items-end">
                        <span
                            class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ now()->format('l') }}</span>
                        <span class="text-xs sm:text-sm font-bold text-slate-600">{{ now()->format('d M Y') }}</span>
                    </div>
                    <div class="w-px h-8 bg-slate-200"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 px-3 sm:px-4 py-2 text-xs sm:text-sm font-bold text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <section class="p-4 sm:p-8 max-w-7xl mx-auto">
                @if (session('success'))
                    <div
                        class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-700 shadow-sm shadow-emerald-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm font-bold">{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-8 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 text-rose-700 shadow-sm shadow-rose-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm font-bold">{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </section>
        </main>
    </div>
    <!-- Overlay for mobile sidebar -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-black opacity-50 lg:hidden"></div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</body>

</html>
