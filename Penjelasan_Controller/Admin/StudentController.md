```php
    public function index(Request $request)
    {
        $query = Student::with(['user', 'classRoom.major']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nis', 'like', '%' . $search . '%')
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
        }

        if ($request->has('class_id') && $request->class_id != '') {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->latest()->paginate(10)->withQueryString();
        $classRooms = ClassRoom::with('major')->get();

        return view('admin.students.index', compact('students', 'classRooms'));
    }
```

Fungsi `index` bertanggung jawab menguras lumbung pangkalan tabel siswa untuk dicacah-tampi menampilkan kumpulan murid selingkup sekolah di administrator. Ia menggandeng sekawanan jejak akun (`user`) serta merangkap ruang kelas sekaligus muasal jalurnya/jurusannya (`classRoom.major`). Sambil memajang ia bisa terpengaruh sejenak atas pancingan search: entah menyasar di nama siswa, identitas rupa NIS, dan atau selayang emailnya. Disisihkan pula bagi yang meninjau eksklusif lewat ID kelas tertentu. Hasil pungutan dilimit sekumpulan 10 demi sepuluh serta diserahkan melebarkan raga ke serambi antarmuka administrator murid (`students.index`).

```php
    public function create()
    {
        $classRooms = ClassRoom::with('major')->get();
        return view('admin.students.create', compact('classRooms'));
    }
```

Fungsi `create` bertugas murni mempresentasikan tata panggung formulir rilis baru. Ia tak luput menghimpun jajaran nama kelas siap pakai sehingga form pendaftaran siswa menyematkan ruang kelas aktual sebagai opsi centangnya kelak.

```php
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nis' => 'required|string|max:20|unique:students,nis',
            'class_id' => 'required|exists:class_rooms,id',
            'password' => 'required|string|min:8',
        ]);

        DB::transaction(function () use ($validated) {
            $studentRole = Role::where('name', 'student')->first();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $studentRole->id,
            ]);

            Student::create([
                'user_id' => $user->id,
                'nis' => $validated['nis'],
                'class_id' => $validated['class_id'],
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil dibuat.');
    }
```

Fungsi `store` menggigit andil krusial mendongkrak data pelajar teraju di sisi sistem. Semenjak usai submitnya dikendalikan dari pendaftaran form baru, `store` berjilbra memeriksa kemurnian entitas baris dan teksnya; wajib surel yang tidak kloning/teridentifikasi kembar dalam `users`, termasuk wajib utuh unik seantero NIS di lumbung `students`. Karena menaut ke dua lumbung (tabel User sang pemilik sandi & Student sebagai profil NIS), pembungkus diikrar `DB::transaction()` biar terhindar nestapa galat parsial putus ditengah jalan (kalau satu gagal akan di-rollback tanpa sisa). Baris pertama sang User akun terbentuk ditumbuhi cap peranan student, dan dibelakang merapat entitas profil siswanya (Student) menyantap NIS plus merapat mengikat ID kelasnya. Sejainya berhasil disusupkan maka sistem beranjak kembali mendirect indeks baris antrean dan ditumpangkannya maklumat persuksesan rintisannya.

```php
    public function edit(Student $student)
    {
        $classRooms = ClassRoom::with('major')->get();
        return view('admin.students.edit', compact('student', 'classRooms'));
    }
```

Fungsi `edit` diperintahkan menarik serpihan identitas lampau eksklusif seorang murid guna ditempel merekah sebagai acuan pembebanan modifikasi wujud pada bingkai halaman ubah identitas form `admin.students.edit`.

```php
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'nis' => 'required|string|max:20|unique:students,nis,' . $student->id,
            'class_id' => 'required|exists:class_rooms,id',
            'password' => 'nullable|string|min:8',
        ]);

        DB::transaction(function () use ($validated, $student, $request) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $student->user->update($userData);

            $student->update([
                'nis' => $validated['nis'],
                'class_id' => $validated['class_id'],
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil diperbarui.');
    }
```

Fungsi `update` menelan isian terkaliberasi sang modifikator semata untuk memberesi keotentikannya. Secara serantak mengawal dua lumbung berdampingan bagai transaksi sedia kala, merenovasi struktur penamaan raga ke lumbung `user` plus memastikan ia cuma dapat bersandi mutakhir jika input kotak sandinya tidak alpa (kosong) waktu dikirim sang pengubah (jika disisipi bakal otomatis menindih kode Hash). Lalu menginjeksi modifikasi khusus profil di ranah `Student` untuk penyesalan ganti balutan NIS serta ID mutasi kelas baru. Lantas sistem meredirect dengan manis menjauhi halaman ubah memutar kembali menoleh ke lumbung absensi per-pelajar.

```php
    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {
            $user = $student->user;
            $student->delete();
            $user->delete();
        });

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil dihapus.');
    }
```

Fungsi `destroy` memanggil sang dewa kehancuran mengkoordinir lenyapnya ikatan relasional antar pelajar tersebut. Supaya tidak jorok berceceran datanya, secara halus disembelih tabel pendaftaran pelajarnya kelak akun penopang inti sang usernya digiring menuju jurang kemusnahan permanen bersamaan. Ketiadaannya lalu diakhiri senyuman konfirmasi penghapusan aman dari arah rute indeks utamanya admin.
