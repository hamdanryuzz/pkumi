<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Nilai PKUMI</title>
    <style>
        @page {
            margin: 2cm 1.5cm;
            size: A4;
        }
        
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            font-size: 12px; 
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        
        .header h1 { 
            margin: 0 0 5px 0; 
            font-size: 18px; 
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .header h2 { 
            margin: 0 0 15px 0; 
            font-size: 14px; 
            color: #333;
            font-weight: normal;
        }
        
        .header .meta-info {
            font-size: 11px;
            color: #666;
            margin-top: 15px;
        }
        
        .info-section {
            margin-bottom: 20px;
            padding: 0;
        }
        
        .info-section h3 {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .weights-container {
            margin-top: 5px;
        }
        
        .weights-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 20px;
        }
        
        .weights-table th, .weights-table td {
            padding: 8px 6px;
            text-align: center;
            font-size: 11px;
            border: 1px solid #000;
        }
        
        .weights-table th {
            background-color: #f0f0f0;
            color: #000;
            font-weight: bold;
        }
        
        .weights-table td {
            font-weight: normal;
        }
        
        .table-container {
            margin-bottom: 25px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            background: white;
            border: 2px solid #000;
        }
        
        th, td { 
            padding: 8px 6px; 
            text-align: left; 
            font-size: 11px;
            border: 1px solid #000;
        }
        
        th { 
            background-color: #f0f0f0;
            color: #000;
            font-weight: bold;
            text-align: center;
        }
        
        .text-center { 
            text-align: center; 
        }
        
        .text-right {
            text-align: right;
        }
        
        .score-cell {
            font-weight: normal;
            text-align: center;
        }
        
        .final-grade {
            font-weight: bold;
            text-align: center;
        }
        
        .grade-badge {
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 3px;
        }
        
        .grade-A { background-color: #d4edda; color: #155724; }
        .grade-B { background-color: #d1ecf1; color: #0c5460; }
        .grade-C { background-color: #fff3cd; color: #856404; }
        .grade-D { background-color: #f8d7da; color: #721c24; }
        .grade-E { background-color: #e2e3e5; color: #383d41; }
        
        .summary-section {
            margin-bottom: 20px;
            padding: 0; /* Hapus padding */
            border: none; /* Hapus border */
        }
        
        .summary-section h3 {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000; /* Sesuaikan border dengan tabel utama */
            margin-bottom: 20px;
        }
        
        .summary-table th, .summary-table td {
            padding: 8px 6px;
            text-align: center;
            font-size: 11px;
            border: 1px solid #000; /* Sesuaikan border dengan tabel utama */
        }
        
        .summary-table th {
            background-color: #f0f0f0;
            color: #000;
            font-weight: bold;
        }
        
        .summary-table td {
            font-weight: normal;
        }
        
        .grade-distribution {
            margin-top: 15px;
        }
        
        .grade-dist-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 11px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        }
        
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 30px;
            text-align: center;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            padding: 10px 20px;
            vertical-align: top;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            margin: 30px auto 8px;
            width: 180px;
        }
        
        .signature-box p {
            margin: 5px 0;
            font-size: 12px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #64748b;
            font-style: italic;
        }
        
        /* Print optimizations */
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .table-container { box-shadow: none; }
            .summary-card { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Nilai Mahasiswa</h1>
        <h2>Program PKUMI (Pendidikan Kader Ulama Masjid Istiqlal)</h2>
        <div class="meta-info">
            <strong>semestere:</strong> {{ $semester ?? 'Semester Ganjil 2024/2025' }} 
        </div>
    </div>

    <div class="info-section">
        <h3>Bobot Penilaian</h3>
        <div class="weights-container">
            <table class="weights-table">
                <tr>
                    <th>Komponen</th>
                    <th>Presensi</th>
                    <th>Tugas</th>
                    <th>UTS</th>
                    <th>UAS</th>
                </tr>
                <tr>
                    <td>Bobot (%)</td>
                    <td>{{ $weights->attendance_weight }}</td>
                    <td>{{ $weights->assignment_weight }}</td>
                    <td>{{ $weights->midterm_weight }}</td>
                    <td>{{ $weights->final_weight }}</td>
                </tr>
            </table>
        </div>
    </div>

    @if($students->count() > 0)
        <h3>PENILAIAN MAHASISWA</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 12%">NIM</th>
                        <th style="width: 25%">Nama Mahasiswa</th>
                        <th style="width: 10%">Presensi</th>
                        <th style="width: 10%">Tugas</th>
                        <th style="width: 10%">UTS</th>
                        <th style="width: 10%">UAS</th>
                        <th style="width: 12%">Nilai Akhir</th>
                        <th style="width: 6%">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $student->nim ?? '-' }}</td>
                        <td>{{ $student->name ?? 'Nama tidak tersedia' }}</td>
                        <td class="text-center score-cell">
                            {{ $student->grade && $student->grade->attendance_score !== null ? number_format($student->grade->attendance_score, 1) : '-' }}
                        </td>
                        <td class="text-center score-cell">
                            {{ $student->grade && $student->grade->assignment_score !== null ? number_format($student->grade->assignment_score, 1) : '-' }}
                        </td>
                        <td class="text-center score-cell">
                            {{ $student->grade && $student->grade->midterm_score !== null ? number_format($student->grade->midterm_score, 1) : '-' }}
                        </td>
                        <td class="text-center score-cell">
                            {{ $student->grade && $student->grade->final_score !== null ? number_format($student->grade->final_score, 1) : '-' }}
                        </td>
                        <td class="text-center final-grade">
                            {{ $student->grade && $student->grade->final_grade ? number_format($student->grade->final_grade, 2) : '-' }}
                        </td>
                        <td class="text-center">
                            @if($student->grade && $student->grade->letter_grade)
                                <span class="grade-badge grade-{{ $student->grade->letter_grade }}">
                                    {{ $student->grade->letter_grade }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @php
            $completedGrades = $students->filter(fn($s) => $s->grade && $s->grade->final_grade !== null);
            $average = $completedGrades->count() > 0 ? $completedGrades->avg(fn($s) => $s->grade->final_grade) : 0;
            
            // Grade distribution
            $gradeDistribution = $completedGrades->groupBy(fn($s) => $s->grade->letter_grade ?? 'N/A')
                ->map(fn($group) => $group->count());
            $totalCompleted = $completedGrades->count();
        @endphp

        <div class="summary-section">
            <h3>Ringkasan Hasil Evaluasi</h3>
            <table class="summary-table">
                <tr>
                    <th>Indikator</th>
                    <th>Total Mahasiswa</th>
                    <th>Sudah Dinilai</th>
                    <th>Rata-rata Kelas</th>
                </tr>
                <tr>
                    <td>Nilai</td>
                    <td>{{ $students->count() }}</td>
                    <td>{{ $completedGrades->count() }}</td>
                    <td>{{ $average > 0 ? number_format($average, 2) : '-' }}</td>
                </tr>
            </table>

            @if($totalCompleted > 0)
                <div class="grade-distribution">
                    <h4 style="margin: 10px 0 8px 0; font-size: 12px; font-weight: bold;">Distribusi Nilai:</h4>
                    @foreach(['A', 'B', 'C', 'D', 'E'] as $grade)
                        @php $count = $gradeDistribution[$grade] ?? 0; @endphp
                        @if($count > 0)
                            <div class="grade-dist-item">
                                <span>Grade {{ $grade }}: {{ $count }} mahasiswa</span>
                                <span>({{ number_format(($count / $totalCompleted) * 100, 1) }}%)</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <p><strong>Koordinator Program</strong></p>
                <div class="signature-line"></div>
                <p>(...........................)</p>
            </div>
            <div class="signature-box">
                <p><strong>Kepala Bagian Akademik</strong></p>
                <div class="signature-line"></div>
                <p>(...........................)</p>
            </div>
        </div>
    @else
        <div class="no-data">
            <h3>Tidak ada data mahasiswa</h3>
            <p>Belum ada mahasiswa yang terdaftar dalam sistem atau data sedang dimuat.</p>
        </div>
    @endif

    <div class="footer">
        <p><strong>Laporan ini dibuat secara otomatis oleh Sistem Informasi Akademik PKUMI</strong></p>
        <p>Dicetak pada: {{ date('d F Y H:i:s') }} WIB | Halaman 1 dari 1</p>
    </div>
</body>
</html>