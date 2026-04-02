@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Data Administrator</h1>
        <a href="{{ route('admin.admins.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
            Tambah Admin Baru
        </a>
    </div>

    <!-- Filter Card -->
    <div class="p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.admins.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari berdasarkan nama atau email..." 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                >
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                    Filter
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.admins.index') }}" class="px-6 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                        Bersihkan
                    </a>
                @endif
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
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Email</th>
                        <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($admins as $admin)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 sm:px-6 py-4 font-medium text-gray-800">
                                {{ $admin->name }}
                                <div class="md:hidden text-[10px] text-gray-500 truncate max-w-[200px]">{{ $admin->email }}</div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-gray-500 hidden md:table-cell">{{ $admin->email }}</td>
                            <td class="px-4 sm:px-6 py-4 text-right space-x-2 sm:space-x-3 whitespace-nowrap">
                                <a href="{{ route('admin.admins.edit', $admin) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs sm:text-sm">Edit</a>
                                @if(auth()->id() !== $admin->id)
                                 <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-xs sm:text-sm">Hapus</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                Tidak ada data admin ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($admins->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $admins->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
