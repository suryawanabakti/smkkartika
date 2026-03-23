@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Data Siswa</h1>
            <a href="{{ route('admin.students.create') }}"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                Tambah Siswa Baru
            </a>
        </div>

        <!-- Filter Card -->
        <div class="p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
            <form action="{{ route('admin.students.index') }}" method="GET"
                class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama, email, atau NIS..."
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div class="w-full md:w-64">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Kelas</label>
                    <select name="class_id"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        <option value="">Semua Kelas</option>
                        @foreach ($classRooms as $classRoom)
                            <option value="{{ $classRoom->id }}"
                                {{ request('class_id') == $classRoom->id ? 'selected' : '' }}>
                                {{ $classRoom->name }} ({{ $classRoom->major->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                        Filter
                    </button>
                    @if (request('search') || request('class_id'))
                        <a href="{{ route('admin.students.index') }}"
                            class="px-6 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                            Bersihkan
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
                            <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">NIS</th>
                            <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($students as $student)
                            <tr class="hover:bg-gray-50 transition-colors text-sm">
                                <td class="px-4 sm:px-6 py-4 font-medium text-gray-800">
                                    <div class="flex flex-col sm:block">
                                        {{ $student->user->name }}
                                        <span class="sm:hidden text-[10px] text-gray-500 font-mono">{{ $student->nis }}</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 hidden sm:table-cell">
                                    <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $student->nis }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-4">
                                    <span
                                        class="px-2 py-1 bg-blue-50 text-blue-600 rounded-md text-[10px] sm:text-xs font-bold">{{ $student->classRoom->name }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-right space-x-2 sm:space-x-3 whitespace-nowrap">
                                    <a href="{{ route('admin.students.edit', $student) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium text-xs sm:text-sm">Edit</a>
                                        <form action="{{ route('admin.students.destroy', $student) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Apakah Anda yakin? Ini juga akan menghapus akun pengguna terkait.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 font-medium text-xs sm:text-sm">Hapus</button>
                                        </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    Tidak ada data siswa ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($students->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
