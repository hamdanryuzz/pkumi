<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info-item {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $title }}</h2>
    </div>

    <div class="info">
        <div class="info-item"><strong>Mata Kuliah:</strong> {{ $course->code }} - {{ $course->name }}</div>
        <div class="info-item"><strong>Semester:</strong> {{ $semester->name }}</div>
        <div class="info-item"><strong>Angkatan:</strong> {{ $year->name }}</div>
        <div class="info-item"><strong>Kelas:</strong> {{ $studentClass->name }}</div>
        <div class="info-item"><strong>Tanggal Cetak:</strong> {{ date('d F Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">NIM</th>
                <th style="width: 40%;">Nama Mahasiswa</th>
                <th style="width: 20%;">Final Grade</th>
                <th style="width: 20%;">Letter Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
                @php
                    $grade = $student->grades->first();
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->nim }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $grade->final_grade ?? '-' }}</td>
                    <td>{{ $grade->letter_grade ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total Mahasiswa: {{ $students->count() }}</p>
    </div>
</body>
</html>
