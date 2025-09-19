@extends('layouts.app')

@section('title', 'Manage Nilai - Sistem Penilaian PKUMI')
@section('page-title', 'Manage Nilai')

@section('content')
<div class="bg-white rounded-lg shadow-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h5 class="text-lg font-bold text-gray-800 mb-2">Input dan Kelola Nilai Mahasiswa</h5>
        <div class="text-sm text-gray-600">
            <span class="font-medium">Bobot saat ini:</span>
            <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full mx-1">
                Presensi {{ $weights->attendance_weight }}%
            </span>
            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full mx-1">
                Tugas {{ $weights->assignment_weight }}%
            </span>
            <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full mx-1">
                UTS {{ $weights->midterm_weight }}%
            </span>
            <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full mx-1">
                UAS {{ $weights->final_weight }}%
            </span>
        </div>
    </div>
    
    <div class="p-6">
        <form id="gradesForm" action="{{ route('grades.bulkUpdate') }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                NIM
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                Nama
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                                <div>Presensi</div>
                                <div class="text-xs font-normal text-gray-300">({{ $weights->attendance_weight }}%)</div>
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                                <div>Tugas</div>
                                <div class="text-xs font-normal text-gray-300">({{ $weights->assignment_weight }}%)</div>
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                                <div>UTS</div>
                                <div class="text-xs font-normal text-gray-300">({{ $weights->midterm_weight }}%)</div>
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                                <div>UAS</div>
                                <div class="text-xs font-normal text-gray-300">({{ $weights->final_weight }}%)</div>
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                                Nilai Akhir
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                                Huruf
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $student)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $student->nim }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $student->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="number" 
                                       class="w-20 px-3 py-2 border border-gray-300 rounded-md text-sm text-center grade-input focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" 
                                       name="grades[{{ $student->grade->id }}][attendance_score]"
                                       value="{{ $student->grade->attendance_score }}"
                                       min="0" max="100" step="0.01"
                                       placeholder="0-100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="number" 
                                       class="w-20 px-3 py-2 border border-gray-300 rounded-md text-sm text-center grade-input focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" 
                                       name="grades[{{ $student->grade->id }}][assignment_score]"
                                       value="{{ $student->grade->assignment_score }}"
                                       min="0" max="100" step="0.01"
                                       placeholder="0-100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="number" 
                                       class="w-20 px-3 py-2 border border-gray-300 rounded-md text-sm text-center grade-input focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-colors duration-200" 
                                       name="grades[{{ $student->grade->id }}][midterm_score]"
                                       value="{{ $student->grade->midterm_score }}"
                                       min="0" max="100" step="0.01"
                                       placeholder="0-100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="number" 
                                       class="w-20 px-3 py-2 border border-gray-300 rounded-md text-sm text-center grade-input focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200" 
                                       name="grades[{{ $student->grade->id }}][final_score]"
                                       value="{{ $student->grade->final_score }}"
                                       min="0" max="100" step="0.01"
                                       placeholder="0-100">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-bold text-gray-900 final-grade-display bg-gray-100 px-3 py-1 rounded-full">
                                    {{ $student->grade->final_grade ? number_format($student->grade->final_grade, 2) : '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 letter-grade-display">
                                    {{ $student->grade->letter_grade ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 ease-in-out transform hover:scale-105 shadow-lg">
                    <i class="fas fa-save mr-2"></i>Simpan Semua Nilai
                </button>
                <button type="button" 
                        class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-200 ease-in-out transform hover:scale-105 shadow-lg" 
                        onclick="calculatePreview()">
                    <i class="fas fa-calculator mr-2"></i>Preview Perhitungan
                </button>
                <button type="button" 
                        class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-200 ease-in-out transform hover:scale-105 shadow-lg" 
                        onclick="autoCalculateAll()">
                    <i class="fas fa-magic mr-2"></i>Auto Calculate
                </button>
            </div>
        </form>
        
        <!-- Grade Statistics -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users fa-2x opacity-80"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">Total Mahasiswa</p>
                        <p class="text-2xl font-bold">{{ $students->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle fa-2x opacity-80"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">Nilai Lengkap</p>
                        <p class="text-2xl font-bold" id="complete-grades">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock fa-2x opacity-80"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">Belum Lengkap</p>
                        <p class="text-2xl font-bold" id="incomplete-grades">{{ $students->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-chart-line fa-2x opacity-80"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">Rata-rata Kelas</p>
                        <p class="text-2xl font-bold" id="class-average">-</p>
                    </div>
                </div>
            </div>
        </div>
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
    
    let completeGrades = 0;
    let totalFinalGrades = 0;
    let validGrades = 0;
    
    document.querySelectorAll('tbody tr').forEach(row => {
        const inputs = row.querySelectorAll('.grade-input');
        const attendance = parseFloat(inputs[0].value) || 0;
        const assignment = parseFloat(inputs[1].value) || 0;
        const midterm = parseFloat(inputs[2].value) || 0;
        const final = parseFloat(inputs[3].value) || 0;
        
        const finalGradeDisplay = row.querySelector('.final-grade-display');
        const letterGradeDisplay = row.querySelector('.letter-grade-display');
        
        if (attendance && assignment && midterm && final) {
            const finalGrade = (
                (attendance * weights.attendance / 100) +
                (assignment * weights.assignment / 100) +
                (midterm * weights.midterm / 100) +
                (final * weights.final / 100)
            );
            
            let letterGrade = 'C';
            let badgeColor = 'bg-yellow-100 text-yellow-800';
            
            if (finalGrade >= 95) {
                letterGrade = 'A+';
                badgeColor = 'bg-green-100 text-green-800';
            } else if (finalGrade >= 90) {
                letterGrade = 'A';
                badgeColor = 'bg-green-100 text-green-800';
            } else if (finalGrade >= 85) {
                letterGrade = 'A-';
                badgeColor = 'bg-green-100 text-green-800';
            } else if (finalGrade >= 80) {
                letterGrade = 'B+';
                badgeColor = 'bg-blue-100 text-blue-800';
            } else if (finalGrade >= 75) {
                letterGrade = 'B';
                badgeColor = 'bg-blue-100 text-blue-800';
            } else if (finalGrade >= 70) {
                letterGrade = 'B-';
                badgeColor = 'bg-blue-100 text-blue-800';
            } else if (finalGrade < 60) {
                badgeColor = 'bg-red-100 text-red-800';
            }
            
            finalGradeDisplay.textContent = finalGrade.toFixed(2);
            finalGradeDisplay.className = 'text-sm font-bold text-gray-900 final-grade-display bg-green-100 px-3 py-1 rounded-full';
            
            letterGradeDisplay.textContent = letterGrade;
            letterGradeDisplay.className = `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium letter-grade-display ${badgeColor}`;
            
            completeGrades++;
            totalFinalGrades += finalGrade;
            validGrades++;
        } else {
            finalGradeDisplay.className = 'text-sm font-bold text-gray-900 final-grade-display bg-gray-100 px-3 py-1 rounded-full';
            letterGradeDisplay.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 letter-grade-display';
        }
    });
    
    // Update statistics
    document.getElementById('complete-grades').textContent = completeGrades;
    document.getElementById('incomplete-grades').textContent = {{ $students->count() }} - completeGrades;
    document.getElementById('class-average').textContent = validGrades > 0 ? (totalFinalGrades / validGrades).toFixed(2) : '-';
}

function autoCalculateAll() {
    calculatePreview();
    
    // Show success message
    const successMessage = document.createElement('div');
    successMessage.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successMessage.innerHTML = '<i class="fas fa-check mr-2"></i>Perhitungan otomatis selesai!';
    document.body.appendChild(successMessage);
    
    setTimeout(() => {
        successMessage.remove();
    }, 3000);
}

// Auto-calculate on input change with debounce
let debounceTimer;
document.querySelectorAll('.grade-input').forEach(input => {
    input.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(calculatePreview, 500);
        
        // Add visual feedback
        this.classList.add('ring-2', 'ring-blue-300');
        setTimeout(() => {
            this.classList.remove('ring-2', 'ring-blue-300');
        }, 1000);
    });
});

// Initial calculation
document.addEventListener('DOMContentLoaded', calculatePreview);
</script>
@endsection