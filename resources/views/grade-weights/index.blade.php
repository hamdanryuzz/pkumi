@extends('layouts.app')

@section('title', 'Manage Bobot Nilai - Sistem Penilaian PKUMI')
@section('page-title', 'Manage Bobot Nilai')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Pengaturan Bobot Penilaian</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('grade-weights.update') }}" method="POST" id="weightForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="attendance_weight" class="form-label">Bobot Presensi (%)</label>
                                <input type="number" 
                                       class="form-control weight-input @error('attendance_weight') is-invalid @enderror" 
                                       id="attendance_weight" 
                                       name="attendance_weight" 
                                       value="{{ $weights->attendance_weight }}"
                                       min="0" max="100" step="0.01" required>
                                @error('attendance_weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assignment_weight" class="form-label">Bobot Tugas (%)</label>
                                <input type="number" 
                                       class="form-control weight-input @error('assignment_weight') is-invalid @enderror" 
                                       id="assignment_weight" 
                                       name="assignment_weight" 
                                       value="{{ $weights->assignment_weight }}"
                                       min="0" max="100" step="0.01" required>
                                @error('assignment_weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="midterm_weight" class="form-label">Bobot UTS (%)</label>
                                <input type="number" 
                                       class="form-control weight-input @error('midterm_weight') is-invalid @enderror" 
                                       id="midterm_weight" 
                                       name="midterm_weight" 
                                       value="{{ $weights->midterm_weight }}"
                                       min="0" max="100" step="0.01" required>
                                @error('midterm_weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="final_weight" class="form-label">Bobot UAS (%)</label>
                                <input type="number" 
                                       class="form-control weight-input @error('final_weight') is-invalid @enderror" 
                                       id="final_weight" 
                                       name="final_weight" 
                                       value="{{ $weights->final_weight }}"
                                       min="0" max="100" step="0.01" required>
                                @error('final_weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Total Bobot: <span id="totalWeight">{{ $weights->getTotalWeight() }}</span>%</strong>
                        <div id="weightStatus" class="mt-1"></div>
                    </div>
                    
                    @error('total')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    
                    <div class="d-flex justify-content-between">
                        <form action="{{ route('grade-weights.reset') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary" 
                                    onclick="return confirm('Reset ke bobot default (10%, 20%, 30%, 40%)?')">
                                <i class="fas fa-undo me-2"></i>Reset Default
                            </button>
                        </form>
                        
                        <button type="submit" class="btn btn-primary" id="saveButton">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Skala Penilaian Huruf</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nilai</th>
                            <th>Huruf</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>95 - 100</td><td><span class="badge bg-success">A+</span></td></tr>
                        <tr><td>90 - 94</td><td><span class="badge bg-success">A</span></td></tr>
                        <tr><td>85 - 89</td><td><span class="badge bg-primary">A-</span></td></tr>
                        <tr><td>80 - 84</td><td><span class="badge bg-primary">B+</span></td></tr>
                        <tr><td>75 - 79</td><td><span class="badge bg-warning">B</span></td></tr>
                        <tr><td>70 - 74</td><td><span class="badge bg-warning">B-</span></td></tr>
                        <tr><td>< 70</td><td><span class="badge bg-danger">C</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Contoh Perhitungan</h6>
            </div>
            <div class="card-body">
                <small class="text-muted">
                    <strong>Contoh:</strong><br>
                    Presensi: 90 × <span class="attendance-example">{{ $weights->attendance_weight }}</span>% = <span class="attendance-result">{{ 90 * $weights->attendance_weight / 100 }}</span><br>
                    Tugas: 85 × <span class="assignment-example">{{ $weights->assignment_weight }}</span>% = <span class="assignment-result">{{ 85 * $weights->assignment_weight / 100 }}</span><br>
                    UTS: 88 × <span class="midterm-example">{{ $weights->midterm_weight }}</span>% = <span class="midterm-result">{{ 88 * $weights->midterm_weight / 100 }}</span><br>
                    UAS: 92 × <span class="final-example">{{ $weights->final_weight }}</span>% = <span class="final-result">{{ 92 * $weights->final_weight / 100 }}</span><br>
                    <hr>
                    <strong>Total: <span class="total-example">{{ 90 * $weights->attendance_weight / 100 + 85 * $weights->assignment_weight / 100 + 88 * $weights->midterm_weight / 100 + 92 * $weights->final_weight / 100 }}</span></strong>
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function updateTotal() {
    const attendance = parseFloat(document.getElementById('attendance_weight').value) || 0;
    const assignment = parseFloat(document.getElementById('assignment_weight').value) || 0;
    const midterm = parseFloat(document.getElementById('midterm_weight').value) || 0;
    const final = parseFloat(document.getElementById('final_weight').value) || 0;
    
    const total = attendance + assignment + midterm + final;
    document.getElementById('totalWeight').textContent = total.toFixed(2);
    
    const statusDiv = document.getElementById('weightStatus');
    const saveButton = document.getElementById('saveButton');
    
    if (total === 100) {
        statusDiv.innerHTML = '<i class="fas fa-check-circle text-success"></i> Bobot sudah benar (100%)';
        statusDiv.className = 'mt-1 text-success';
        saveButton.disabled = false;
    } else {
        statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> Total bobot harus 100%';
        statusDiv.className = 'mt-1 text-warning';
        saveButton.disabled = true;
    }
    
    // Update example calculation
    updateExample(attendance, assignment, midterm, final);
}

function updateExample(attendance, assignment, midterm, final) {
    document.querySelector('.attendance-example').textContent = attendance;
    document.querySelector('.assignment-example').textContent = assignment;
    document.querySelector('.midterm-example').textContent = midterm;
    document.querySelector('.final-example').textContent = final;
    
    document.querySelector('.attendance-result').textContent = (90 * attendance / 100).toFixed(1);
    document.querySelector('.assignment-result').textContent = (85 * assignment / 100).toFixed(1);
    document.querySelector('.midterm-result').textContent = (88 * midterm / 100).toFixed(1);
    document.querySelector('.final-result').textContent = (92 * final / 100).toFixed(1);
    
    const total = (90 * attendance / 100) + (85 * assignment / 100) + (88 * midterm / 100) + (92 * final / 100);
    document.querySelector('.total-example').textContent = total.toFixed(1);
}

document.querySelectorAll('.weight-input').forEach(input => {
    input.addEventListener('input', updateTotal);
});

// Initial calculation
updateTotal();
</script>
@endsection