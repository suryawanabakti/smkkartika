```php
    public function index(Request $request)
    {
        $query = Major::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        $majors = $query->latest()->paginate(10)->withQueryString();

        return view('admin.majors.index', compact('majors'));
    }
```

Fungsi `index` ini digunakan untuk memunculkan antarmuka daftar tabel jurusan (Major) di panel administrator. Sistem mempersiapkan pencarian ke tabel jurusan, jika admin mengetikkan pencarian di kolom search, maka data yang ditampilkan hanya jurusan dengan nama atau kode yang mirip nilai inputannya. Hasilnya lalu diambil, diurutkan dari yang terbaru (`latest()`), dilimit ke 10 hasil per halaman, dan dilemparkan ke tampilan view tabel jurusan.

```php
    public function create()
    {
        return view('admin.majors.create');
    }
```

Fungsi `create` bertugas mutlak hanya untuk menampilkan rupa halaman formulir pembuatan jurusan baru. Tanpa ada olah data, sistem langsung diarahkan memuat template `admin.majors.create`.

```php
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:majors,code',
        ]);

        Major::create($validated);

        return redirect()->route('admin.majors.index')->with('success', 'Jurusan berhasil dibuat.');
    }
```

Fungsi `store` digunakan untuk menangani proses insersi atau memasukkan jurusan baru ke database. Permintaan data difilter lalu divalidasi keabsahannya, yaitu nama wajib diisi teks maksimal 255 huruf, dan kode wajib diisi berupa teks maksimal 10 huruf dan kodenya harus bersifat unik (belum ada di database). Jika berhasil menembus filter, jurusan baru dibuat. Layar kemudian dipaksa kembali mendirect ke indeks jurusan dilengkapi notifikasi sukses.

```php
    public function edit(Major $major)
    {
        return view('admin.majors.edit', compact('major'));
    }
```

Fungsi `edit` ini berperan melayani tampil halaman formulir ubah data untuk spesifik suatu jurusan. Identitas atau baris data jurusan dari ID yang diminta dimunculkan ke tampilan pengubahan (`admin.majors.edit`).

```php
    public function update(Request $request, Major $major)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:majors,code,' . $major->id,
        ]);

        $major->update($validated);

        return redirect()->route('admin.majors.index')->with('success', 'Jurusan berhasil diperbarui.');
    }
```

Fungsi `update` berfungsi merekam ulang data jurusan yang telah ada dan dimodifikasi sebelumnya di form edisi edit. Proses validasi memiliki pengecualian dalam aturan keunikan (boleh menggunakan kodenya sendiri). Apabila syarat telah terpenuhi keseluruhan, baris data jurusan tereksekusi pembaruannya. Dan halaman dilempar kembali ke indeks daftar jurusan.

```php
    public function destroy(Major $major)
    {
        $major->delete();

        return redirect()->route('admin.majors.index')->with('success', 'Jurusan berhasil dihapus.');
    }
```

Fungsi `destroy` menangani pembasmian atau penghapusan total atas suatu jurusan yang ingin disingkirkan dari pencatatan database. Sistem menyingkirkannya, kemudian beralih ke rute daftar jurusan sambil mengimbuhkan pesan kelancaran penghapusan.
