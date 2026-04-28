<!DOCTYPE html>
<html>
<head>
    <title>Data Guru</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>DATA GURU</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIP</th>
                <th>Jabatan</th>
                <th>Email</th>
                <th>L/P</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $key => $teacher)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $teacher->user->name }}</td>
                <td>{{ $teacher->nip }}</td>
                <td>{{ $teacher->position ?? '-' }}</td>
                <td>{{ $teacher->user->email }}</td>
                <td>{{ $teacher->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
