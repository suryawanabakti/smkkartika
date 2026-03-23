<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <title>Login Portal | SMK KARTIKA</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full antialiased text-slate-900">
    <div
        class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-emerald-50 via-slate-50 to-white">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center mb-8">
                <div class="relative group">
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-emerald-400 to-rose-400 rounded-3xl blur opacity-20 group-hover:opacity-40 transition duration-1000 group-hover:duration-200">
                    </div>
                    <div
                        class="relative h-24 w-24 bg-white rounded-3xl flex items-center justify-center p-3 shadow-xl border border-emerald-100 overflow-hidden">
                        <img src="{{ asset('logo.png') }}" class="w-full h-full object-contain" alt="Logo">
                    </div>
                </div>
            </div>
            <h2 class="text-center text-4xl font-black text-slate-900 tracking-tighter uppercase italic">
                SMK <span class="text-emerald-600">KARTIKA</span>
            </h2>
            <p
                class="mt-3 text-center text-xs font-bold text-slate-400 uppercase tracking-[0.3em] underline decoration-rose-500/20 underline-offset-8">
                Akses Sistem Absensi Personanal Dan Siswa
            </p>
        </div>

        <div class="mt-12 sm:mx-auto sm:w-full sm:max-w-md">
            <div
                class="bg-white py-12 px-8 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.05)] border border-slate-100 rounded-[2.5rem] sm:px-14 relative overflow-hidden group">
                <div
                    class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent">
                </div>

                <form class="space-y-8" action="{{ route('login') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl animate-shake">
                            <div class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-rose-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs text-rose-600 font-black uppercase tracking-wider">
                                    Akses Gagal: {{ $errors->first() }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-2">
                        <label for="email"
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Email
                            Terdaftar</label>
                        <div class="relative group">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                value="{{ old('email') }}"
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 transition-all duration-300 font-bold"
                                placeholder="name@school.com">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password"
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Kata
                            Sandi</label>
                        <div class="relative group">
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required
                                class="block w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 transition-all duration-300 font-bold"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-5 w-5 text-emerald-600 focus:ring-emerald-500/20 border-slate-200 rounded-lg bg-white transition-all cursor-pointer">
                        <label for="remember"
                            class="ml-3 block text-xs text-slate-500 font-bold uppercase tracking-wider cursor-pointer select-none">
                            Biarkan saya tetap masuk
                        </label>
                    </div>

                    <div>
                        <button type="submit"
                            class="group relative w-full flex justify-center py-5 px-4 border border-transparent rounded-2xl text-sm font-black text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-300 overflow-hidden shadow-lg shadow-emerald-500/20">
                            <span
                                class="absolute inset-0 w-full h-full bg-gradient-to-r from-emerald-400/20 to-rose-400/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700 ease-in-out"></span>
                            <span class="relative flex items-center gap-2 uppercase tracking-widest">
                                Masuk ke Sistem
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>

                <div class="mt-12 pt-8 border-t border-slate-50 text-center">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">
                        &copy; {{ date('Y') }} SMK KARTIKA &bull; Portal Internal
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
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
</body>

</html>
