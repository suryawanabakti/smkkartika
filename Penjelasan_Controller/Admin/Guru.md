```php
    public function index(Request $request)
    {
        $query = Teacher::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nip', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                  });
        }

        $teachers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.teachers.index', compact('teachers'));
    }
```

Fungsi `index` bertanggung jawab menguras lumbung pangkalan tabel pendidik/guru. Ia bekerja seragam bersama murid, memanggil para guru disertai entitas akun `user`-nya agar mampu merunut kaitan sandi profil. Bila fitur search dijalankan, maka ia sigap memburu profil guru merujuk relung kedalaman Nomor Induk Pegawainya (NIP) lalu menyelaraskan pada porsi namanya plus surel sang penggunanya. Setelah itu seluruh rincian memanjang menertibkan tayangan 10 entri sejilid pada dasbor view administrator spesifik laman barisan guru.

```php
    public function create()
    {
        return view('admin.teachers.create');
    }
```

Fungsi `create` hanya memancarkan kemunculan laman tatap muka pendataan penambahan entitas formulir sang pendidik sedari nol tanpa memompa serpih apa jua.

```php
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nip' => 'required|string|max:20|unique:teachers,nip',
            'password' => 'required|string|min:8',
        ]);

        DB::transaction(function() use ($validated) {
            $teacherRole = Role::where('name', 'teacher')->first();
            
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $teacherRole->id,
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
            ]);
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil dibuat.');
    }
```

Fungsi `store` mengarsiteki alur pendaftaran seutuhnya bagi penganut peran guru di kerahiman pangkalan sistem. Seusai ditapis kemurnian emailnya di batas ambang wajib steril kebaruan eksklusif lumbung akun dan tak boleh menduplikasi kembar identik dalam balutan relasi NIP sang pengajar, alurnya menjiwai sinkronisasi aman perpaduan dua elemen transaksi ganda (membentengi error cacat timpang): Menetaskan sang user pembonceng peranan cap "teacher" beserta pendelegasian nama bersandi teguh (Hash), lalu ditarik nafas kembali membikin identitas utuh pelengkap ke seluk beluk `Teacher` guna mematok NIP padanya sebelum melempar sukses melenggang pergi kembali menatap bilik baris data guru pertamanya administrator.

```php
    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }
```

Fungsi `edit` menyasar sebuah profil pengajar lampau guna diurai telanjang memaparkan nilai lama merangkap jadi modal sisipan porsi nilai formulir rupa halaman manipulasi.

```php
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'nip' => 'required|string|max:20|unique:teachers,nip,' . $teacher->id,
            'password' => 'nullable|string|min:8',
        ]);

        DB::transaction(function() use ($validated, $teacher, $request) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $teacher->user->update($userData);

            $teacher->update([
                'nip' => $validated['nip'],
            ]);
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil diperbarui.');
    }
```

Fungsi `update` bersinergi merekam segala revisi perbaruan entitas yang terhubung langsung dengan barisan pengguna guru. Usai menimbang kelayakan isiannya—sandi hanyalah tambahan modifikatif tak dipaksa ubah kala renggang, sistem menyelundup merampungkan pemugaran wujud pada peraduan `user` beserta pangkalan NIP sang pengajar di area database terkaitnya selaras aman sentosa hingga menyutradarai perpindahan arah menuntun layar menuju tabel depan sematan rute `admin.teachers.index`.

```php
    public function destroy(Teacher $teacher)
    {
        DB::transaction(function() use ($teacher) {
            $user = $teacher->user;
            $teacher->delete();
            $user->delete();
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil dihapus.');
    }
```

Fungsi `destroy` menghabisi atau mencopot profil entitas dari ruang singgah semestinya. Pemusnahan dikemas sedemikian ketat guna turut membenamkan seonggok entitas akun otentikasinya dengan profil raganya hingga bersih tersapu lumbung datanya dan dipalingkan melacak sisa kawanannya di gerbong depan daftar guru disertai stempel konfirmasi keperkasaannya terhapus rapi.
