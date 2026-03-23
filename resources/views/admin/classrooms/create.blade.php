@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Tambah Kelas Baru</h1>
        <a href="{{ route('admin.classrooms.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="p-8 bg-white rounded-xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.classrooms.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all @error('name') border-red-500 @enderror"
                    placeholder="contoh: X RPL 1"
                    required
                >
                @error('name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="major_id" class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                <select 
                    name="major_id" 
                    id="major_id" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all @error('major_id') border-red-500 @enderror"
                    required
                >
                    <option value="">Pilih Jurusan</option>
                    @foreach($majors as $major)
                        <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>
                            {{ $major->name }} ({{ $major->code }})
                        </option>
                    @endforeach
                </select>
                @error('major_id')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-1">Wali Kelas (Opsional)</label>
                <select 
                    name="teacher_id" 
                    id="teacher_id" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all @error('teacher_id') border-red-500 @enderror"
                >
                    <option value="">Pilih Guru</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->user->name }} ({{ $teacher->nip }})
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                    Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
