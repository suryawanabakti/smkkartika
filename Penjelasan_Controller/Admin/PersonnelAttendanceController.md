```php
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $query = PersonnelAttendance::with('user')
            ->whereDate('date', $date);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $attendances = $query->latest()->paginate(15)->withQueryString();

        return view('admin.attendance.personnel', compact('attendances', 'date'));
    }
```

Fungsi `index` bertanggung jawab menyediakan laporan presensi harian dari pegawai dan guru yang tertampang di meja administrator. Sistem memperoleh tanggal hari ini sebagai waktu default-nya, lalu akan mengambil informasi absen pegawai beserta identitas penggunanya yang terkait dengaan tanggal tersebut. Fasilitas tambahan diikutkan dengan memberikan penyaringan berdasarkan pencarian nama user (pegawai) serta mampu difilter berdasarkan status rekam kehadiran harian saja. Temuan yang dicari digilir atau dilimit pembagiannya 15 halaman dan dikirimkan untuk rilis di tampilan layar indeks absen staf.

```php
    public function recap(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));
        
        $startDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $startDate->daysInMonth;

        $personnel = User::whereHas('role', function($q) {
            $q->whereIn('name', ['admin', 'teacher']);
        })->get();

        $attendanceData = PersonnelAttendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy(['user_id', function ($item) {
                return Carbon::parse($item->date)->day;
            }]);

        return view('admin.attendance.personnel_recap', compact('personnel', 'attendanceData', 'month', 'year', 'daysInMonth', 'startDate'));
    }
```

Fungsi `recap` dikhususkan bagi administrator demi menerbitkan rangkuman laporan mutlak berdurasi bulanan utuh. Jika parameter bulan dan tahun tidak diberikan secara sadar oleh admin, ia bertindak dengan inisiatif sendirinya mematok ke bulan dan tahun detik ini. Selanjutnya memprofilkan kalender dengan mengutip banyaknya hari di bulan itu. Lalu memungut informasi personel yang cuma mengantongi peran 'admin' bertindih 'teacher/guru'. Ia kemudian menambang total daftar presensi sedulan seutuhnya milik tahun serta bulan dimaksud. Ia kemudian mengkategorikan dan menyematkan datanya menumpuk disasarkan relasi identitas pengguna (`user_id`) disertai urutan harinya guna diinjeksi memapapar sebagai baris dan kolom yang merata bagaikan tabel di tampilan antarmuka.
