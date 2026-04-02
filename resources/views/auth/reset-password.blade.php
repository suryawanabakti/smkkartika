<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <title>Atur Ulang Sandi | SMK KARTIKA XX-1 MAKASSAR</title>

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
                        ATUR ULANG <span class="text-emerald-400">SANDI</span>
                    </h1>
                    <div class="mt-8 h-1 w-32 bg-emerald-400 rounded-full"></div>
                    <p class="mt-8 text-xl text-emerald-50/80 font-medium leading-relaxed">
                        Hampir selesai.<br>
                        Silakan masukkan kata sandi baru Anda untuk mengamankan kembali akun Anda.
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
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight">Sandi Baru</h2>
                    <p class="mt-3 text-slate-500 font-medium">Buat kata sandi yang kuat dan mudah diingat.</p>
                </div>

                <form class="space-y-6" action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="space-y-2">
                        <label for="email" class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">E-mail</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" required value="{{ old('email', $email) }}"
                                class="block w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all duration-300 font-bold"
                                placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-xs text-rose-500 font-bold animate-shake">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Kata Sandi Baru</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" required
                                class="block w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all duration-300 font-bold"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-2 text-xs text-rose-500 font-bold animate-shake">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Konfirmasi Sandi Baru</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="block w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all duration-300 font-bold"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="group relative w-full flex justify-center py-5 px-4 border border-transparent rounded-2xl text-sm font-black text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/30 transition-all duration-300 overflow-hidden shadow-xl shadow-emerald-500/20">
                            <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-emerald-400/20 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                            <span class="relative flex items-center gap-3 uppercase tracking-[0.2em] font-black">
                                Simpan Sandi Baru
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
