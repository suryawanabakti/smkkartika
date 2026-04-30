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
    <div style="margin-top: 40px; width: 100%;">
        <table style="border: none; width: 100%;">
            <tr>
                <td style="border: none; width: 60%;"></td>
                <td style="border: none; width: 40%; text-align: center; font-size: 11px;">
                    <p>Makassar, {{ Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p style="font-weight: bold; margin-top: 5px;">Tata Usaha (Admin)</p>
                    <div style="height: 60px;"></div>
                    <p style="font-weight: bold; text-decoration: underline;">(..........................................)</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
