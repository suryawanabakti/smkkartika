@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Rekap Mengajar Hari Ini</h1>
            <p class="text-gray-600">{{ $dayName }}, {{ now()->translatedFormat('d F Y') }}</p>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($schedules as $schedule)
                @php
                    $attendance = $todayAttendances->get($schedule->id);
                @endphp
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-md text-xs font-semibold">
                                Periode {{ $schedule->period_start }} - {{ $schedule->period_end }}
                            </span>
                            @if($attendance)
                                <span class="px-2 py-1 bg-green-50 text-green-700 rounded-md text-xs font-semibold flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Sudah Absen
                                </span>
                            @else
                                <span class="px-2 py-1 bg-yellow-50 text-yellow-700 rounded-md text-xs font-semibold">
                                    Belum Absen
                                </span>
                            @endif
                        </div>

                        <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $schedule->subject }}</h3>
                        <p class="text-sm text-gray-600 mb-4">Kelas: <span class="font-semibold text-gray-800">{{ $schedule->classRoom->name }}</span></p>

                        @if($attendance)
                            <div class="mt-4 pt-4 border-t border-gray-50">
                                <p class="text-xs text-gray-500 mb-1">Waktu Absen:</p>
                                <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') }} WIB</p>
                                @if($attendance->notes)
                                    <p class="text-xs text-gray-500 mt-2 mb-1">Catatan/Jurnal:</p>
                                    <p class="text-sm text-gray-700 italic">"{{ $attendance->notes }}"</p>
                                @endif
                            </div>
                        @else
                            <form action="{{ route('teacher.teaching_recap.store') }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                
                                <div class="mb-4">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Jurnal/Materi (Opsional)</label>
                                    <textarea name="notes" rows="2" class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Apa yang diajarkan hari ini?"></textarea>
                                </div>

                                <button type="submit" class="w-full py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-semibold text-sm">
                                    Absen Sekarang
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-500">Tidak ada jadwal mengajar untuk Anda hari ini ({{ $dayName }}).</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
