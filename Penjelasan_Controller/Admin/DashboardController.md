```php
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $stats = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_classes' => ClassRoom::count(),
            'total_majors' => Major::count(),
            'student_attendance_today' => [
                'present' => StudentAttendance::where('date', $today)->where('status', 'present')->count(),
                'absent' => StudentAttendance::where('date', $today)->where('status', 'absent')->count(),
                'sick' => StudentAttendance::where('date', $today)->where('status', 'sick')->count(),
                'permission' => StudentAttendance::where('date', $today)->where('status', 'permission')->count(),
            ],
            'personnel_attendance_today' => [
                'present' => PersonnelAttendance::where('date', $today)->where('status', 'present')->count(),
                'absent' => PersonnelAttendance::where('date', $today)->where('status', 'absent')->count(),
                'sick' => PersonnelAttendance::where('date', $today)->where('status', 'sick')->count(),
                'permission' => PersonnelAttendance::where('date', $today)->where('status', 'permission')->count(),
            ]
        ];

        return view('admin.dashboard', compact('stats'));
    }
```

Fungsi `index` pada DashboardController ini digunakan untuk menampilkan halaman utama dasbor admin ketika pertama kali masuk. Pertama, sistem mengambil tanggal hari ini menggunakan Carbon. Kemudian sistem akan mengoleksi semua data statistik kunci ke dalam variabel array bernama `$stats`. Data yang dihitung mencakup jumlah keseluruhan siswa, guru, kelas, dan jurusan.
Selain itu, sistem juga menghitung dan merekapitulasi jumlah absensi siswa secara detail hanya untuk hari ini, meliputi masing-masing keterangan kehadiran yaitu masuk (present), alfa/absen (absent), sakit (sick), dan izin (permission). Merekapitulasi serupa juga diterapkan pada catatan absensi para staf sekolah atau guru (personnel). Seluruh data ini dilempar dan ditempel untuk merender tampilan (view) di `admin.dashboard`.
