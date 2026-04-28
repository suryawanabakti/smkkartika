@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ 
    editMode: false,
    showModal: false,
    selectedUser: '',
    selectedUserId: '',
    selectedDate: '',
    selectedStatus: 'present',
    selectedDescription: '',
    openEdit(userId, userName, date, status, description) {
        this.selectedUserId = userId;
        this.selectedUser = userName;
        this.selectedDate = date;
        this.selectedStatus = status || 'present';
        this.selectedDescription = description || '';
        this.showModal = true;
    }
}">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-800">Rekap Kehadiran Pegawai</h1>
            <p class="text-xs sm:text-sm text-gray-500">Tinjauan bulanan kehadiran staf</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <button @click="editMode = !editMode" 
                :class="editMode ? 'bg-amber-100 text-amber-700 border-amber-200' : 'bg-white text-gray-600 border-gray-100'"
                class="px-4 py-2 rounded-xl border shadow-sm text-xs font-bold transition-all flex items-center gap-2">
                <svg class="w-4 h-4" :class="editMode ? 'text-amber-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span x-text="editMode ? 'Matikan Mode Edit' : 'Aktifkan Mode Edit'"></span>
            </button>

            <div class="px-4 py-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                <form action="{{ route('admin.attendance.personnel.recap') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bulan</label>
                        <select name="month" class="px-2 py-1.5 border border-gray-100 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 font-bold">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tahun</label>
                        <select name="year" class="px-2 py-1.5 border border-gray-100 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 font-bold">
                            @foreach(range(date('Y') - 5, date('Y') + 1) as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-1.5 bg-gray-800 text-white rounded-lg text-xs font-bold hover:bg-gray-900 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('admin.attendance.personnel.recap.pdf', request()->all()) }}" target="_blank" class="px-4 py-1.5 bg-red-600 text-white rounded-lg text-xs font-bold hover:bg-red-700 transition-colors flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Cetak PDF
                    </a>
                </form>
            </div>
        </div>
    </div>

    <!-- Recap Grid -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-gray-100">
                        <th rowspan="2" class="px-6 py-4 text-left font-bold text-slate-600 uppercase tracking-tight sticky left-0 bg-slate-50 z-20 border-r border-gray-100 min-w-[220px]">
                            NAMA / NIP
                        </th>
                        @for($i = 1; $i <= $daysInMonth; $i++)
                            @php
                                $date = $startDate->copy()->day($i);
                                $isSunday = $date->isSunday();
                            @endphp
                            <th class="px-1 py-2 text-center font-bold border-r border-gray-50 {{ $isSunday ? 'bg-red-50 text-red-500' : 'text-slate-500' }}">
                                {{ sprintf('%02d', $i) }}
                            </th>
                        @endfor
                    </tr>
                    <tr class="bg-slate-50/50 border-b border-gray-100">
                        @for($i = 1; $i <= $daysInMonth; $i++)
                            @php
                                $date = $startDate->copy()->day($i);
                                $isSunday = $date->isSunday();
                            @endphp
                            <th class="px-1 py-1 text-center font-semibold border-r border-gray-50 {{ $isSunday ? 'bg-red-50 text-red-400' : 'text-slate-400 font-mono text-[9px]' }}">
                                {{ strtoupper($date->shortEnglishDayOfWeek) }}
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($personnel as $person)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4 sticky left-0 bg-white group-hover:bg-slate-50 z-10 border-r border-gray-100 shadow-[4px_0_8px_-4px_rgba(0,0,0,0.05)]">
                                <div class="font-bold text-slate-800 truncate">{{ $person->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $person->teacher->nip ?? 'A000000000001' }}</div>
                            </td>
                            @for($i = 1; $i <= $daysInMonth; $i++)
                                @php
                                    $date = $startDate->copy()->day($i);
                                    $isSunday = $date->isSunday();
                                    $attendance = isset($attendanceData[$person->id][$i]) ? $attendanceData[$person->id][$i]->first(function($a) use ($date) {
                                        $dateString = $date->toDateString();
                                        return $a->date instanceof \Carbon\Carbon 
                                            ? $a->date->toDateString() == $dateString 
                                            : (string)$a->date == $dateString;
                                    }) : null;
                                    
                                    $statusChar = '';
                                    $cellClass = '';
                                    $status = $attendance ? $attendance->status : '';
                                    $description = $attendance ? $attendance->description : '';
                                    
                                    if ($isSunday) {
                                        $statusChar = 'L';
                                        $cellClass = 'bg-red-50/40 text-red-200';
                                    } elseif ($attendance) {
                                        switch($attendance->status) {
                                            case 'present': $statusChar = 'H'; $cellClass = 'text-green-600 font-extrabold'; break;
                                            case 'sick': $statusChar = 'S'; $cellClass = 'bg-yellow-50 text-yellow-600 font-extrabold'; break;
                                            case 'permission': $statusChar = 'I'; $cellClass = 'bg-blue-50 text-blue-600 font-extrabold'; break;
                                            case 'absent': $statusChar = 'A'; $cellClass = 'bg-red-50 text-red-600 font-extrabold animate-pulse'; break;
                                        }
                                    }
                                @endphp
                                <td @if(!$isSunday) @click="if(editMode && '{{ $status }}' !== 'present') openEdit('{{ $person->id }}', '{{ $person->name }}', '{{ $date->toDateString() }}', '{{ $status }}', '{{ addslashes($description) }}')" @endif
                                    class="px-1 py-1 text-center border-r border-gray-50 min-w-[38px] {{ $cellClass }} transition-all duration-300 {{ !$isSunday ? 'cursor-default' : '' }}"
                                    :class="editMode && !{{ $isSunday ? 'true' : 'false' }} && '{{ $status }}' !== 'present' ? 'hover:bg-indigo-50 hover:scale-110 cursor-pointer shadow-inner' : (editMode && '{{ $status }}' === 'present' ? 'opacity-50 cursor-not-allowed' : '')">
                                    <div class="relative group/cell">
                                        {{ $statusChar }}
                                        @if($description)
                                            <div class="absolute -top-1 -right-1 w-1.5 h-1.5 bg-indigo-400 rounded-full"></div>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-slate-800 text-white text-[10px] rounded shadow-lg opacity-0 group-hover/cell:opacity-100 transition-opacity whitespace-nowrap z-50 pointer-events-none">
                                                {{ $description }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Legend Card -->
    <div class="p-6 bg-white rounded-[2rem] border border-slate-100 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-1.5 h-6 bg-indigo-500 rounded-full"></div>
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-tight">Legenda & Kode Status</h3>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-emerald-50/50 border border-emerald-100/50">
                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center font-black text-emerald-600 border border-emerald-100">H</div>
                <div class="flex flex-col">
                    <span class="font-bold text-slate-700 text-xs">Hadir</span>
                    <span class="text-slate-400 text-[10px]">Tepat Waktu</span>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-amber-50/50 border border-amber-100/50">
                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center font-black text-amber-600 border border-amber-100">S</div>
                <div class="flex flex-col">
                    <span class="font-bold text-slate-700 text-xs">Sakit</span>
                    <span class="text-slate-400 text-[10px]">Izin Sakit</span>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-blue-50/50 border border-blue-100/50">
                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center font-black text-blue-600 border border-blue-100">I</div>
                <div class="flex flex-col">
                    <span class="font-bold text-slate-700 text-xs">Izin</span>
                    <span class="text-slate-400 text-[10px]">Keperluan Lain</span>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-rose-50/50 border border-rose-100/50">
                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center font-black text-rose-600 border border-rose-100">A</div>
                <div class="flex flex-col">
                    <span class="font-bold text-slate-700 text-xs">Alfa</span>
                    <span class="text-slate-400 text-[10px]">Tanpa Ket.</span>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50/50 border border-slate-100/50">
                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center font-black text-slate-400 border border-slate-100">L</div>
                <div class="flex flex-col">
                    <span class="font-bold text-slate-700 text-xs">Libur</span>
                    <span class="text-slate-400 text-[10px]">Minggu/Libur</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="showModal" 
         x-cloak
         class="fixed inset-0 z-[100] overflow-y-auto" 
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop Overlay -->
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
                 @click="showModal = false"
                 aria-hidden="true"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100 z-[110]">
                
                <form action="{{ route('admin.attendance.personnel.recap.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" x-model="selectedUserId">
                    <input type="hidden" name="date" x-model="selectedDate">

                    <div class="p-8">
                        <!-- Modal Header -->
                        <div class="flex items-start justify-between mb-8">
                            <div class="space-y-1">
                                <h3 class="text-xl font-bold text-slate-800" id="modal-title">Edit Kehadiran</h3>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100 uppercase tracking-tighter" x-text="selectedDate"></span>
                                    <span class="text-slate-300 text-xs">•</span>
                                    <p class="text-xs font-medium text-slate-500 truncate max-w-[150px]" x-text="selectedUser"></p>
                                </div>
                            </div>
                            <button type="button" @click="showModal = false" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Status Selection -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Pilih Status Kehadiran</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <!-- Status: Present -->
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="status" value="present" x-model="selectedStatus" class="peer sr-only">
                                        <div class="p-3.5 rounded-2xl border-2 border-slate-50 bg-slate-50/50 transition-all flex flex-col gap-2 items-center text-center
                                                    peer-checked:border-emerald-500 peer-checked:bg-emerald-50/30 peer-checked:ring-4 peer-checked:ring-emerald-500/10
                                                    group-hover:bg-white group-hover:border-slate-200">
                                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-emerald-500 peer-checked:scale-110 transition-transform">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span class="text-xs font-bold text-slate-600">Hadir</span>
                                        </div>
                                    </label>

                                    <!-- Status: Sick -->
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="status" value="sick" x-model="selectedStatus" class="peer sr-only">
                                        <div class="p-3.5 rounded-2xl border-2 border-slate-50 bg-slate-50/50 transition-all flex flex-col gap-2 items-center text-center
                                                    peer-checked:border-amber-500 peer-checked:bg-amber-50/30 peer-checked:ring-4 peer-checked:ring-amber-500/10
                                                    group-hover:bg-white group-hover:border-slate-200">
                                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-amber-500 peer-checked:scale-110 transition-transform">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                </svg>
                                            </div>
                                            <span class="text-xs font-bold text-slate-600">Sakit</span>
                                        </div>
                                    </label>

                                    <!-- Status: Permission -->
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="status" value="permission" x-model="selectedStatus" class="peer sr-only">
                                        <div class="p-3.5 rounded-2xl border-2 border-slate-50 bg-slate-50/50 transition-all flex flex-col gap-2 items-center text-center
                                                    peer-checked:border-blue-500 peer-checked:bg-blue-50/30 peer-checked:ring-4 peer-checked:ring-blue-500/10
                                                    group-hover:bg-white group-hover:border-slate-200">
                                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-blue-500 peer-checked:scale-110 transition-transform">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <span class="text-xs font-bold text-slate-600">Izin</span>
                                        </div>
                                    </label>

                                    <!-- Status: Absent -->
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="status" value="absent" x-model="selectedStatus" class="peer sr-only">
                                        <div class="p-3.5 rounded-2xl border-2 border-slate-50 bg-slate-50/50 transition-all flex flex-col gap-2 items-center text-center
                                                    peer-checked:border-rose-500 peer-checked:bg-rose-50/30 peer-checked:ring-4 peer-checked:ring-rose-500/10
                                                    group-hover:bg-white group-hover:border-slate-200">
                                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-rose-500 peer-checked:scale-110 transition-transform">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </div>
                                            <span class="text-xs font-bold text-slate-600">Alfa</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Additional Description -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Keterangan Tambahan (Opsional)</label>
                                <textarea name="description" x-model="selectedDescription" rows="3" 
                                          class="w-full px-4 py-3 rounded-2xl border-2 border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-400 outline-none transition-all text-sm font-medium text-slate-700 placeholder:text-slate-300"
                                          placeholder="Contoh: Sakit flu, Izin keperluan keluarga..."></textarea>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="mt-10 flex flex-col gap-3">
                            <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:translate-y-[-2px] active:translate-y-[0px] transition-all text-sm">
                                Simpan Perubahan
                            </button>
                            <button type="button" @click="showModal = false" class="w-full py-4 bg-white text-slate-500 rounded-2xl font-bold border border-slate-100 hover:bg-slate-50 hover:text-slate-700 transition-all text-sm">
                                Batalkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
