@extends('layouts.app')

@section('title', 'Manage Nilai - Sistem Penilaian PKUMI')

@section('page-title', 'Manage Nilai')

@section('content')
<main class="py-6 px-4 md:px-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Manage Nilai</h2>
        <p class="text-base text-gray-500 mt-1">Input dan kelola nilai mahasiswa per mata kuliah</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Course Selection Form -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="text-lg font-bold text-gray-800">Pilih Mata Kuliah</h5>
            <p class="text-sm text-gray-600 mt-1">Pilih mata kuliah untuk mengelola nilai mahasiswa</p>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('grades.index') }}" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <select name="course_id" id="course_id" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required onchange="this.form.submit()">
                        <option value="">-- Pilih Mata Kuliah --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" 
                                    {{ $selectedCourseId == $course->id ? 'selected' : '' }}>
                                {{ $course->name }} ({{ $course->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-search mr-2"></i>Tampilkan
                </button>
            </form>
        </div>
    </div>

    @if($selectedCourseId && $courses->find($selectedCourseId))
        @php
            $selectedCourse = $courses->find($selectedCourseId);
        @endphp
        
        <!-- Grades Management Form -->
        <form method="POST" action="{{ route('grades.store') }}" id="gradesForm">
            @csrf
            <input type="hidden" name="course_id" value="{{ $selectedCourseId }}">
            
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header with Course Info and Weights -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex flex-wrap justify-between items-center">
                        <div>
                            <h5 class="text-lg font-bold text-gray-800">
                                Daftar Nilai Mahasiswa
                            </h5>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-book mr-1"></i>{{ $selectedCourse->name }} 
                                <span class="text-gray-400">|</span> 
                                <i class="fas fa-code mr-1"></i>{{ $selectedCourse->code }}
                            </p>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600 mt-2 sm:mt-0">
                            <span class="font-medium mr-1">Bobot Penilaian:</span>
                            <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                <i class="fas fa-calendar-check mr-1"></i>Presensi {{ $weights->attendance_weight }}%
                            </span>
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                <i class="fas fa-tasks mr-1"></i>Tugas {{ $weights->assignment_weight }}%
                            </span>
                            <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                                <i class="fas fa-clipboard-check mr-1"></i>UTS {{ $weights->midterm_weight }}%
                            </span>
                            <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">
                                <i class="fas fa-graduation-cap mr-1"></i>UAS {{ $weights->final_weight }}%
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Grades Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-id-card mr-1"></i>NIM
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-user mr-1"></i>NAMA MAHASISWA
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-calendar-check mr-1"></i>PRESENSI<br>
                                    <span class="text-xs font-normal">({{ $weights->attendance_weight }}%)</span>
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-tasks mr-1"></i>TUGAS<br>
                                    <span class="text-xs font-normal">({{ $weights->assignment_weight }}%)</span>
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-clipboard-check mr-1"></i>UTS<br>
                                    <span class="text-xs font-normal">({{ $weights->midterm_weight }}%)</span>
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-graduation-cap mr-1"></i>UAS<br>
                                    <span class="text-xs font-normal">({{ $weights->final_weight }}%)</span>
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-calculator mr-1"></i>NILAI AKHIR
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-medal mr-1"></i>HURUF
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                @php
                                    $grade = $grades->get($student->id);
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $student->nim }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                                <span class="text-blue-600 font-semibold text-sm">
                                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                        </div>
                                    </td>
                                    
                                    <!-- Score Input Fields -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <input type="number" 
                                               name="grades[{{ $student->id }}][attendance_score]"
                                               value="{{ $grade?->attendance_score ?? '' }}"
                                               placeholder="0-100"
                                               min="0" max="100" step="0.01"
                                               class="score-input w-20 text-center border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               data-student="{{ $student->id }}" data-type="attendance">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <input type="number" 
                                               name="grades[{{ $student->id }}][assignment_score]"
                                               value="{{ $grade?->assignment_score ?? '' }}"
                                               placeholder="0-100"
                                               min="0" max="100" step="0.01"
                                               class="score-input w-20 text-center border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               data-student="{{ $student->id }}" data-type="assignment">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <input type="number" 
                                               name="grades[{{ $student->id }}][midterm_score]"
                                               value="{{ $grade?->midterm_score ?? '' }}"
                                               placeholder="0-100"
                                               min="0" max="100" step="0.01"
                                               class="score-input w-20 text-center border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               data-student="{{ $student->id }}" data-type="midterm">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <input type="number" 
                                               name="grades[{{ $student->id }}][final_score]"
                                               value="{{ $grade?->final_score ?? '' }}"
                                               placeholder="0-100"
                                               min="0" max="100" step="0.01"
                                               class="score-input w-20 text-center border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               data-student="{{ $student->id }}" data-type="final">
                                    </td>
                                    
                                    <!-- Calculated Results -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm font-bold" id="final-grade-{{ $student->id }}">
                                            {{ $grade?->final_grade ? number_format($grade->final_grade, 2) : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div id="letter-grade-{{ $student->id }}">
                                            @if($grade?->letter_grade)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $grade->letter_grade >= 'A' ? 'bg-green-100 text-green-800' : ($grade->letter_grade >= 'B' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ $grade->letter_grade }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-users text-4xl mb-4"></i>
                                        <p class="text-lg">Tidak ada data mahasiswa</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($students->count() > 0)
                <!-- Footer with Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Total {{ $students->count() }} mahasiswa
                    </div>
                    <div class="flex gap-3">
                        <button type="button" id="calculateAll" 
                                class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-200">
                            <i class="fas fa-calculator mr-2"></i>Preview Perhitungan
                        </button>
                        <button type="submit" 
                                class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200">
                            <i class="fas fa-save mr-2"></i>Simpan Semua Nilai
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </form>
    @elseif($selectedCourseId)
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Mata Kuliah Tidak Ditemukan</h3>
            <p class="text-gray-600">Mata kuliah yang dipilih tidak tersedia. Silakan pilih mata kuliah lain.</p>
        </div>
    @endif
</main>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate preview functionality
    const calculateButton = document.getElementById('calculateAll');
    const scoreInputs = document.querySelectorAll('.score-input');
    
    // Weights from server
    const weights = {
        attendance: {{ $weights->attendance_weight }},
        assignment: {{ $weights->assignment_weight }},
        midterm: {{ $weights->midterm_weight }},
        final: {{ $weights->final_weight }}
    };
    
    // Calculate final grade
    function calculateFinalGrade(attendance, assignment, midterm, final) {
        if (!attendance || !assignment || !midterm || !final) {
            return null;
        }
        
        const finalGrade = (
            (attendance * weights.attendance / 100) +
            (assignment * weights.assignment / 100) +
            (midterm * weights.midterm / 100) +
            (final * weights.final / 100)
        );
        
        return Math.round(finalGrade * 100) / 100;
    }
    
    // Get letter grade
    function getLetterGrade(score) {
        if (score >= 95) return 'A+';
        if (score >= 90) return 'A';
        if (score >= 85) return 'A-';
        if (score >= 80) return 'B+';
        if (score >= 75) return 'B';
        if (score >= 70) return 'B-';
        return 'C';
    }
    
    // Preview calculation for a student
    function previewCalculation(studentId) {
        const attendance = parseFloat(document.querySelector(`input[name="grades[${studentId}][attendance_score]"]`).value) || null;
        const assignment = parseFloat(document.querySelector(`input[name="grades[${studentId}][assignment_score]"]`).value) || null;
        const midterm = parseFloat(document.querySelector(`input[name="grades[${studentId}][midterm_score]"]`).value) || null;
        const finalScore = parseFloat(document.querySelector(`input[name="grades[${studentId}][final_score]"]`).value) || null;
        
        const finalGrade = calculateFinalGrade(attendance, assignment, midterm, finalScore);
        const letterGrade = finalGrade ? getLetterGrade(finalGrade) : null;
        
        // Update preview display
        const finalGradeElement = document.getElementById(`final-grade-${studentId}`);
        const letterGradeElement = document.getElementById(`letter-grade-${studentId}`);
        
        if (finalGrade) {
            finalGradeElement.innerHTML = `<span class="text-blue-600">${finalGrade.toFixed(2)} *</span>`;
            
            const badgeColor = letterGrade >= 'A' ? 'bg-green-100 text-green-800' : 
                              letterGrade >= 'B' ? 'bg-blue-100 text-blue-800' : 
                              'bg-gray-100 text-gray-800';
                              
            letterGradeElement.innerHTML = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${badgeColor}">${letterGrade} *</span>`;
        } else {
            finalGradeElement.innerHTML = '<span class="text-gray-400">-</span>';
            letterGradeElement.innerHTML = '<span class="text-gray-400">-</span>';
        }
    }
    
    // Calculate all students preview
    if (calculateButton) {
        calculateButton.addEventListener('click', function() {
            scoreInputs.forEach(input => {
                const studentId = input.getAttribute('data-student');
                previewCalculation(studentId);
            });
            
            // Show info message
            const infoDiv = document.createElement('div');
            infoDiv.className = 'bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6';
            infoDiv.innerHTML = '<strong class="font-bold">Preview Perhitungan!</strong> <span class="block sm:inline">Nilai yang ditampilkan dengan tanda (*) adalah hasil perhitungan sementara. Klik "Simpan" untuk menyimpan ke database.</span>';
            
            const existingInfo = document.querySelector('.bg-blue-100');
            if (existingInfo) existingInfo.remove();
            
            document.querySelector('main').insertBefore(infoDiv, document.querySelector('.bg-white'));
            
            setTimeout(() => infoDiv.remove(), 5000);
        });
    }
    
    // Auto-preview on input change
    scoreInputs.forEach(input => {
        input.addEventListener('input', function() {
            const studentId = this.getAttribute('data-student');
            setTimeout(() => previewCalculation(studentId), 300);
        });
    });
    
    // Form validation
    const form = document.getElementById('gradesForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const hasAnyInput = Array.from(scoreInputs).some(input => input.value.trim() !== '');
            
            if (!hasAnyInput) {
                e.preventDefault();
                alert('Silakan masukkan minimal satu nilai sebelum menyimpan.');
                return false;
            }
        });
    }
});
</script>
@endsection
