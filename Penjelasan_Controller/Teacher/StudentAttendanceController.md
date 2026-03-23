```php
    public function index()
    {
        $user = Auth::user();
        $class = $user->teacher->classRoom;
        $date = Carbon::today()->toDateString();
        
        if (!$class) {
            return view('teacher.students.index', ['students' => collect(), 'class' => null, 'date' => $date]);
        }
        $students = Student::where('class_id', $class->id)
            ->with(['user', 'attendances' => function($q) use ($date) {
                $q->whereDate('date', $date);
            }])
            ->get();

        return view('teacher.students.index', compact('students', 'class', 'date'));
    }
```

Fungsi `index` ini dimanfaatkan oleh guru ketika hendak membuka halaman pencatatan dan pandangan harian absensi murid di kelasnya. Pertama, sistem membedah relasi untuk menemukan data kelas dari sang guru yang melog-in. Jikalau ternyata guru itu bukanlah bertindak selaku wali dari kelas mana pun (`!$class`), sistem bakal sigap melontarkan halaman kosong memuat keterangan tanpa murid maupun daftar kelas, sehingga pening juga error sirna.
Namun bila guru tersebut memegang peranan wali kelas, ia akan mencakup semua relasi seluruh pelajar dalam binaannya seraya menyuntikkan kaitan jejak rekam absensi murid khusus di satu hari itu ke dalam variabel `$students`. Semuanya pun lantas dimuntahkan menuju antarmuka absensi harian (`teacher.students.index`).

```php
    public function store(Request $request)
    {
        $request->validate([
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,sick,permission,absent',
        ]);

        $date = Carbon::today()->toDateString();
        
        foreach ($request->attendance as $item) {
            StudentAttendance::updateOrCreate(
                [
                    'student_id' => $item['student_id'],
                    'date' => $date
                ],
                [
                    'status' => $item['status']
                ]
            );
        }

        return back()->with('success', 'Kehadiran siswa berhasil diperbarui untuk hari ini.');
    }
```

Fungsi `store` mengemban amanat berat berupa mengeksekusi catatan input massal pendaftaran absensi semua murid kelas seketika kala sang guru menekan tombol simpan atau submisi. Awalnya wujud parameter ditapis ketat, harus berupa array kehadiran yang utuh membawa identitas murid aktif (`student_id`) berdampingan keterangan valid atas kehadirannya cuma bertaut 4 kata laksana saklar (masuk, sakit, izin, absen/alfa).
Manakala data dirasa tak bernoda galat apa jua, array murid dibongkar membundar `foreach`. Cerdiknya, Laravel memerintahkan `updateOrCreate()`; yang otomatis mencari apakah data absensi hari itu per siswanya sudah tertenun. Jika belum, data segar dicetak, dan jika hari tersebut sang guru keliru dan mengubah keterangan absen ulang, datanya cukup di-timpa tanpa menduplikasi rangkap di pelataran database. Barulah pengajar diputar-balik menuju halamannya berimbuhkan penanda kelancaran sistem merekam aksinya.

```php
    public function recap(Request $request)
    {
        $user = Auth::user();
        $class = $user->teacher->classRoom;
        
        if (!$class) {
            return view('teacher.students.recap', ['students' => collect(), 'class' => null]);
        }

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;

        $students = Student::where('class_id', $class->id)
            ->with(['user', 'attendances' => function ($query) use ($month, $year) {
                $query->whereMonth('date', $month)
                    ->whereYear('date', $year);
            }])
            ->get();

        return view('teacher.students.recap', compact('students', 'class', 'month', 'year', 'daysInMonth'));
    }
```

Fungsi `recap` adalah jendela khusus wali kelas untuk memantau rekap menyeluruh kalender kehadiran per bulannya dari peserta didik satu kelasnya. Fungsi ini memiliki penyaringan ganda, yang mencegah guru non-wali membukanya secara leluasa. Pengguna difasilitasi param opsional tahun dan bulan dari formnya, lalu sistem secara mandiri menggali rimbunan absensi siswanya pada retang sasaran sebulan penuh tersebut (`whereMonth` dan `whereYear`). Rekapitulasi pun menembak antarmuka lembaran `teacher.students.recap` demi diringkas wujud tampilannya sebagai lembaran lajur absensi bulanan.
