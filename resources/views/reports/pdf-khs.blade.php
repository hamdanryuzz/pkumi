<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; margin-bottom: 30px; }
        h2 { margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; font-size: 12px; }
        th { background: #f4f4f4; }
        .student-block { margin-bottom: 30px; page-break-inside: avoid; }
        .info { font-size: 13px; margin-bottom: 5px; }
        .footer { text-align: right; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $title }}</h2>
        <p>{{ $semester->name }} | {{ $year->name }} | {{ $studentClass->name }}</p>
        <p><small>Dicetak: {{ date('d F Y') }}</small></p>
    </div>

    @foreach($students as $student)
    <div class="student-block">
        <div class="info"><strong>NIM:</strong> {{ $student->nim }}</div>
        <div class="info"><strong>Nama:</strong> {{ $student->name }}</div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mata Kuliah</th>
                    <th>Nilai Akhir</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                @forelse($student->grades as $index => $grade)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $grade->course->name ?? '-' }}</td>
                        <td style="text-align:center;">{{ $grade->final_grade ?? '-' }}</td>
                        <td style="text-align:center;">{{ $grade->letter_grade ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center;">Belum ada nilai</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endforeach

    <div class="footer">
        <p>Total Mahasiswa: {{ $students->count() }}</p>
    </div>
</body>
</html>
