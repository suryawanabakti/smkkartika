```php
    public function edit()
    {
        $settings = [
            'school_name' => SchoolSetting::get('school_name', 'SMK Kartika'),
            'school_latitude' => SchoolSetting::get('school_latitude', '-5.1436'),
            'school_longitude' => SchoolSetting::get('school_longitude', '119.4667'),
            'school_radius' => SchoolSetting::get('school_radius', '200'),
            'min_check_in_time' => SchoolSetting::get('min_check_in_time', '07:00'),
            'min_check_out_time' => SchoolSetting::get('min_check_out_time', '15:00'),
        ];

        return view('admin.settings.school', compact('settings'));
    }
```

Fungsi `edit` ini bertugas khusus memanggil lalu menampilkan formulir konfigurasi profil pengaturan sekolah ke layar admin. Sistem merangkai pengaturan nilai-nilai konfigurasi seperti lokasi sekolah berupa titik koordinat garis lintang dan bujurnya (latitude/longitude), radius meter jangkauan sah berabsensi, batas jam mula diperbolehkannya check in dan check out presensi (absen masuk dan pulang). Apabila tidak ada nilai paten tertinggal di database untuk atribut ini, ia pun menyuntikkan angka-angka atau teks bawaan (default) yang otomatis dirilis dan disajikan ke form di muka admin via file tampilan `admin.settings.school`.

```php
    public function update(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_latitude' => 'required|numeric|between:-90,90',
            'school_longitude' => 'required|numeric|between:-180,180',
            'school_radius' => 'required|numeric|min:50|max:5000',
            'min_check_in_time' => 'required|date_format:H:i',
            'min_check_out_time' => 'required|date_format:H:i',
        ]);

        foreach ($validated as $key => $value) {
            SchoolSetting::set($key, $value);
        }

        return redirect()->route('admin.settings.school')->with('success', 'Pengaturan lokasi sekolah berhasil diperbarui.');
    }
```

Fungsi `update` diakses secara tanggap seketika admin berhasil mesubmit klik tombol untuk simpan dalam form layar pengaturan sekolah sebelumnya. Fungsi ini mencegat kiriman isian data tersebut serta menggali dan menimbang apakah angka-angka wajar dimasukkan: misal batasan latitude tak bisa di atas 90 atau luar titik -90 rentangnya, sementara radius dipatok tidak kurang 50 meter keatas maksimal 5 km (5000 meter), jam masuk dan keluar divalidasi presisi layaknya waktu (Hour:minute / H:i). Selepas semuanya menembus persetujuan kelaikan data, barulah sistem membongkar rentetan key tersebut satu demi satu pada lilitan proses perulangan foreach untuk dilabelkan mutlak menyisip (`SchoolSetting::set()`) menimpa database secara murni. Proses pengikatan ini pun melempar perawatnya meninjau kembali lamannya dengan senyum riang (menambahkan teks pesan success).
