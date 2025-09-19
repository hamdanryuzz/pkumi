@extends('layouts.app')

@section('title', 'Manage Nilai - Sistem Penilaian PKUMI')
@section('page-title', 'Manage Nilai')

@section('content')
<main class="py-6 px-4 md:px-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Manage Nilai</h2>
        <p class="text-base text-gray-500 mt-1">Input dan kelola nilai mahasiswa</p>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="flex flex-wrap justify-between items-center px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-bold text-gray-800">Daftar Nilai Mahasiswa</h5>
            <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600 mt-2 sm:mt-0">
                <span class="font-medium mr-1">Bobot Saat Ini:</span>
                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                    Presensi {{ $weights->attendance_weight }}%
                </span>
                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                    Tugas {{ $weights->assignment_weight }}%
                </span>
                <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                    UTS {{ $weights->midterm_weight }}%
                </span>
                <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">
                    UAS {{ $weights->final_weight }}%
                </span>
            </div>
        </div>
        
        <div class="p-6">
            <form id="gradesForm" action="{{ route('grades.bulkUpdate') }}" method="POST">
                @csrf
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Presensi
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tugas
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    UTS
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    UAS
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nilai Akhir
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 letter-grade-display">
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
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    const weights = {
        attendance: {{ $weights->attendance_weight }},
        assignment: {{ $weights->assignment_weight }},
        midterm: {{ $weights->midterm_weight }},
        final: {{ $weights->final_weight }}
    };

    function getLetterGrade(finalGrade) {
        if (finalGrade >= 95) return { grade: 'A+', color: 'bg-green-100 text-green-800' };
        if (finalGrade >= 90) return { grade: 'A', color: 'bg-green-100 text-green-800' };
        if (finalGrade >= 85) return { grade: 'A-', color: 'bg-green-100 text-green-800' };
        if (finalGrade >= 80) return { grade: 'B+', color: 'bg-blue-100 text-blue-800' };
        if (finalGrade >= 75) return { grade: 'B', color: 'bg-blue-100 text-blue-800' };
        if (finalGrade >= 70) return { grade: 'B-', color: 'bg-blue-100 text-blue-800' };
        if (finalGrade >= 60) return { grade: 'C', color: 'bg-yellow-100 text-yellow-800' };
        if (finalGrade >= 50) return { grade: 'D', color: 'bg-orange-100 text-orange-800' };
        return { grade: 'E', color: 'bg-red-100 text-red-800' };
    }
    
    function calculatePreview() {
        let finalGradeSum = 0;
        let gradeCount = 0;

        document.querySelectorAll('tbody tr').forEach(row => {
            const inputs = row.querySelectorAll('.grade-input');
            const attendance = parseFloat(inputs[0].value) || 0;
            const assignment = parseFloat(inputs[1].value) || 0;
            const midterm = parseFloat(inputs[2].value) || 0;
            const final = parseFloat(inputs[3].value) || 0;
            
            const finalGradeDisplay = row.querySelector('.final-grade-display');
            const letterGradeDisplay = row.querySelector('.letter-grade-display');

            if (inputs[0].value && inputs[1].value && inputs[2].value && inputs[3].value) {
                const finalGrade = (
                    (attendance * weights.attendance / 100) +
                    (assignment * weights.assignment / 100) +
                    (midterm * weights.midterm / 100) +
                    (final * weights.final / 100)
                );
                
                const gradeInfo = getLetterGrade(finalGrade);
                
                finalGradeDisplay.textContent = finalGrade.toFixed(2);
                finalGradeDisplay.className = `text-sm font-bold text-gray-900 final-grade-display px-3 py-1 rounded-full ${gradeInfo.color.replace('100', '200').replace('800', '900')}`;
                
                letterGradeDisplay.textContent = gradeInfo.grade;
                letterGradeDisplay.className = `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium letter-grade-display ${gradeInfo.color}`;
                
                finalGradeSum += finalGrade;
                gradeCount++;
            } else {
                finalGradeDisplay.textContent = '-';
                finalGradeDisplay.className = 'text-sm font-bold text-gray-900 final-grade-display bg-gray-100 px-3 py-1 rounded-full';
                letterGradeDisplay.textContent = '-';
                letterGradeDisplay.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 letter-grade-display';
            }
        });
        
        // Update statistics (kalau ada)
        // document.getElementById('complete-grades').textContent = gradeCount;
        // document.getElementById('incomplete-grades').textContent = {{ $students->count() }} - gradeCount;
        // document.getElementById('class-average').textContent = gradeCount > 0 ? (finalGradeSum / gradeCount).toFixed(2) : '-';
    }

    function autoCalculateAll() {
        calculatePreview();
        const successMessage = document.createElement('div');
        successMessage.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        successMessage.innerHTML = '<i class="fas fa-check mr-2"></i>Perhitungan otomatis selesai!';
        document.body.appendChild(successMessage);
        
        setTimeout(() => {
            successMessage.remove();
        }, 3000);
    }
    
    let debounceTimer;
    document.querySelectorAll('.grade-input').forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(calculatePreview, 500);
            
            this.classList.add('ring-2', 'ring-blue-300');
            setTimeout(() => {
                this.classList.remove('ring-2', 'ring-blue-300');
            }, 1000);
        });
    });

    document.addEventListener('DOMContentLoaded', calculatePreview);
</script>
@endsection