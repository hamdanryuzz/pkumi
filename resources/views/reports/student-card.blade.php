<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Nilai - {{ $student->name ?? 'Mahasiswa' }}</title>
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
        
        .meta-info {
            font-size: 11px;
            color: #666;
            margin-bottom: 15px;
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
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        }
        
        /* Print optimizations */
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .table-container { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Kartu Nilai Mahasiswa</h1>
        <h2>Program PKUMI (Pendidikan Kader Ulama Masjid Istiqlal)</h2>
    </div>

    <div class="info-section">
        <h3>Identitas Mahasiswa</h3>
        <div class="meta-info">
            <strong>Nama:</strong> {{ $student->name ?? '-' }} <br>
            <strong>NIM:</strong> {{ $student->nim ?? '-' }} <br>
            <strong>Periode:</strong> {{ $period ?? 'Semester Ganjil 2024/2025' }}
        </div>
    </div>

    <div class="table-container">
        <h3>HASIL PENILAIAN</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 20%">Jenis Nilai</th>
                    <th style="width: 20%">Nilai Angka</th>
                    <th style="width: 20%">Bobot</th>
                    <th style="width: 20%">Nilai Akhir Bobot</th>
                    <th style="width: 20%">Grade</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Presensi</td>
                    <td class="text-center score-cell">
                        {{ $student->grade && $student->grade->attendance_score !== null ? number_format($student->grade->attendance_score, 1) : '-' }}
                    </td>
                    <td class="text-center">{{ $weights->attendance_weight }}%</td>
                    <td class="text-center">
                        {{ $student->grade && $weights ? number_format(($student->grade->attendance_score ?? 0) * ($weights->attendance_weight / 100), 2) : '-' }}
                    </td>
                    <td class="text-center">-</td>
                </tr>
                <tr>
                    <td>Tugas</td>
                    <td class="text-center score-cell">
                        {{ $student->grade && $student->grade->assignment_score !== null ? number_format($student->grade->assignment_score, 1) : '-' }}
                    </td>
                    <td class="text-center">{{ $weights->assignment_weight }}%</td>
                    <td class="text-center">
                        {{ $student->grade && $weights ? number_format(($student->grade->assignment_score ?? 0) * ($weights->assignment_weight / 100), 2) : '-' }}
                    </td>
                    <td class="text-center">-</td>
                </tr>
                <tr>
                    <td>UTS</td>
                    <td class="text-center score-cell">
                        {{ $student->grade && $student->grade->midterm_score !== null ? number_format($student->grade->midterm_score, 1) : '-' }}
                    </td>
                    <td class="text-center">{{ $weights->midterm_weight }}%</td>
                    <td class="text-center">
                        {{ $student->grade && $weights ? number_format(($student->grade->midterm_score ?? 0) * ($weights->midterm_weight / 100), 2) : '-' }}
                    </td>
                    <td class="text-center">-</td>
                </tr>
                <tr>
                    <td>UAS</td>
                    <td class="text-center score-cell">
                        {{ $student->grade && $student->grade->final_score !== null ? number_format($student->grade->final_score, 1) : '-' }}
                    </td>
                    <td class="text-center">{{ $weights->final_weight }}%</td>
                    <td class="text-center">
                        {{ $student->grade && $weights ? number_format(($student->grade->final_score ?? 0) * ($weights->final_weight / 100), 2) : '-' }}
                    </td>
                    <td class="text-center">-</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right final-grade">Total Nilai Akhir</td>
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
            </tbody>
        </table>
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

    <div class="footer">
        <p><strong>Laporan ini dibuat secara otomatis oleh Sistem Informasi Akademik PKUMI</strong></p>
        <p>Dicetak pada: {{ date('d F Y H:i:s') }} WIB | Halaman 1 dari 1</p>
    </div>
</body>
</html>