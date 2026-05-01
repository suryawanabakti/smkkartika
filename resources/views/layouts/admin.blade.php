<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="h-full antialiased text-gray-900 overflow-hidden" x-data="{ 
    sidebarOpen: false,
    now: new Date(),
    init() {
        setInterval(() => { this.now = new Date() }, 1000);
    }
}">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-indigo-700 text-white transition-transform duration-300 transform lg:relative lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-between px-4 h-16 bg-indigo-800 shrink-0">
                    <span class="text-xl font-bold tracking-wider">SMK KARTIKA</span>
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="p-1 text-indigo-200 rounded-md lg:hidden hover:text-white focus:outline-none focus:ring focus:ring-indigo-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    <div class="pt-4 pb-2 text-xs font-semibold tracking-wider text-indigo-300 uppercase">Academic</div>

                    <a href="{{ route('admin.majors.index') }}"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.majors.*') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Majors
                    </a>

                    <a href="{{ route('admin.classrooms.index') }}"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.classrooms.*') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                        Classrooms
                    </a>

                    <div class="pt-4 pb-2 text-xs font-semibold tracking-wider text-indigo-300 uppercase">Users</div>

                    <a href="{{ route('admin.teachers.index') }}"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.teachers.*') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Teachers
                    </a>

                    <a href="{{ route('admin.students.index') }}"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.students.*') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-9-3.833M18.732 7.961a5 5 0 11-9.047-4.461 5 5 0 019.047 4.461z" />
                        </svg>
                        Students
                    </a>

                    <div class="pt-4 pb-2 text-xs font-semibold tracking-wider text-indigo-300 uppercase">Attendance
                    </div>

                    <a href="{{ route('admin.attendance.personnel') }}"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.attendance.personnel') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Daily Personnel
                    </a>

                    <a href="{{ route('admin.attendance.students') }}"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.attendance.students') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Daily Students
                    </a>

                    <div class="pt-4 pb-2 text-xs font-semibold tracking-wider text-indigo-300 uppercase">Reports</div>

                    <a href="{{ route('admin.attendance.personnel.recap') }}"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.attendance.personnel.recap') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m32 4v-2a4 4 0 00-4-4h-5a4 4 0 00-4 4v2m-13-4h13m-13 0a5 5 0 110-10 5 5 0 010 10zm13 0a5 5 0 110-10 5 5 0 010 10z" />
                        </svg>
                        Personnel Recap
                    </a>

                    <a href="{{ route('admin.attendance.students.recap') }}"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.attendance.students.recap') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Student Recap
                    </a>
                    <div class="pt-4 pb-2 text-xs font-semibold tracking-wider text-indigo-300 uppercase">Account</div>
                    <a href="{{ route('profile.index') }}"
                        class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('profile.*') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        My Profile
                    </a>
                </nav>

                <!-- Sidebar Footer -->
                <div class="flex items-center justify-between px-4 py-4 bg-indigo-800 shrink-0">
                    <div class="flex items-center">
                        <img class="w-8 h-8 rounded-full border border-indigo-500"
                            src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Admin">
                        <span class="ml-3 text-sm font-medium">Administrator</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Top Navbar -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b lg:justify-end">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-1 text-gray-500 rounded-md lg:hidden hover:text-gray-600 focus:outline-none focus:ring focus:ring-indigo-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        @php
                            $personnelAlerts = \App\Models\PersonnelAttendance::with('user')
                                ->where('date', \Carbon\Carbon::today())
                                ->whereIn('status', ['absent', 'sick', 'permission'])
                                ->get()
                                ->map(function($item) {
                                    $item->type_label = 'Pegawai';
                                    $item->name = $item->user->name ?? 'Unknown';
                                    return $item;
                                });

                            $studentAlerts = \App\Models\StudentAttendance::with('student.user')
                                ->where('date', \Carbon\Carbon::today())
                                ->whereIn('status', ['absent', 'sick', 'permission'])
                                ->get()
                                ->map(function($item) {
                                    $item->type_label = 'Siswa';
                                    $item->name = $item->student->user->name ?? 'Unknown';
                                    return $item;
                                });

                            $todayAlerts = $personnelAlerts->concat($studentAlerts)->sortByDesc('created_at')->take(10)->values();
                        @endphp
                        <button @click="open = !open" @click.away="open = false" class="relative p-2 text-gray-400 hover:text-indigo-600 transition-colors rounded-full hover:bg-indigo-50 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if($todayAlerts->count() > 0)
                                <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500 border-2 border-white"></span>
                                </span>
                            @endif
                        </button>

                        <div x-show="open" x-cloak
                            class="absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden origin-top-right"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95">
                            <div class="px-5 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-800">Notifikasi Hari Ini</h3>
                                    <p class="text-[10px] text-gray-500 font-medium mt-0.5">Pegawai & Siswa</p>
                                </div>
                                @if($todayAlerts->count() > 0)
                                    <span class="text-xs font-black text-rose-600 bg-rose-100 px-2 py-1 rounded-lg border border-rose-200">{{ $todayAlerts->count() }} Baru</span>
                                @endif
                            </div>
                            <div class="max-h-96 overflow-y-auto divide-y divide-gray-50">
                                @forelse($todayAlerts as $alert)
                                    <div class="px-5 py-4 hover:bg-gray-50/50 transition-colors">
                                        <div class="flex items-start gap-4">
                                            <div class="shrink-0 mt-1">
                                                @if($alert->status == 'sick')
                                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-amber-50 border border-amber-100 text-amber-600 shadow-sm">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    </div>
                                                @elseif($alert->status == 'permission')
                                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 shadow-sm">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    </div>
                                                @else
                                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-rose-50 border border-rose-100 text-rose-600 shadow-sm">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-2">
                                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $alert->name }}</p>
                                                    <span class="text-[9px] font-black uppercase tracking-widest text-gray-400 shrink-0">{{ $alert->created_at->format('H:i') }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 mt-1.5">
                                                    <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md">{{ $alert->type_label }}</span>
                                                    <span class="text-[10px] font-black uppercase tracking-wider {{ $alert->status == 'sick' ? 'text-amber-600' : ($alert->status == 'permission' ? 'text-indigo-600' : 'text-rose-600') }}">
                                                        {{ $alert->status == 'sick' ? 'Sakit' : ($alert->status == 'permission' ? 'Izin' : 'Alfa') }}
                                                    </span>
                                                </div>
                                                @if($alert->description)
                                                    <p class="text-xs text-gray-500 mt-2 italic line-clamp-1 border-l-2 border-gray-200 pl-2">"{{ $alert->description }}"</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-5 py-10 flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-500">Semua Hadir</p>
                                        <p class="text-xs text-gray-400 mt-1">Belum ada catatan sakit, izin, atau alfa.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest hidden sm:inline" 
                              x-text="now.toLocaleDateString('en-US', { weekday: 'long' })"></span>
                        <span class="text-sm font-bold text-gray-700" 
                              x-text="now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })"></span>
                        <span class="text-xs font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg border border-indigo-100 shadow-sm" 
                              x-text="now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })"></span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-sm font-medium text-red-600 hover:text-red-700">Logout</button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <section class="p-4 sm:p-6">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </section>
        </main>
    </div>

    <!-- Overlay for mobile sidebar -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-black opacity-50 lg:hidden"></div>
</body>

</html>
