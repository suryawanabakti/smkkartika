@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Data Kelas</h1>
        <a href="{{ route('admin.classrooms.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
            Tambah Kelas Baru
        </a>
    </div>

    <!-- Filter Card -->
    <div class="p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.classrooms.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cari</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari berdasarkan nama kelas..." 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                >
            </div>
            <div class="w-full md:w-64">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Jurusan</label>
                <select name="major_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                    <option value="">Semua Jurusan</option>
                    @foreach($majors as $major)
                        <option value="{{ $major->id }}" {{ request('major_id') == $major->id ? 'selected' : '' }}>
                            {{ $major->name }} ({{ $major->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                    Filter
                </button>
                @if(request('search') || request('major_id'))
                    <a href="{{ route('admin.classrooms.index') }}" class="px-6 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                        Atur Ulang
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
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jurusan</th>
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Wali Kelas</th>
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Dibuat Pada</th>
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($classRooms as $classRoom)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 sm:px-6 py-4 font-medium text-gray-800">
                                <div>{{ $classRoom->name }}</div>
                                <div class="sm:hidden text-[10px] text-gray-500 mt-0.5">
                                    @if($classRoom->teacher)
                                        {{ $classRoom->teacher->user->name }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-gray-600">
                                <span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-md text-[10px] sm:text-xs font-bold">{{ $classRoom->major->code }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-gray-600 hidden sm:table-cell">
                                @if($classRoom->teacher)
                                    <span class="font-medium">{{ $classRoom->teacher->user->name }}</span>
                                @else
                                    <span class="text-gray-400 italic">Belum Ditentukan</span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-gray-500 hidden md:table-cell">{{ $classRoom->created_at->format('d M Y') }}</td>
                            <td class="px-4 sm:px-6 py-4 text-right space-x-2 sm:space-x-3 whitespace-nowrap">
                                <a href="{{ route('admin.classrooms.edit', $classRoom) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs sm:text-sm">Edit</a>
                                <form action="{{ route('admin.classrooms.destroy', $classRoom) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-xs sm:text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                Tidak ada data kelas ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($classRooms->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $classRooms->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
