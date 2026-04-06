@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Data Guru</h1>
        <a href="{{ route('admin.teachers.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
            Tambah Guru Baru
        </a>
    </div>

    <!-- Filter Card -->
    <div class="p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.teachers.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari berdasarkan nama, email, atau NIP..." 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                >
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                    Filter
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.teachers.index') }}" class="px-6 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                        Bersihkan
                    </a>
                @endif
                <a href="{{ route('admin.teachers.pdf', request()->all()) }}" target="_blank" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Cetak PDF</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto text-sm sm:text-base">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">NIP</th>
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Email</th>
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">L/P</th>
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($teachers as $teacher)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 sm:px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $teacher->user->name }}</div>
                                <div class="md:hidden text-[10px] text-gray-500 truncate max-w-[150px]">{{ $teacher->user->email }}</div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-gray-600">
                                <span class="font-mono text-[10px] sm:text-xs bg-gray-100 px-2 py-1 rounded">{{ $teacher->nip }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-gray-500 hidden md:table-cell">{{ $teacher->user->email }}</td>
                            <td class="px-4 sm:px-6 py-4 text-gray-500">{{ $teacher->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td class="px-4 sm:px-6 py-4 text-right space-x-2 sm:space-x-3 whitespace-nowrap">
                                <a href="{{ route('admin.teachers.edit', $teacher) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs sm:text-sm">Edit</a>
                                 <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin? Ini juga akan menghapus akun pengguna terkait.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-xs sm:text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                Tidak ada data guru ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($teachers->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $teachers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
