@extends('layouts.app')

@section('title', 'Dashboard - Sistem Penilaian PKUMI')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Mahasiswa</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStudents }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Mahasiswa Aktif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeStudents }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Nilai Sudah Diinput</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $gradesEntered }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Rata-rata Kelas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $averageGrade ? number_format($averageGrade, 2) : '-' }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Distribusi Nilai Huruf</h6>
            </div>
            <div class="card-body">
                @if($gradeDistribution->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nilai Huruf</th>
                                    <th>Jumlah Mahasiswa</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gradeDistribution as $grade)
                                <tr>
                                    <td><span class="badge bg-primary">{{ $grade->letter_grade }}</span></td>
                                    <td>{{ $grade->count }}</td>
                                    <td>{{ number_format(($grade->count / $gradesEntered) * 100, 1) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Belum ada data nilai yang diinput.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('students.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Tambah Mahasiswa Baru
                    </a>
                    <a href="{{ route('grades.index') }}" class="btn btn-success">
                        <i class="fas fa-edit me-2"></i>Input Nilai Mahasiswa
                    </a>
                    <a href="{{ route('reports.exportPdf') }}" class="btn btn-info">
                        <i class="fas fa-file-pdf me-2"></i>Cetak Laporan PDF
                    </a>
                    <a href="{{ route('reports.exportExcel') }}" class="btn btn-warning">
                        <i class="fas fa-file-excel me-2"></i>Export ke Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection