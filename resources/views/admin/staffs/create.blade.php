@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">TAMBAH <span
                        class="text-emerald-600 uppercase">STAFF</span></h1>
                <p
                    class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1 underline decoration-rose-500/30 underline-offset-8">
                    Registrasi Akun Personel Baru</p>
            </div>
            <a href="{{ route('admin.staffs.index') }}"
                class="flex items-center gap-2 text-slate-400 hover:text-emerald-600 font-black text-[10px] uppercase tracking-widest transition-colors group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div
            class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.02)] relative overflow-hidden">
            <div
                class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-emerald-500/20 to-transparent">
            </div>

            <form action="{{ route('admin.staffs.store') }}" method="POST" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2 space-y-2">
                        <label for="name"
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Nama Lengkap
                            Staff</label>
                        <div class="relative group">
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                placeholder="Contoh: Budi Santoso, S.Kom"
                                class="block w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 transition-all duration-300 @error('name') border-rose-500 bg-rose-50/30 @enderror"
                                required>
                        </div>
                        @error('name')
                            <p class="mt-2 text-[10px] text-rose-500 font-black uppercase tracking-wider pl-1">
                                {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="nip"
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Nomor Induk
                            Pegawai (NIP)</label>
                        <input type="text" name="nip" id="nip" value="{{ old('nip') }}"
                            placeholder="19XXXXXXXXXXXXX"
                            class="block w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 transition-all duration-300 font-mono @error('nip') border-rose-500 bg-rose-50/30 @enderror"
                            required>
                        @error('nip')
                            <p class="mt-2 text-[10px] text-rose-500 font-black uppercase tracking-wider pl-1">
                                {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email"
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Email Sekolah
                            / Personal</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            placeholder="staff@smkkartika.sch.id"
                            class="block w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 transition-all duration-300 @error('email') border-rose-500 bg-rose-50/30 @enderror"
                            required>
                        @error('email')
                            <p class="mt-2 text-[10px] text-rose-500 font-black uppercase tracking-wider pl-1">
                                {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label for="password"
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Kata Sandi
                            Sistem</label>
                        <input type="password" name="password" id="password" placeholder="Minimal 8 karakter"
                            class="block w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 transition-all duration-300 @error('password') border-rose-500 bg-rose-50/30 @enderror"
                            required>
                        @error('password')
                            <p class="mt-2 text-[10px] text-rose-500 font-black uppercase tracking-wider pl-1">
                                {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit"
                        class="group relative w-full flex justify-center py-5 px-4 border border-transparent rounded-2xl text-sm font-black text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-300 overflow-hidden shadow-lg shadow-emerald-500/20">
                        <span
                            class="absolute inset-0 w-full h-full bg-gradient-to-r from-emerald-400/20 to-rose-400/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700 ease-in-out"></span>
                        <span class="relative flex items-center gap-2 uppercase tracking-widest text-xs">
                            Simpan Data Staff
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
