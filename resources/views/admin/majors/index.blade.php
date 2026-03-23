@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Data Jurusan</h1>
        <a href="{{ route('admin.majors.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
            Tambah Jurusan Baru
        </a>
    </div>

    <!-- Filter Card -->
    <div class="p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.majors.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari berdasarkan nama atau kode..." 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                >
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                    Filter
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.majors.index') }}" class="px-6 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Jurusan</th>
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Dibuat Pada</th>
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($majors as $major)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 sm:px-6 py-4 font-medium text-gray-800">{{ $major->name }}</td>
                            <td class="px-4 sm:px-6 py-4">
                                <span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-md text-[10px] sm:text-xs font-bold">{{ $major->code }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-gray-500 hidden sm:table-cell">{{ $major->created_at->format('d M Y') }}</td>
                            <td class="px-4 sm:px-6 py-4 text-right space-x-2 sm:space-x-3 whitespace-nowrap">
                                <a href="{{ route('admin.majors.edit', $major) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs sm:text-sm">Edit</a>
                                <form action="{{ route('admin.majors.destroy', $major) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-xs sm:text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                Tidak ada data jurusan ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($majors->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $majors->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
