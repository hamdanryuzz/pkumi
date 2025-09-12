<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Nilai PKUMI</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header h2 { margin: 5px 0; font-size: 14px; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .badge { padding: 2px 6px; border-radius: 3px; color: white; background-color: #007bff; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN NILAI MAHASISWA</h1>
        <h2>Program PKUMI (Pendidikan Kader Ulama Masjid Istiqlal)</h2>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <div class="info">
        <h3>Bobot Penilaian:</h3>
        <p>
            Presensi: {{ $weights->attendance_weight }}% | 
            Tugas: {{ $weights->assignment_weight }}% | 
            UTS: {{ $weights->midterm_weight }}% | 
            UAS: {{ $weights->final_weight }}%
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Presensi</th>
                <th>Tugas</th>
                <th>UTS</th>
                <th>UAS</th>
                <th>Nilai Akhir</th>
                <th>Huruf</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $student->nim }}</td>
                <td>{{ $student->name }}</td>
                <td class="text-center">{{ $student->grade->attendance_score ?? '-' }}</td>
                <td class="text-center">{{ $student->grade->assignment_score ?? '-' }}</td>
                <td class="text-center">{{ $student->grade->midterm_score ?? '-' }}</td>
                <td class="text-center">{{ $student->grade->final_score ?? '-' }}</td>
                <td class="text-center">
                    {{ $student->grade->final_grade ? number_format($student->grade->final_grade, 2) : '-' }}
                </td>
                <td class="text-center">{{ $student->grade->letter_grade ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $completedGrades = $students->filter(fn($s) => $s->grade && $s->grade->final_grade);
        $average = $completedGrades->avg(fn($s) => $s->grade->final_grade);
    @endphp

    <div class="info">
        <h3>Ringkasan:</h3>
        <p>Total Mahasiswa: {{ $students->count() }}</p>
        <p>Sudah Dinilai: {{ $completedGrades->count() }}</p>
        <p>Rata-rata Kelas: {{ $average ? number_format($average, 2) : '-' }}</p>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
    </div>
</body>
</html>