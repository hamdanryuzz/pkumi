<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 30px 50px;
            font-size: 13px;
        }
        h2, h3 {
            text-align: center;
            margin: 0;
        }
        .program {
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
        }
        th {
            text-align: center;
            font-weight: bold;
        }
        .info-table td {
            border: none;
            padding: 3px 0;
        }
        .info-label {
            width: 180px;
        }
        .footer {
            margin-top: 40px;
            width: 100%;
            text-align: right;
        }
        .signature {
            margin-top: 60px;
            text-align: right;
            font-weight: bold;
        }
        .note {
            margin-top: 40px;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <h2><strong>KARTU HASIL STUDI SEMESTER {{ $semester->name }}</strong></h2>
    <h3>PENDIDIKAN KADER ULAMA MASJID ISTIQLAL</h3>

    <br>
    <table class="info-table">
        <tr>
            <td class="info-label">Nama Mahasiswa</td>
            <td>: {{ $student->name }}</td>
        </tr>
        <tr>
            <td>Tempat/Tgl. Lahir</td>
            <td>: {{ $student->place_of_birth ?? '-' }}, {{ \Carbon\Carbon::parse($student->date_of_birth ?? '')->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Nomor Induk Mahasiswa</td>
            <td>: {{ $student->nim }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>: {{ $student->gender === 'male' ? 'Laki-Laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td>Program Pendidikan</td>
            <td>: {{ $student->program ?? 'Magister Pendidikan Kader Ulama (S2)' }}</td>
        </tr>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th rowspan="2">NO</th>
                <th rowspan="2">MATA KULIAH</th>
                <th colspan="3">NILAI</th>
                <th rowspan="2">SKS</th>
                <th rowspan="2">NB*SKS</th>
            </tr>
            <tr>
                <th>Angka</th>
                <th>Huruf</th>
                <th>Bobot</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSks = 0;
                $totalBobotX_Sks = 0;
            @endphp

            @forelse($grades as $index => $grade)
                @php
                    $bobot = $grade->bobot ?? 0;
                    $sks = $grade->course->sks ?? 0;
                    $nbSks = $bobot * $sks;
                    $totalSks += $sks;
                    $totalBobotX_Sks += $nbSks;
                @endphp
                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td>{{ $grade->course->name ?? '-' }}</td>
                    <td style="text-align:center;">{{ $grade->final_grade ?? '-' }}</td>
                    <td style="text-align:center;">{{ $grade->letter_grade ?? '-' }}</td>
                    <td style="text-align:center;">{{ number_format($bobot, 2) }}</td>
                    <td style="text-align:center;">{{ $sks }}</td>
                    <td style="text-align:center;">{{ number_format($nbSks, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">Belum ada nilai</td>
                </tr>
            @endforelse

            <tr>
                <td colspan="5" style="text-align:right; font-weight:bold;">Jumlah</td>
                <td style="text-align:center; font-weight:bold;">{{ $totalSks }}</td>
                <td style="text-align:center; font-weight:bold;">{{ number_format($totalBobotX_Sks, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <br><br>

    @php
        $ipk = $totalSks > 0 ? round($totalBobotX_Sks / $totalSks, 2) : 0;
        $predikat = 'Cukup';
        if ($ipk >= 3.75) $predikat = 'Terpuji';
        elseif ($ipk >= 3.25) $predikat = 'Sangat Baik';
        elseif ($ipk >= 2.75) $predikat = 'Baik';
    @endphp

    <table style="width: 60%; margin-top: 20px;">
        <tr>
            <td style="width: 250px;">Indeks Prestasi Kumulatif (IPK)</td>
            <td>: {{ number_format($ipk, 2) }}</td>
        </tr>
        <tr>
            <td>Predikat</td>
            <td>: {{ $predikat }}</td>
        </tr>
    </table>

    <div class="note">
        <strong>KETERANGAN</strong><br>
        <strong>Singkatan:</strong><br>
        NB : Nilai Bobot<br>
        SKS : Satuan Kredit Semester<br>
        MK : Mata Kuliah
    </div>

    <div class="footer">
        <p>Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <br><br><br>
        <p><strong>Direktur Pendidikan Kader Ulama</strong><br>
        Masjid Istiqlal</p>
    </div>

</body>
</html>
