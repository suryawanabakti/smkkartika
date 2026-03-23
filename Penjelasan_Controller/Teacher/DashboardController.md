```php
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $myAttendance = PersonnelAttendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        $class = $user->teacher->classRoom;
        $classStats = null;

        if ($class) {

            $studentIds = $class->students->pluck('id');
            $classStats = [
                'total_students' => $class->students->count(),
                'present_today' => StudentAttendance::whereIn('student_id', $studentIds)
                    ->whereDate('date', $today)
                    ->where('status', 'present')
                    ->count(),
                'absent_today' => StudentAttendance::whereIn('student_id', $studentIds)
                    ->whereDate('date', $today)
                    ->whereIn('status', ['absent', 'sick', 'permission'])
                    ->count(),
            ];
        }

        $schoolSettings = [
            'name' => SchoolSetting::get('school_name', 'SMK Kartika'),
            'latitude' => (float) SchoolSetting::get('school_latitude', -5.1436),
            'longitude' => (float) SchoolSetting::get('school_longitude', 119.4667),
            'radius' => (int) SchoolSetting::get('school_radius', 200),
        ];

        return view('teacher.dashboard', compact('myAttendance', 'class', 'classStats', 'schoolSettings'));
    }
```

Fungsi `index` ini digunakan untuk menyiapkan dan menampilkan keseluruhan data pada halaman beranda atau dasbor khusus guru saat guru tersebut login. 
Sistem akan mengenali guru yang sedang aktif masuk (`Auth::user()`), lalu mengambil data status absen mandiri sang guru (`$myAttendance`) khusus untuk hari itu guna dimunculkan ke tampilan (apakah sudah absen masuk, sudah absen pulang, atau belum sama sekali). 

Selain itu, sistem akan mengecek apakah guru tersebut bertindak sebagai wali kelas (memiliki relasi ke `$user->teacher->classRoom`). Jika ia merupakan wali kelas, sistem akan menghitung jumlah murid yang berada di bawah pantauan kelasnya (`$classStats`), lalu memisahkan statistik yang hadir hari ini (`present_today`) dan yang tidak hadir (total akumulasi absen, sakit, atau izin dalam `absent_today`).

Di tahap akhir, sistem juga memanggil beberapa titik penting profil sekolah, utamanya memuat nama, patokan koordinat (latitude & longitude) dan radius sah (m) dari `SchoolSetting` sebagai pondasi fitur melacak peta radius absensi. Semuanya dibundel kemas lalu diforkan menuju lembaran tayangan dasar (`teacher.dashboard`).
