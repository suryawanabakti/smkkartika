```php
    public function index(Request $request)
    {
        $query = ClassRoom::with(['major', 'teacher.user']);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('major_id') && $request->major_id != '') {
            $query->where('major_id', $request->major_id);
        }

        $classRooms = $query->latest()->paginate(10)->withQueryString();
        $majors = Major::all();

        return view('admin.classrooms.index', compact('classRooms', 'majors'));
    }
```

Fungsi `index` ini digunakan untuk menampilkan halaman utama daftar kelas pada panel admin. Pertama, sistem mengambil data `ClassRoom` beserta relasinya, yaitu jurusan (`major`) dan wali kelas (`teacher.user`). Jika admin memasukkan kata kunci pada pencarian, maka sistem akan memfilter nama kelas yang cocok. Sistem juga akan memfilter berdasarkan ID jurusan jika dipilih. Hasilnya kemudian diurutkan dari yang terbaru dan dilimit 10 data per halaman (pagination). Terakhir, data kelas dan daftar semua jurusan dilempar ke tampilan (view) `admin.classrooms.index`.

```php
    public function create()
    {
        $majors = Major::all();
        $teachers = Teacher::with('user')->get();
        return view('admin.classrooms.create', compact('majors', 'teachers'));
    }
```

Fungsi `create` ini bertugas untuk menampilkan halaman formulir tambah kelas baru. Sistem mengambil seluruh data jurusan (`$majors`) dan data guru beserta akun penggunanya (`$teachers`), kemudian mengarahkannya ke tampilan `admin.classrooms.create` sehingga form tersebut memiliki opsi pilihan jurusan dan wali kelas.

```php
    public function store(Request $request)
    {
        $validated = $request->validate([
            'major_id' => 'required|exists:majors,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'name' => 'required|string|max:255',
        ]);

        ClassRoom::create($validated);

        return redirect()->route('admin.classrooms.index')->with('success', 'Kelas berhasil dibuat.');
    }
```

Fungsi `store` digunakan untuk menangani proses penyimpanan data kelas baru setelah admin mensubmit formulir tambah kelas. Sistem akan memvalidasi data terlebih dahulu: `major_id` wajib ada di tabel jurusan, `teacher_id` boleh kosong tapi jika ada harus valid di tabel guru, dan `name` wajib diisi berupa teks maksimal 255 karakter. Jika validasi sukses, kelas baru dibuat di database. Kemudian, sistem mengarahkan admin kembali ke halaman daftar kelas dengan membawa pesan notifikasi sukses.

```php
    public function edit(ClassRoom $classroom)
    {
        $majors = Major::all();
        $teachers = Teacher::with('user')->get();
        return view('admin.classrooms.edit', compact('classroom', 'majors', 'teachers'));
    }
```

Fungsi `edit` bertujuan untuk menampilkan halaman formulir ubah data kelas. Sama seperti `create`, sistem mengambil semua data jurusan dan guru sebagai opsi pada form, dan menampilkan riwayat identitas kelas (`$classroom`) ke tampilan form pengubahan data.

```php
    public function update(Request $request, ClassRoom $classroom)
    {
        $validated = $request->validate([
            'major_id' => 'required|exists:majors,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'name' => 'required|string|max:255',
        ]);

        $classroom->update($validated);

        return redirect()->route('admin.classrooms.index')->with('success', 'Kelas berhasil diperbarui.');
    }
```

Fungsi `update` ini menyimpan pembaruan identitas maupun konfigurasi kelas ke sistem database. Sistem memvalidasi inputannya (jurusan, wali kelas, dan nama kelas) seperti proses tambah awal, lalu mengaplikasikan perubahan tersebut ke data kelas yang dipilih. Setelah berhasil tersimpan, admin diarahkan kembali ke daftar kelas dengan notifikasi perubahan sukses.

```php
    public function destroy(ClassRoom $classroom)
    {
        $classroom->delete();

        return redirect()->route('admin.classrooms.index')->with('success', 'Kelas berhasil dihapus.');
    }
```

Fungsi `destroy` digunakan untuk menghapus data kelas tertentu dari database secara permanen. Setelah penghapusan berhasil dieksekusi, sistem akan mengembalikan admin ke halaman list daftar kelas dan dan menampilkan peringatan hijau berpesan berhasil dihapus.
