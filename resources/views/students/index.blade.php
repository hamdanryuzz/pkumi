@extends('layouts.app')

@section('title', 'Manage Mahasiswa - Sistem Penilaian PKUMI')
@section('page-title', 'Manage Mahasiswa')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Mahasiswa</h5>
        <div>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-upload me-1"></i>Import
            </button>
            <a href="{{ route('students.export') }}" class="btn btn-info btn-sm">
                <i class="fas fa-download me-1"></i>Export
            </a>
            <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Tambah Mahasiswa
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Nilai Akhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td>{{ $student->nim }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email ?? '-' }}</td>
                        <td>{{ $student->phone ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $student->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </td>
                        <td>
                            @if($student->grade && $student->grade->final_grade)
                                {{ number_format($student->grade->final_grade, 2) }} 
                                <span class="badge bg-primary">{{ $student->grade->letter_grade }}</span>
                            @else
                                <span class="text-muted">Belum ada nilai</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Yakin ingin menghapus mahasiswa ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data mahasiswa</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $students->links() }}
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih File Excel/CSV</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Format: NIM, Nama, Email, Telepon, Status</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection