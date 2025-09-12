@extends('layouts.app')

@section('title', 'Laporan - Sistem Penilaian PKUMI')
@section('page-title', 'Laporan & Cetak Nilai')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Export Laporan</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('reports.exportPdf') }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf me-2"></i>Download Laporan PDF
                    </a>
                    <a href="{{ route('reports.exportExcel') }}" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Download Laporan Excel
                    </a>
                </div>
                
                <hr>
                
                <h6>Cetak Kartu Nilai Individual</h6>
                <form action="#" method="GET" class="mt-2">
                    <div class="mb-3">
                        <select class="form-select" id="studentSelect" onchange="printStudentCard()">
                            <option value="">Pilih Mahasiswa...</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->nim }} - {{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Bobot Penilaian Saat Ini</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td>Presensi</td>
                        <td>{{ $weights->attendance_weight }}%</td>
                    </tr>
                    <tr>
                        <td>Tugas</td>
                        <td>{{ $weights->assignment_weight }}%</td>
                    </tr>
                    <tr>
                        <td>UTS</td>
                        <td>{{ $weights->midterm_weight }}%</td>
                    </tr>
                    <tr>
                        <td>UAS</td>
                        <td>{{ $weights->final_weight }}%</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Rekap Nilai Mahasiswa</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Presensi</th>
                                <th>Tugas</th>
                                <th>UTS</th>
                                <th>UAS</th>
                                <th>Nilai Akhir</th>
                                <th>Huruf</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>{{ $student->nim }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->grade->attendance_score ?? '-' }}</td>
                                <td>{{ $student->grade->assignment_score ?? '-' }}</td>
                                <td>{{ $student->grade->midterm_score ?? '-' }}</td>
                                <td>{{ $student->grade->final_score ?? '-' }}</td>
                                <td>
                                    @if($student->grade->final_grade)
                                        <strong>{{ number_format($student->grade->final_grade, 2) }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->grade->letter_grade)
                                        <span class="badge bg-primary">{{ $student->grade->letter_grade }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('reports.studentCard', $student->id) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Cetak Kartu Nilai">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Statistik Kelas</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $completedGrades = $students->filter(fn($s) => $s->grade && $s->grade->final_grade);
                            $average = $completedGrades->avg(fn($s) => $s->grade->final_grade);
                            $highest = $completedGrades->max(fn($s) => $s->grade->final_grade);
                            $lowest = $completedGrades->min(fn($s) => $s->grade->final_grade);
                        @endphp
                        
                        <table class="table table-sm">
                            <tr>
                                <td>Total Mahasiswa</td>
                                <td><strong>{{ $students->count() }}</strong></td>
                            </tr>
                            <tr>
                                <td>Sudah Dinilai</td>
                                <td><strong>{{ $completedGrades->count() }}</strong></td>
                            </tr>
                            <tr>
                                <td>Rata-rata Kelas</td>
                                <td><strong>{{ $average ? number_format($average, 2) : '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Nilai Tertinggi</td>
                                <td><strong>{{ $highest ? number_format($highest, 2) : '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Nilai Terendah</td>
                                <td><strong>{{ $lowest ? number_format($lowest, 2) : '-' }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Distribusi Nilai</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $gradeDistribution = $completedGrades->groupBy(fn($s) => $s->grade->letter_grade);
                        @endphp
                        
                        <table class="table table-sm">
                            @foreach(['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C'] as $grade)
                                <tr>
                                    <td><span class="badge bg-primary">{{ $grade }}</span></td>
                                    <td>{{ $gradeDistribution->get($grade, collect())->count() }} mahasiswa</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function printStudentCard() {
    const select = document.getElementById('studentSelect');
    const studentId = select.value;
    
    if (studentId) {
        window.open(`{{ url('reports/student-card') }}/${studentId}`, '_blank');
    }
}
</script>
@endsection