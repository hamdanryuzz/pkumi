@extends('layouts.app')

@section('title', 'Manage Nilai - Sistem Penilaian PKUMI')
@section('page-title', 'Manage Nilai')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Input dan Kelola Nilai Mahasiswa</h5>
        <small class="text-muted">
            Bobot saat ini: Presensi ({{ $weights->attendance_weight }}%), 
            Tugas ({{ $weights->assignment_weight }}%), 
            UTS ({{ $weights->midterm_weight }}%), 
            UAS ({{ $weights->final_weight }}%)
        </small>
    </div>
    <div class="card-body">
        <form id="gradesForm" action="{{ route('grades.bulkUpdate') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Presensi<br><small>({{ $weights->attendance_weight }}%)</small></th>
                            <th>Tugas<br><small>({{ $weights->assignment_weight }}%)</small></th>
                            <th>UTS<br><small>({{ $weights->midterm_weight }}%)</small></th>
                            <th>UAS<br><small>({{ $weights->final_weight }}%)</small></th>
                            <th>Nilai Akhir</th>
                            <th>Huruf</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{ $student->nim }}</td>
                            <td>{{ $student->name }}</td>
                            <td>
                                <input type="number" 
                                       class="form-control form-control-sm grade-input" 
                                       name="grades[{{ $student->grade->id }}][attendance_score]"
                                       value="{{ $student->grade->attendance_score }}"
                                       min="0" max="100" step="0.01">
                            </td>
                            <td>
                                <input type="number" 
                                       class="form-control form-control-sm grade-input" 
                                       name="grades[{{ $student->grade->id }}][assignment_score]"
                                       value="{{ $student->grade->assignment_score }}"
                                       min="0" max="100" step="0.01">
                            </td>
                            <td>
                                <input type="number" 
                                       class="form-control form-control-sm grade-input" 
                                       name="grades[{{ $student->grade->id }}][midterm_score]"
                                       value="{{ $student->grade->midterm_score }}"
                                       min="0" max="100" step="0.01">
                            </td>
                            <td>
                                <input type="number" 
                                       class="form-control form-control-sm grade-input" 
                                       name="grades[{{ $student->grade->id }}][final_score]"
                                       value="{{ $student->grade->final_score }}"
                                       min="0" max="100" step="0.01">
                            </td>
                            <td>
                                <span class="final-grade-display">
                                    {{ $student->grade->final_grade ? number_format($student->grade->final_grade, 2) : '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary letter-grade-display">
                                    {{ $student->grade->letter_grade ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan Semua Nilai
                </button>
                <button type="button" class="btn btn-secondary" onclick="calculatePreview()">
                    <i class="fas fa-calculator me-2"></i>Preview Perhitungan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function calculatePreview() {
    const weights = {
        attendance: {{ $weights->attendance_weight }},
        assignment: {{ $weights->assignment_weight }},
        midterm: {{ $weights->midterm_weight }},
        final: {{ $weights->final_weight }}
    };
    
    document.querySelectorAll('tbody tr').forEach(row => {
        const inputs = row.querySelectorAll('.grade-input');
        const attendance = parseFloat(inputs[0].value) || 0;
        const assignment = parseFloat(inputs[1].value) || 0;
        const midterm = parseFloat(inputs[2].value) || 0;
        const final = parseFloat(inputs[3].value) || 0;
        
        if (attendance && assignment && midterm && final) {
            const finalGrade = (
                (attendance * weights.attendance / 100) +
                (assignment * weights.assignment / 100) +
                (midterm * weights.midterm / 100) +
                (final * weights.final / 100)
            );
            
            let letterGrade = 'C';
            if (finalGrade >= 95) letterGrade = 'A+';
            else if (finalGrade >= 90) letterGrade = 'A';
            else if (finalGrade >= 85) letterGrade = 'A-';
            else if (finalGrade >= 80) letterGrade = 'B+';
            else if (finalGrade >= 75) letterGrade = 'B';
            else if (finalGrade >= 70) letterGrade = 'B-';
            
            row.querySelector('.final-grade-display').textContent = finalGrade.toFixed(2);
            row.querySelector('.letter-grade-display').textContent = letterGrade;
        }
    });
}

// Auto-calculate on input change
document.querySelectorAll('.grade-input').forEach(input => {
    input.addEventListener('input', calculatePreview);
});
</script>
@endsection