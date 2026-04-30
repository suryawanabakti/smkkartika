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
                            <div class="space-y-1">
                                <label for="name" class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                    required
                                    class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-shadow">
                                @error('name')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-1">
                                <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                    required
                                    class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-shadow">
                                @error('email')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- NIP -->
                            <div class="space-y-1">
                                <label for="nip" class="text-sm font-medium text-gray-700">NIP (Nomor Induk
                                    Pegawai)</label>
                                <input type="text" name="nip" id="nip" value="{{ old('nip', $user->nip) }}"
                                    class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-shadow"
                                    placeholder="Masukkan NIP">
                                @error('nip')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="space-y-1">
                                <label for="gender" class="text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                <select name="gender" id="gender"
                                    class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-shadow">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('gender', $user->gender) == 'L' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="P" {{ old('gender', $user->gender) == 'P' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                                @error('gender')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Position -->
                            <div class="space-y-1 md:col-span-2">
                                <label for="position" class="text-sm font-medium text-gray-700">Jabatan</label>
                                <input type="text" name="position" id="position"
                                    value="{{ old('position', $user->position) }}"
                                    class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-shadow"
                                    placeholder="Contoh: Guru Matematika, Staf Administrasi">
                                @error('position')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="pt-4 md:col-span-2 border-t border-gray-100">
                                <h3 class="text-sm font-semibold text-gray-900 mb-4">Ubah Kata Sandi (Kosongkan jika tidak
                                    ingin mengubah)</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-1">
                                        <label for="password" class="text-sm font-medium text-gray-700">Kata Sandi
                                            Baru</label>
                                        <input type="password" name="password" id="password"
                                            class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-shadow">
                                        @error('password')
                                            <p class="text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="space-y-1">
                                        <label for="password_confirmation"
                                            class="text-sm font-medium text-gray-700">Konfirmasi Kata Sandi</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-shadow">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition-all shadow-md shadow-indigo-100">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
                <div class="p-6 border-b border-red-50 bg-red-50/50">
                    <h2 class="text-lg font-semibold text-red-900">Hapus Akun</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-6">Setelah akun Anda dihapus, semua sumber daya dan datanya akan
                        dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang
                        ingin Anda simpan.</p>

                    <div x-data="{ open: false }">
                        <button @click="open = true"
                            class="px-6 py-2.5 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 focus:ring-4 focus:ring-red-100 transition-all shadow-md shadow-red-100">
                            Hapus Akun
                        </button>

                        <!-- Modal -->
                        <div x-show="open"
                            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm"
                            x-cloak>
                            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 animate-in fade-in zoom-in duration-200"
                                @click.away="open = false">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Penghapusan Akun</h3>
                                <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin menghapus akun Anda? Masukkan
                                    kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara
                                    permanen.</p>

                                <form action="{{ route('profile.destroy') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="space-y-4">
                                        <div class="space-y-1">
                                            <label for="confirm_password" class="text-sm font-medium text-gray-700">Kata
                                                Sandi</label>
                                            <input type="password" name="password" id="confirm_password" required
                                                class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-shadow">
                                        </div>
                                        <div class="flex justify-end space-x-3">
                                            <button type="button" @click="open = false"
                                                class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                                Batal
                                            </button>
                                            <button type="submit"
                                                class="px-4 py-2 text-sm font-medium bg-red-600 text-white hover:bg-red-700 rounded-lg transition-colors">
                                                Hapus Secara Permanen
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
