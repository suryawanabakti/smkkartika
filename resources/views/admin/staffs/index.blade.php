@extends('layouts.app')

@section('content')
    <div class="space-y-8 animate-in fade-in duration-700">
        <!-- Header Page -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">MANAJEMEN <span
                        class="text-emerald-600 uppercase">STAFF</span></h1>
                <p
                    class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mt-1 underline decoration-rose-500/30 underline-offset-8">
                    Data Personel Dan Tenaga Kependidikan</p>
            </div>
            <a href="{{ route('admin.staffs.create') }}"
                class="group relative inline-flex items-center justify-center px-6 py-3 font-black text-white bg-emerald-600 rounded-2xl hover:bg-emerald-700 transition-all duration-300 shadow-lg shadow-emerald-600/20 overflow-hidden">
                <span
                    class="absolute inset-0 w-full h-full bg-gradient-to-r from-emerald-400/20 to-rose-400/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700 ease-in-out"></span>
                <span class="relative flex items-center gap-2 uppercase tracking-widest text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Staff
                </span>
            </a>
        </div>

        <!-- Filter & Search Section -->
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.02)]">
            <form action="{{ route('admin.staffs.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within:text-emerald-500 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama, email, atau NIP..."
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 transition-all duration-300">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10">
                        Filter
                    </button>
                    @if (request('search'))
                        <a href="{{ route('admin.staffs.index') }}"
                            class="px-8 py-4 bg-rose-50 text-rose-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-rose-100 transition-all border border-rose-100 flex items-center">
                            Reset
                        </a>
                    @endif
                    <a href="{{ route('admin.staffs.pdf', request()->all()) }}" target="_blank"
                        class="px-8 py-4 bg-rose-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-rose-700 transition-all shadow-lg shadow-rose-600/20 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        CETAK PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Data Table Section -->
        <div
            class="bg-white rounded-[2.5rem] border border-slate-100 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.02)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th
                                class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">
                                Data Personal</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">
                                Identitas (NIP)</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50 hidden md:table-cell">
                                Akses Login</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">
                                L/P</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50 text-right">
                                Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($staffs as $staff)
                            <tr class="group hover:bg-emerald-50/30 transition-all duration-300">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-100 to-slate-100 flex items-center justify-center text-emerald-700 font-black text-xs shadow-sm">
                                            {{ substr($staff->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-black text-slate-900 text-sm tracking-tight">
                                                {{ $staff->user->name }}</div>
                                            <div
                                                class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">
                                                Anggota Staff</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span
                                        class="inline-flex items-center px-3 py-1 bg-white border border-slate-200 rounded-lg text-xs font-black text-slate-600 shadow-sm font-mono">
                                        {{ $staff->nip }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 hidden md:table-cell">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 bg-emerald-50 rounded-lg">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold text-slate-500">{{ $staff->user->email }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="inline-flex items-center px-3 py-1 bg-white border border-slate-200 rounded-lg text-xs font-black text-slate-600 shadow-sm">
                                        {{ $staff->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right space-x-2">
                                    <a href="{{ route('admin.staffs.edit', $staff) }}"
                                        class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all duration-300 shadow-sm">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.staffs.destroy', $staff) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin? Akun sistem juga akan dihapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-[10px] font-black text-rose-500 uppercase tracking-widest hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all duration-300 shadow-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center">
                                            <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-9-3.833M18.732 7.961a5 5 0 11-9.047-4.461 5 5 0 019.047 4.461z" />
                                            </svg>
                                        </div>
                                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Tidak ada
                                            data staff ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($staffs->hasPages())
                <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                    {{ $staffs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
