```php
    public function index()
    {
        $student = Auth::user()->student;
        
        $todayAttendance = StudentAttendance::where('student_id', $student->id)
            ->whereDate('date', Carbon::today())
            ->first();

        $monthlyStats = [
            'present' => StudentAttendance::where('student_id', $student->id)
                ->whereMonth('date', Carbon::now()->month)
                ->where('status', 'present')
                ->count(),
            'sick' => StudentAttendance::where('student_id', $student->id)
                ->whereMonth('date', Carbon::now()->month)
                ->where('status', 'sick')
                ->count(),
            'permission' => StudentAttendance::where('student_id', $student->id)
                ->whereMonth('date', Carbon::now()->month)
                ->where('status', 'permission')
                ->count(),
            'absent' => StudentAttendance::where('student_id', $student->id)
                ->whereMonth('date', Carbon::now()->month)
                ->where('status', 'absent')
                ->count(),
        ];

        return view('student.dashboard', compact('student', 'todayAttendance', 'monthlyStats'));
    }
```

Fungsi `index` ini menjadi wajah penyambutan pertama siswa (dashboard/beranda) pada panelnya usai mendobrak pintu login. Segera ia mengamankan identitas profil siswanya lalu mengambil sebaris rekaman kehadiran utuh hanya miliknya eksklusif untuk dicek hari ini (`$todayAttendance`).

Supaya tampilannya kaya informasi, sistem turut menjumlahkan lalu merekahkan hitungan status bulanan (hanya terbatas bulan kala ia login) yang merincikan frekuensi kedisplinannya (`$monthlyStats`). Ia mencacah berapa kali ia hadir ('present'), sakit ('sick'), izin ('permission'), dan bolos alfa ('absent') sejauh bulan berjalan ini. Profil, deteksi kehadiran hari ini, berikut rangkuman laporan ringkas bulanannya tersebut lantas diunggah sebagai hiasan informatif ke pelataran rupa dasbor `student.dashboard`.
