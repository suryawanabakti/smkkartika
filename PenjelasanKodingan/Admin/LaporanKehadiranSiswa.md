```php
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());

        $query = StudentAttendance::with(['student.user', 'student.classRoom.major'])
            ->whereDate('date', $date);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('class_id') && $request->class_id != '') {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $attendances = $query->latest()->paginate(20)->withQueryString();
        $classRooms = ClassRoom::with('major')->get();

        return view('admin.attendance.students', compact('attendances', 'classRooms', 'date'));
    }
```

Fungsi `index` ini digunakan untuk menampilkan tabel lengkap kehadiran dari semua unit jenjang atau kumpulan kelas tiap siswa untuk pergelaran pantau data administrator. Secara asali rentetan ini memperlihatkan absensi terfokus pada titik tanggal hari bersangkutan (bisa dimanipulasi dengan custom kiriman param date). Rentetan filter siap diaktivasi memancing hasil jika admin menunggangi filter nama siswa yang dimasifkan search term tabel kehadiran, ditandem pula bila admin menginginkan filter hanya mengekstrak kelas-kelas bernilai ID kelas identik, di sisi penjuru filter lainnya ada param status. Seluruh akumulasi penyaringan bakal tersortir kronologis teranyar lalu tersunat di batas peredaran 20 elemen absen dihalaman beruntun dan merapat untuk tayang di halaman index presensi siswa mendampingi pasokan susunan struktur kelasnya.

```php
    public function recap(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));
        $classId = $request->get('class_id');
        
        $startDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $startDate->daysInMonth;

        $query = Student::with('user', 'classRoom.major');
        if ($classId) {
            $query->where('class_id', $classId);
        }
        $students = $query->get();

        $attendanceData = StudentAttendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy(['student_id', function ($item) {
                return Carbon::parse($item->date)->day;
            }]);

        $classRooms = ClassRoom::with('major')->get();

        return view('admin.attendance.students_recap', compact('students', 'attendanceData', 'month', 'year', 'daysInMonth', 'startDate', 'classRooms'));
    }
```

Fungsi `recap` dikhususkan bagi administrator demi menerbitkan rangkuman laporan mutlak berdurasi bulanan utuh kehadiran pelajar. Sistem mengumpulkan info riwayat data absensi seluruhnya menyasar spesifikasi kelas tertuju tanpa menguras kelas tetangganya. Sistem pula mencacah total bulan berlabuh dan merenggangi tanggal harinya per blok. Kemudian tabel raksasa akan dijangkar dititik siswa dan menempelkannya dengan sumbu harinya mendatar berbaris di perulokasian `attendanceData` supaya mudah dijajarkan dirender rapi jali ke tampungan piringan layar `students_recap`.
