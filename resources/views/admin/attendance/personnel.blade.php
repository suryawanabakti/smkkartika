@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Kehadiran Pegawai</h1>
            <div class="text-sm text-gray-500 font-medium">
                Tanggal: {{ Carbon\Carbon::parse($date)->format('d M Y') }}
            </div>
        </div>

        <!-- Filter Card -->
        <div class="p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
            <form action="{{ route('admin.attendance.personnel') }}" method="GET"
                class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cari Guru</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama..."
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div class="w-full md:w-48">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ $date }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div class="w-full md:w-40">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                        <option value="">Semua Status</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                        <option value="sick" {{ request('status') == 'sick' ? 'selected' : '' }}>Sakit</option>
                        <option value="permission" {{ request('status') == 'permission' ? 'selected' : '' }}>Izin
                        </option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Alfa</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Guru</th>
                            <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">NIP</th>
                            <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Masuk/Keluar</th>
                            <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 sm:px-6 py-4 font-medium text-gray-800">
                                    <div>{{ $attendance->user->name }}</div>
                                    <div class="sm:hidden text-[10px] text-gray-500 font-mono">{{ $attendance->nip }}</div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 hidden sm:table-cell text-gray-600 font-mono text-xs">{{ $attendance->nip }}</td>
                                <td class="px-4 sm:px-6 py-4 text-gray-600">
                                    <div class="flex flex-col sm:flex-row sm:gap-2">
                                        <span class="text-emerald-600 font-bold sm:font-medium">{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}</span>
                                        <span class="hidden sm:inline text-gray-300">/</span>
                                        <span class="text-rose-600 font-bold sm:font-medium">{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4">
                                    @php
                                        $statusClasses = [
                                            'present' => 'bg-green-50 text-green-700 border-green-200',
                                            'sick' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                            'permission' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'absent' => 'bg-red-50 text-red-700 border-red-200',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 py-1 border rounded-md text-[10px] sm:text-xs font-bold uppercase whitespace-nowrap {{ $statusClasses[$attendance->status] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                        {{ $attendance->status == 'present' ? 'Hadir' : ($attendance->status == 'sick' ? 'Sakit' : ($attendance->status == 'permission' ? 'Izin' : 'Alfa')) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    Tidak ada data kehadiran ditemukan untuk tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($attendances->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
