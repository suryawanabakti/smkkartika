@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Tambah Jadwal Mengajar</h1>
            <p class="text-gray-600">Silakan isi formulir di bawah ini untuk menambahkan jadwal baru.</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <form action="{{ route('admin.teacher-schedules.store') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <div class="space-y-1">
                    <label for="teacher_id" class="text-sm font-medium text-gray-700">Guru</label>
                    <select name="teacher_id" id="teacher_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none @error('teacher_id') border-red-500 @enderror">
                        <option value="">Pilih Guru</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->user->name }} ({{ $teacher->nip }})
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label for="day" class="text-sm font-medium text-gray-700">Hari</label>
                        <select name="day" id="day" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none @error('day') border-red-500 @enderror">
                            @foreach($days as $day)
                                <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                        @error('day') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="class_id" class="text-sm font-medium text-gray-700">Kelas</label>
                        <select name="class_id" id="class_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none @error('class_id') border-red-500 @enderror">
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('class_id') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="subject" class="text-sm font-medium text-gray-700">Mata Pelajaran</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none @error('subject') border-red-500 @enderror" placeholder="Contoh: Matematika">
                    @error('subject') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label for="period_start" class="text-sm font-medium text-gray-700">Dari Jam (Periode)</label>
                        <input type="number" name="period_start" id="period_start" value="{{ old('period_start') }}" min="1" max="11" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none @error('period_start') border-red-500 @enderror">
                        @error('period_start') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="period_end" class="text-sm font-medium text-gray-700">Sampai Jam (Periode)</label>
                        <input type="number" name="period_end" id="period_end" value="{{ old('period_end') }}" min="1" max="11" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none @error('period_end') border-red-500 @enderror">
                        @error('period_end') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">Simpan Jadwal</button>
                    <a href="{{ route('admin.teacher-schedules.index') }}" class="px-6 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors font-medium">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
