<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <title>Login Portal | SMK KARTIKA XX-1 MAKASSAR</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .login-bg-overlay {
            background: linear-gradient(to right, rgba(6, 78, 59, 0.4), rgba(6, 78, 59, 1));
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-2px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(2px);
            }
        }

        .animate-shake {
            animation: shake 0.5s cubic-bezier(.36, .07, .19, .97) both;
        }
    </style>
</head>

<body class="h-full antialiased font-[Inter]">
    <div class="min-h-screen flex">
        <!-- Left Side: Image & Branding (Hidden on mobile) -->
        <div class="hidden lg:flex lg:w-3/5 relative overflow-hidden bg-emerald-950">
            <!-- Background Image -->
            <img src="{{ asset('bg-login.png') }}"
                class="absolute inset-0 w-full h-full object-cover object-center opacity-70 transform hover:scale-105 transition-transform duration-[10s] ease-out"
                alt="SMK Kartika Entrance">

            <!-- Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-950/90 via-emerald-950/30 to-transparent"></div>

            <!-- Content on Image -->
            <div class="relative z-10 w-full h-full flex flex-col justify-between p-16">
                <div class="animate-float">
                    <img src="{{ asset('logo.png') }}"
                        class="h-20 w-auto bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/20 shadow-2xl"
                        alt="Logo">
                </div>

                <div class="max-w-2xl">
                    <h1 class="text-6xl font-black text-white tracking-tighter leading-tight uppercase">
                        SMK <span class="text-emerald-400">KARTIKA</span><br>
                        <span class="text-3xl text-emerald-100/80 font-bold tracking-widest uppercase">XX-1
                            MAKASSAR</span>
                    </h1>
                    <div class="mt-8 h-1 w-32 bg-emerald-400 rounded-full"></div>
                    <p class="mt-8 text-xl text-emerald-50/80 font-medium leading-relaxed">
                        Selamat Datang di Portal Internal.<br>
                        Sistem manajemen kehadiran terpadu untuk pendidik dan peserta didik.
                    </p>
                </div>

                <div class="text-emerald-300/50 text-sm font-bold tracking-widest uppercase">
                    &copy; {{ date('Y') }} SMK KARTIKA &bull; Excellence in Education
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-2/5 flex flex-col justify-center items-center p-8 sm:p-12 md:p-20 bg-white relative">
            <!-- Mobile Header (Only visible on small screens) -->
            <div class="lg:hidden absolute top-12 left-0 w-full px-8 text-center">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('logo.png') }}" class="h-16 w-auto" alt="Logo">
                </div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tighter">
                    SMK <span class="text-emerald-600">KARTIKA</span>
                </h2>
            </div>

            <div class="w-full max-w-md">
                <div class="mb-10 lg:text-left text-center">
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight">Masuk Portal</h2>
                    <p class="mt-3 text-slate-500 font-medium">Sila gunakan akun terdaftar Anda untuk mengakses sistem.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-8 p-5 bg-rose-50 border border-rose-100 rounded-2xl animate-shake">
                        <div class="flex items-center gap-4">
                            <div class="p-2 bg-rose-100 rounded-xl text-rose-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-rose-500 font-black uppercase tracking-widest mb-1">Akses Ditolak
                                </p>
                                <p class="text-sm font-bold text-rose-700 leading-tight">{{ $errors->first() }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="space-y-2">
                        <label for="email"
                            class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">E-mail
                            Terdaftar</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                value="{{ old('email') }}"
                                class="block w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all duration-300 font-bold"
                                placeholder="nama@email.com">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password"
                            class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Kata
                            Sandi</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required
                                class="block w-full pl-12 pr-12 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all duration-300 font-bold"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center">

                        </div>
                        <a href="{{ route('password.request') }}"
                            class="text-xs font-black text-emerald-600 uppercase tracking-widest hover:text-emerald-700 transition-colors">
                            Lupa Sandi?
                        </a>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="group relative w-full flex justify-center py-5 px-4 border border-transparent rounded-2xl text-sm font-black text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/30 transition-all duration-300 overflow-hidden shadow-xl shadow-emerald-500/20">
                            <span
                                class="absolute inset-0 w-full h-full bg-gradient-to-r from-emerald-400/20 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                            <span class="relative flex items-center gap-3 uppercase tracking-[0.2em] font-black">
                                Masuk Sistem
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>

                <div class="mt-16 text-center lg:hidden">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">
                        &copy; {{ date('Y') }} SMK KARTIKA &bull; Makassar
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
