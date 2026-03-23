```php
    public function index(Request $request)
    {
        $student = Auth::user()->student;
        
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));

        $attendances = StudentAttendance::where('student_id', $student->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        return view('student.attendance.index', compact('student', 'attendances', 'month', 'year'));
    }
```

Fungsi `index` ini digunakan secara eksklusif oleh murid untuk merenungi jejak kehadiran dan ketidakhadirannya sendiri. Sistem akan langsung mengenali identitas profil siswa yang sedang berhasil masuk (`Auth::user()->student`). Kemudian sistem akan menangkap parameter bulan dan tahun dari permintaan filter di halaman, dan jika tidak diatur, maka kalender merujuk bulan plus tahun hari ini. 

Rekam absensinya akan difilter, di mana sistem memaksa menarik data absensi dari lumbung (`StudentAttendance`) yang mutlak hanya mempunyai `student_id` yang sama persis dengan siswa tersebut serta jatuh pada sebulan penuh acuan parameternya. Rekaman riwayat tersebut disusun berderet dari yang paling baru di rentangnya (`orderBy('date', 'desc')`), ditarik, lantas ditampilkan bersama balutan profil siswa ke layar laporan ringkasan presensinya di `student.attendance.index`.
