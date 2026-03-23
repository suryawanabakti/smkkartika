```php
    public function index()
    {
        $user = Auth::user();
        $attendances = PersonnelAttendance::where('user_id', $user->id)
            ->latest('date')
            ->paginate(10);
            
        $todayAttendance = PersonnelAttendance::where('user_id', $user->id)
            ->whereDate('date', Carbon::today())
            ->first();

        $schoolSettings = [
            'name' => SchoolSetting::get('school_name', 'SMK Kartika'),
            'latitude' => (float) SchoolSetting::get('school_latitude', -5.1436),
            'longitude' => (float) SchoolSetting::get('school_longitude', 119.4667),
            'radius' => (int) SchoolSetting::get('school_radius', 200),
        ];

        $workHours = [
            'min_check_in' => SchoolSetting::get('min_check_in_time', '07:00'),
            'min_check_out' => SchoolSetting::get('min_check_out_time', '15:00'),
        ];

        return view('teacher.attendance.index', compact('attendances', 'todayAttendance', 'schoolSettings', 'workHours'));
    }
```

Fungsi `index` menangani representasi beranda rekam jejak maupun rupa tombol berabsensi diri (masuk dan pulang) sang guru secara personal. Sistem mulanya mendulang riwayat historis absensinya sendiri dipecah ke takaran 10 entri tiap halaman (`paginate(10)`). Selain itu rekaman detail hari ininya saja disita khusus (`$todayAttendance`) untuk memutuskan apakah guru sudah berstatus usai masuk atau masih wajib memencet tombol absensi.
Bahan baku pemetaan radius diangkut kemari sebagai modal sistem mendeteksi batasan jarak guru saat ini dari pusat sekolah (menampung radius dan lat/long sekolah). Waktu kedisiplinan semisal batas memencet presensi masuk dan pulang pun diekstrak masuk. Seluruh variabelnya dijejali penuhan kepada rupa antarmuka `teacher.attendance.index`. 

```php
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        
        ... // (cek apakah sudah absen masuk, jika sudah tolak)

        // Validate minimum check-in time
        ... // (cek apakah belum mencapai jam buka absen, misal jam 07:00)

        // Validate distance to school
        $schoolLat = (float) SchoolSetting::get('school_latitude', -5.1436);
        $schoolLng = (float) SchoolSetting::get('school_longitude', 119.4667);
        $schoolRadius = (int) SchoolSetting::get('school_radius', 200);

        $userLat = (float) $request->latitude;
        $userLng = (float) $request->longitude;

        $distance = $this->haversineDistance($userLat, $userLng, $schoolLat, $schoolLng);

        if ($distance > $schoolRadius) {
            return back()->with('error', 'Anda berada di luar radius sekolah (' . round($distance) . 'm dari sekolah, batas: ' . $schoolRadius . 'm). Silakan mendekat ke area sekolah.');
        }

        PersonnelAttendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in_time' => now(),
            'status' => 'present',
            'latitude' => $userLat,
            'longitude' => $userLng,
        ]);

        return back()->with('success', 'Absensi masuk berhasil dicatat ...');
    }
```

Fungsi `store` mengoordinasikan perintah rumit pendaftaran absensi masuk (check-in) untuk satu pengajar harian itu. Sistem menghalau bilamana guru merangsek absen dua kali untuk hari tersebut. Lolos dari halangan sandi, sistem mencegah pencatatan mendahului jadwal resmi sebelum keran absensi dilonggarkan sekolah (misal belum jam 07:00). 
Hal terpenting adalah sistem mengeduk titik pilar pengguna seraya menghitung presisi bentang jarak garis tariknya menuju pilar pusat lahan sekolah melalui mekanisme komputasi formula rupa bulat bola jarak udara bernama (Haversine Distance). Seandainya kejauhannya keluar dari batasan lingkar sah meter maka proses bakal diberhentikan sembari memunculkan notifikasi usiran karena di-luar jangkauan radius (Out of range). Seumpamanya terpenuhi syariatnya jarak dan waktunya, cap masuk ditancapkan secara gemilang berserta pencetakan waktunya (`check_in_time`).

```php
    public function checkout(Request $request)
    {
        ... // (validasi lat/long yang sama persis)

        $attendance = PersonnelAttendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        // (cek belum masuk tak bisa pulang, atau cek jika sudah pulang ditolak dua kali)
        ...

        // Validate minimum check-out time
        ... // (cek belum jam pulang menolak guru mangkir dini)

        // Validate distance to school
        ... // (cek haversine radius mirip seperti ketika masuk)

        $attendance->update([
            'check_out_time' => now(),
        ]);

        return back()->with('success', 'Absensi pulang berhasil dicatat ...');
    }
```

Fungsi `checkout` seia sekata serupa mempresentasikan perintah keluar (absen pulang). Sangat berikhtiar memvalidasi titik pangkal lat/long dengan pembulatan batas lingkar sah lagi lewat jarak haversine. Bedanya, sang guru tak boleh membobol pulang murni bila ia membolos ketidakadilan masuk tak tercatat sama sekali di database, serta sistem mencegat dini manapun guru mau melabrak memencet bergegas padahal sirine jadwal pulang resminya usai tersemat masih jauh (belum waktunya check out). Data lama entri absensi saat masuk kini cukup diamandeman diperbarui porsi `check_out_time` menguncinya.

```php
    private function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
```

Fungsi `haversineDistance` merupakan senjata andalan motor komputasi radius. Fungsi gurem bersifat privasi terbatas (berkata sandi `private` yang disembunyikan bagi fungsional luar) bertugas memecahkan rumusan trigonometri bulat permukaan buana untuk mengukur berapa meterkah jurang tarikan presisi antara dua titik GPS (latitude dan longitude guru merentang batas menuju pusat sekolah). Titik kordinat bersatu direduksi menuju derajat lintang dan bujurnya merupa parameter jarak pengikat radius murni.
