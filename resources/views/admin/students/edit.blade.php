@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Data Siswa</h1>
            <a href="{{ route('admin.students.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">
                &larr; Kembali ke Daftar
            </a>
        </div>

        <div class="p-8 bg-white rounded-xl border border-gray-100 shadow-sm">
            <form action="{{ route('admin.students.update', $student) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $student->user->name) }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all @error('name') border-red-500 @enderror"
                            required>
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nis" class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                        <input type="text" name="nis" id="nis" value="{{ old('nis', $student->nis) }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all @error('nis') border-red-500 @enderror"
                            required>
                        @error('nis')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select name="class_id" id="class_id"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all @error('class_id') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Kelas</option>
                            @foreach ($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}"
                                    {{ old('class_id', $student->class_id) == $classRoom->id ? 'selected' : '' }}>
                                    {{ $classRoom->name }} ({{ $classRoom->major->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                        <input type="email" name="email" id="email"
                            value="{{ old('email', $student->user->email) }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all @error('email') border-red-500 @enderror"
                            required>
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all @error('password') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500 italic text-right">Biarkan kosong jika tidak ingin mengubah kata sandi.</p>
                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                        Perbarui Data Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
