@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-2 xl:grid-cols-4">
        <!-- Students -->
        <div class="p-4 sm:p-6 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="p-2 sm:p-3 bg-blue-100 text-blue-600 rounded-lg shrink-0">
                <svg class="w-6 h-6 sm:w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-9-3.833M18.732 7.961a5 5 0 11-9.047-4.461 5 5 0 019.047 4.461z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Total Siswa</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $stats['total_students'] }}</p>
            </div>
        </div>

        <!-- Teachers -->
        <div class="p-4 sm:p-6 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="p-2 sm:p-3 bg-purple-100 text-purple-600 rounded-lg shrink-0">
                <svg class="w-6 h-6 sm:w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Total Guru</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $stats['total_teachers'] }}</p>
            </div>
        </div>

        <!-- Classes -->
        <div class="p-4 sm:p-6 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="p-2 sm:p-3 bg-green-100 text-green-600 rounded-lg shrink-0">
                <svg class="w-6 h-6 sm:w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Total Kelas</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $stats['total_classes'] }}</p>
            </div>
        </div>

        <!-- Majors -->
        <div class="p-4 sm:p-6 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="p-2 sm:p-3 bg-orange-100 text-orange-600 rounded-lg shrink-0">
                <svg class="w-6 h-6 sm:w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] sm:text-sm font-medium text-gray-500 uppercase tracking-wider">Total Jurusan</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $stats['total_majors'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Student Attendance Today -->
        <div class="p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Kehadiran Siswa Hari Ini</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-green-50 rounded-lg text-center leading-normal">
                    <p class="text-xs font-semibold text-green-600 uppercase">Hadir</p>
                    <p class="text-xl font-bold text-green-700">{{ $stats['student_attendance_today']['present'] }}</p>
                </div>
                <div class="p-4 bg-red-50 rounded-lg text-center leading-normal">
                    <p class="text-xs font-semibold text-red-600 uppercase">Alfa</p>
                    <p class="text-xl font-bold text-red-700">{{ $stats['student_attendance_today']['absent'] }}</p>
                </div>
                <div class="p-4 bg-yellow-50 rounded-lg text-center leading-normal">
                    <p class="text-xs font-semibold text-yellow-600 uppercase">Sakit</p>
                    <p class="text-xl font-bold text-yellow-700">{{ $stats['student_attendance_today']['sick'] }}</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg text-center leading-normal">
                    <p class="text-xs font-semibold text-blue-600 uppercase">Izin</p>
                    <p class="text-xl font-bold text-blue-700">{{ $stats['student_attendance_today']['permission'] }}</p>
                </div>
            </div>
        </div>

        <!-- Personnel Attendance Today -->
        <div class="p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Kehadiran Pegawai Hari Ini</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-green-50 rounded-lg text-center leading-normal">
                    <p class="text-xs font-semibold text-green-600 uppercase">Hadir</p>
                    <p class="text-xl font-bold text-green-700">{{ $stats['personnel_attendance_today']['present'] }}</p>
                </div>
                <div class="p-4 bg-red-50 rounded-lg text-center leading-normal">
                    <p class="text-xs font-semibold text-red-600 uppercase">Alfa</p>
                    <p class="text-xl font-bold text-red-700">{{ $stats['personnel_attendance_today']['absent'] }}</p>
                </div>
                <div class="p-4 bg-yellow-50 rounded-lg text-center leading-normal">
                    <p class="text-xs font-semibold text-yellow-600 uppercase">Sakit</p>
                    <p class="text-xl font-bold text-yellow-700">{{ $stats['personnel_attendance_today']['sick'] }}</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg text-center leading-normal">
                    <p class="text-xs font-semibold text-blue-600 uppercase">Izin</p>
                    <p class="text-xl font-bold text-blue-700">{{ $stats['personnel_attendance_today']['permission'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
