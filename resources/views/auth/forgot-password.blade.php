<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <title>Lupa Sandi | SMK KARTIKA XX-1 MAKASSAR</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
            20%, 40%, 60%, 80% { transform: translateX(2px); }
        }
        .animate-shake { animation: shake 0.5s cubic-bezier(.36, .07, .19, .97) both; }
    </style>
</head>

<body class="h-full antialiased font-[Inter]">
    <div class="min-h-screen flex">
        <!-- Left Side: Image & Branding -->
        <div class="hidden lg:flex lg:w-3/5 relative overflow-hidden bg-emerald-950">
            <img src="{{ asset('bg-login.png') }}"
                class="absolute inset-0 w-full h-full object-cover object-center opacity-70 transform hover:scale-105 transition-transform duration-[10s] ease-out"
                alt="SMK Kartika Entrance">
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-950/90 via-emerald-950/30 to-transparent"></div>
            
            <div class="relative z-10 w-full h-full flex flex-col justify-between p-16">
                <div class="animate-float">
                    <img src="{{ asset('logo.png') }}"
                        class="h-20 w-auto bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/20 shadow-2xl"
                        alt="Logo">
                </div>
                <div class="max-w-2xl">
                    <h1 class="text-6xl font-black text-white tracking-tighter leading-tight uppercase">
                        PEMULIHAN <span class="text-emerald-400">AKUN</span>
                    </h1>
                    <div class="mt-8 h-1 w-32 bg-emerald-400 rounded-full"></div>
                    <p class="mt-8 text-xl text-emerald-50/80 font-medium leading-relaxed">
                        Lupa kata sandi Anda? Jangan khawatir.<br>
                        Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang aplikasi Anda.
                    </p>
                </div>
                <div class="text-emerald-300/50 text-sm font-bold tracking-widest uppercase">
                    &copy; {{ date('Y') }} SMK KARTIKA &bull; Excellence in Education
                </div>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full lg:w-2/5 flex flex-col justify-center items-center p-8 sm:p-12 md:p-20 bg-white relative">
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
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight">Lupa Sandi?</h2>
                    <p class="mt-3 text-slate-500 font-medium">Kami akan mengirimkan instruksi pemulihan ke email Anda.</p>
                </div>

                @if (session('status'))
                    <div class="mb-8 p-5 bg-emerald-50 border border-emerald-100 rounded-2xl">
                        <div class="flex items-center gap-4 text-emerald-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm font-bold leading-tight">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="space-y-2">
                        <label for="email" class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">E-mail Terdaftar</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" required value="{{ old('email') }}"
                                class="block w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all duration-300 font-bold"
                                placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-xs text-rose-500 font-bold animate-shake">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="group relative w-full flex justify-center py-5 px-4 border border-transparent rounded-2xl text-sm font-black text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/30 transition-all duration-300 overflow-hidden shadow-xl shadow-emerald-500/20">
                            <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-emerald-400/20 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                            <span class="relative flex items-center gap-3 uppercase tracking-[0.2em] font-black">
                                Kirim Tautan Reset
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </span>
                        </button>
                    </div>

                    <div class="text-center pt-4">
                        <a href="{{ route('login') }}" class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-emerald-600 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
