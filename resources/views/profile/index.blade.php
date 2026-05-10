@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
            <p class="mt-1 text-sm text-gray-600">Kelola informasi profil dan pengaturan akun Anda.</p>
        </div>

        <div class="grid grid-cols-1 gap-8">
            <!-- Personal Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Pribadi</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="space-y-2">
                                <label for="name" class="text-sm font-bold text-gray-800">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-white shadow-sm hover:border-gray-300 focus:shadow-md">
                                @error('name')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="email" class="text-sm font-bold text-gray-800">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-white shadow-sm hover:border-gray-300 focus:shadow-md">
                                @error('email')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- NIP -->
                            <div class="space-y-2">
                                <label for="nip" class="text-sm font-bold text-gray-800">NIP (Nomor Induk
                                    Pegawai)</label>
                                <input type="text" name="nip" id="nip" value="{{ old('nip', $user->nip) }}"
                                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-white shadow-sm hover:border-gray-300 focus:shadow-md"
                                    placeholder="Masukkan NIP">
                                @error('nip')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="space-y-2">
                                <label for="gender" class="text-sm font-bold text-gray-800">Jenis Kelamin</label>
                                <select name="gender" id="gender"
                                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-white shadow-sm hover:border-gray-300 focus:shadow-md">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('gender', $user->gender) == 'L' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="P" {{ old('gender', $user->gender) == 'P' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                                @error('gender')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Position -->
                            <div class="space-y-2">
                                <label for="position" class="text-sm font-bold text-gray-800">Jabatan</label>
                                <input type="text" name="position" id="position"
                                    value="{{ old('position', $user->position) }}"
                                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-white shadow-sm hover:border-gray-300 focus:shadow-md"
                                    placeholder="Masukkan Jabatan">
                                @error('position')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="pt-4 md:col-span-2 border-t border-gray-100">
                                <h3 class="text-sm font-semibold text-gray-900 mb-4">Ubah Kata Sandi (Kosongkan jika tidak
                                    ingin mengubah)</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="password" class="text-sm font-bold text-gray-800">Kata Sandi
                                            Baru</label>
                                        <input type="password" name="password" id="password"
                                            class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-white shadow-sm hover:border-gray-300 focus:shadow-md">
                                        @error('password')
                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="space-y-2">
                                        <label for="password_confirmation"
                                            class="text-sm font-bold text-gray-800">Konfirmasi Kata Sandi</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="w-full px-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all bg-white shadow-sm hover:border-gray-300 focus:shadow-md">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition-all shadow-lg shadow-indigo-200 hover:scale-[1.02] active:scale-[0.98]">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
@endsection
