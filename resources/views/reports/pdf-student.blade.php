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
        .student-info {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #f9f9f9;
        }
        .info-row {
            display: flex;
            margin: 8px 0;
        }
        .info-label {
            width: 150px;
            font-weight: bold;
        }
        .grades-section {
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .final-grade {
            background-color: #e8f5e9;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $title }}</h2>
        <p>{{ $course->code }} - {{ $course->name }}</p>
    </div>

    <div class="student-info">
        <h3>Data Mahasiswa</h3>
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 150px; font-weight: bold;">NIM</td>
                <td style="border: none;">: {{ $student->nim }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; font-weight: bold;">Nama</td>
                <td style="border: none;">: {{ $student->name }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; font-weight: bold;">Kelas</td>
                <td style="border: none;">: {{ $student->studentClass->name }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; font-weight: bold;">Angkatan</td>
                <td style="border: none;">: {{ $student->year->name }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none; font-weight: bold;">Semester</td>
                <td style="border: none;">: {{ $semester->name }}</td>
            </tr>
        </table>
    </div>

    <div class="grades-section">
        <h3>Rincian Nilai</h3>
        <table>
            <thead>
                <tr>
                    <th>Komponen Nilai</th>
                    <th style="width: 20%; text-align: center;">Skor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Nilai Kehadiran</td>
                    <td style="text-align: center;">{{ $grade->attendance_score }}</td>
                </tr>
                <tr>
                    <td>Nilai Tugas</td>
                    <td style="text-align: center;">{{ $grade->assignment_score }}</td>
                </tr>
                <tr>
                    <td>Nilai UTS (Midterm)</td>
                    <td style="text-align: center;">{{ $grade->midterm_score }}</td>
                </tr>
                <tr>
                    <td>Nilai UAS (Final)</td>
                    <td style="text-align: center;">{{ $grade->final_score }}</td>
                </tr>
                <tr class="final-grade">
                    <td>Nilai Akhir (Final Grade)</td>
                    <td style="text-align: center;">{{ $grade->final_grade }}</td>
                </tr>
                <tr class="final-grade">
                    <td>Huruf Mutu (Letter Grade)</td>
                    <td style="text-align: center;">{{ $grade->letter_grade }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 50px; text-align: right;">
        <p>Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>
</body>
</html>
