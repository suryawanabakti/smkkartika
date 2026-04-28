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
                                <td @if(!$isSunday) @click="if(editMode) openEdit('{{ $person->id }}', '{{ $person->name }}', '{{ $date->toDateString() }}', '{{ $status }}', '{{ addslashes($description) }}')" @endif
                                    class="px-1 py-1 text-center border-r border-gray-50 min-w-[38px] {{ $cellClass }} transition-all duration-300 {{ !$isSunday ? 'cursor-default' : '' }}"
                                    :class="editMode && !{{ $isSunday ? 'true' : 'false' }} ? 'hover:bg-indigo-50 hover:scale-110 cursor-pointer shadow-inner' : ''">
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
    <div class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
        <h3 class="text-xs font-bold text-gray-400 uppercase mb-4">Legenda & Kode Status</h3>
        <div class="flex flex-wrap gap-x-8 gap-y-4">
            <div class="flex items-center gap-3 text-xs">
                <div class="w-8 h-8 rounded-xl bg-green-50 text-green-600 flex items-center justify-center font-black border border-green-100">H</div>
                <div class="flex flex-col">
                    <span class="font-bold text-gray-700">Hadir</span>
                    <span class="text-gray-400 text-[10px]">Hadir</span>
                </div>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <div class="w-8 h-8 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center font-black border border-yellow-100">S</div>
                <div class="flex flex-col">
                    <span class="font-bold text-gray-700">Sakit</span>
                    <span class="text-gray-400 text-[10px]">Sakit</span>
                </div>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black border border-blue-100">I</div>
                <div class="flex flex-col">
                    <span class="font-bold text-gray-700">Izin</span>
                    <span class="text-gray-400 text-[10px]">Izin</span>
                </div>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <div class="w-8 h-8 rounded-xl bg-red-100 text-red-600 flex items-center justify-center font-black border border-red-200">A</div>
                <div class="flex flex-col">
                    <span class="font-bold text-gray-700">Alfa</span>
                    <span class="text-gray-400 text-[10px]">Alfa</span>
                </div>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <div class="w-8 h-8 rounded-xl bg-red-50 text-red-500 flex items-center justify-center font-black border border-red-100">L</div>
                <div class="flex flex-col">
                    <span class="font-bold text-gray-700">Libur</span>
                    <span class="text-gray-400 text-[10px]">Minggu/Hari Libur</span>
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
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" 
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
                 class="relative inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white z-[110]">
                
                <form action="{{ route('admin.attendance.personnel.recap.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" x-model="selectedUserId">
                    <input type="hidden" name="date" x-model="selectedDate">

                    <div class="bg-white px-8 pt-10 pb-8">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-2xl font-black text-slate-900 leading-none" id="modal-title">Edit Kehadiran</h3>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest" x-text="selectedUser"></p>
                                </div>
                            </div>
                            <div class="px-4 py-2 bg-slate-50 rounded-2xl text-[11px] font-black text-slate-400 border border-slate-100 uppercase tracking-widest" x-text="selectedDate"></div>
                        </div>

                        <div class="space-y-8">
                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 ml-1">Status Kehadiran</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <template x-for="(label, status) in {present: 'Hadir', sick: 'Sakit', permission: 'Izin', absent: 'Alfa'}">
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="status" :value="status" x-model="selectedStatus" class="peer sr-only">
                                            <div class="p-4 rounded-2xl border-2 border-slate-50 bg-slate-50/50 text-sm font-bold transition-all flex items-center gap-3
                                                        peer-checked:border-indigo-500 peer-checked:bg-white peer-checked:text-indigo-700 peer-checked:shadow-xl peer-checked:shadow-indigo-100/50
                                                        group-hover:bg-white group-hover:border-slate-200">
                                                <div class="w-2.5 h-2.5 rounded-full ring-4 ring-white shadow-sm" 
                                                     :class="{
                                                        'bg-emerald-500': status == 'present',
                                                        'bg-amber-500': status == 'sick',
                                                        'bg-blue-500': status == 'permission',
                                                        'bg-rose-500': status == 'absent'
                                                     }"></div>
                                                <span x-text="label" class="text-slate-700 peer-checked:text-indigo-900"></span>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 ml-1">Keterangan Tambahan</label>
                                <textarea name="description" x-model="selectedDescription" rows="3" 
                                          class="w-full px-5 py-4 rounded-[1.5rem] border-2 border-slate-50 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-medium text-slate-700 placeholder:text-slate-300"
                                          placeholder="Tulis alasan sakit, izin, atau lainnya di sini..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50/80 backdrop-blur-sm px-8 py-8 flex flex-col sm:flex-row-reverse gap-4 border-t border-slate-100">
                        <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-slate-900 text-white rounded-2xl font-bold shadow-2xl shadow-slate-900/20 hover:bg-black hover:scale-[1.02] active:scale-[0.98] transition-all text-sm">
                            Simpan Perubahan
                        </button>
                        <button type="button" @click="showModal = false" class="w-full sm:w-auto px-10 py-4 bg-white text-slate-500 rounded-2xl font-bold border border-slate-200 hover:bg-slate-50 hover:text-slate-700 transition-all text-sm">
                            Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
