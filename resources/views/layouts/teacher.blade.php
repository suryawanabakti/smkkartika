<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} Teacher Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full antialiased text-slate-900" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 transition-transform duration-300 transform lg:relative lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center px-6 h-20 border-b border-slate-100 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <span class="text-lg font-extrabold tracking-tight text-slate-800">TEACHER<span class="text-indigo-600">HUB</span></span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
                    <a href="{{ route('teacher.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('teacher.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>

                    <div class="px-4 py-4 text-[10px] font-extrabold tracking-widest text-slate-400 uppercase">Self Service</div>
                    
                    <a href="{{ route('teacher.attendance.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('teacher.attendance.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        My Attendance
                    </a>

                    <div class="px-4 py-4 text-[10px] font-extrabold tracking-widest text-slate-400 uppercase">Class Management</div>

                    <a href="{{ route('teacher.students.index') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('teacher.students.index') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-9-3.833M18.732 7.961a5 5 0 11-9.047-4.461 5 5 0 019.047 4.461z"/></svg>
                        Student Attendance
                    </a>

                    <a href="{{ route('teacher.students.recap') }}" class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all duration-200 {{ request()->routeIs('teacher.students.recap') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m32-2v2m-9-7a4 4 0 11-8 0 4 4 0 018 0zM2 9a4 4 0 118 0 4 4 0 01-8 0zm9 2a4 4 0 100-8 4 4 0 000 8zm-9 4a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Student Recap
                    </a>
                </nav>

                <!-- Sidebar User Info -->
                <div class="p-4 bg-slate-50 border-t border-slate-100 shrink-0">
                    <div class="flex items-center gap-3 px-2 py-2">
                        <img class="w-10 h-10 rounded-xl border-2 border-white shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff" alt="{{ Auth::user()->name }}">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] font-medium text-slate-500 truncate">{{ Auth::user()->teacher->nip ?? 'Teacher Account' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-slate-50">
            <!-- Top Navbar -->
            <header class="sticky top-0 z-40 flex items-center justify-between px-4 sm:px-8 h-20 bg-white/80 backdrop-blur-md border-b border-slate-100">
                <button 
                    @click="sidebarOpen = !sidebarOpen" 
                    class="p-2 text-slate-500 rounded-xl lg:hidden hover:bg-slate-100 hover:text-slate-600 focus:outline-none transition-colors"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <div class="flex items-center space-x-4 sm:space-x-6 lg:ml-auto">
                    <div class="flex flex-col items-end">
                        <span class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ now()->format('l') }}</span>
                        <span class="text-xs sm:text-sm font-bold text-slate-600">{{ now()->format('d M Y') }}</span>
                    </div>
                    <div class="w-px h-8 bg-slate-200"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-3 sm:px-4 py-2 text-xs sm:text-sm font-bold text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <section class="p-4 sm:p-8 max-w-7xl mx-auto">
                @if(session('success'))
                    <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-700 shadow-sm shadow-emerald-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm font-bold">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-8 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 text-rose-700 shadow-sm shadow-rose-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm font-bold">{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </section>
        </main>
    </div>

    <!-- Overlay for mobile sidebar -->
    <div 
        x-show="sidebarOpen" 
        x-cloak
        @click="sidebarOpen = false" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden"
    ></div>
</body>
</html>
