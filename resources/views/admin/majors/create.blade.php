@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Tambah Jurusan Baru</h1>
        <a href="{{ route('admin.majors.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="p-8 bg-white rounded-xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.majors.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Jurusan</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all @error('name') border-red-500 @enderror"
                    placeholder="contoh: Rekayasa Perangkat Lunak"
                    required
                >
                @error('name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Kode Jurusan</label>
                <input 
                    type="text" 
                    name="code" 
                    id="code" 
                    value="{{ old('code') }}" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all @error('code') border-red-500 @enderror"
                    placeholder="e.g. RPL"
                    required
                >
                @error('code')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                    Simpan Jurusan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
