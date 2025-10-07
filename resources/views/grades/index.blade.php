@extends('layouts.app')

@section('title', 'Kelola Nilai - Sistem Penilaian PKUMI')

@section('page-title', 'Kelola Nilai')

@section('content')
<main class="py-6 px-4 md:px-8">
    <!-- Header Section -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Kelola Nilai</h2>
        <p class="text-base text-gray-500 mt-1">Input dan kelola nilai mahasiswa per mata kuliah berdasarkan semestere</p>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
 
    <!-- Error Alert -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <strong class="font-bold">Error!</strong>
            <ul class="list-disc list-inside mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Course and semester Selection Form -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h5 class="text-lg font-bold text-gray-800">
                <i class="fas fa-filter mr-2"></i>Pilih Mata Kuliah dan semester
            </h5>
            <p class="text-sm text-gray-600 mt-1">Pilih mata kuliah dan semestere untuk mengelola nilai mahasiswa</p>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('grades.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
                <!-- semester Selection -->
                <div>
                    <label for="semester_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-1"></i>semester 
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="semester_id" id="semester_id" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required>
                        <option value="">Pilih semester</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ $selectedSemesterId == $semester->id ? 'selected' : '' }}>
                                {{ $semester->name }}
                                @if($semester->status === 'active')
                                    <span class="text-green-600">(Aktif)</span>
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Course Selection -->
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-book mr-1"></i>Mata Kuliah 
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="course_id" id="course_id" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            required>
                        <option value="">Pilih Mata Kuliah</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ $selectedCourseId == $course->id ? 'selected' : '' }}>
                                {{ $course->name }} ({{ $course->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-search mr-2"></i>Tampilkan Mahasiswa
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($selectedCourseId && $selectedSemesterId)
        <!-- Selected Course and semester Info -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                <div class="flex justify-between items-center">
                    <div>
                        <h5 class="text-lg font-bold text-gray-800">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            {{ $courses->find($selectedCourseId)->name ?? 'Unknown Course' }}
                        </h5>
                        <p class="text-sm text-gray-600 mt-1">
                            <i class="fas fa-calendar mr-1"></i>{{ $semester->find($selectedSemesterId)->name ?? 'Unknown semester' }} |
                            <i class="fas fa-users mr-1"></i>{{ $students->count() }} mahasiswa terdaftar
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('enrollments.index', ['course_id' => $selectedCourseId, 'semester_id' => $selectedSemesterId]) }}" 
                           class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200">
                            <i class="fas fa-list mr-2"></i>Lihat Enrollment
                        </a>
                        @if($students->count() > 0)
                            <button type="button" onclick="exportGrades()" 
                                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                                <i class="fas fa-file-excel mr-2"></i>Export
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($students->count() > 0)
            <!-- Grade Weights Info -->
            @if($weights)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-weight-hanging text-blue-600 mr-2"></i>
                        <h6 class="font-bold text-blue-800">Bobot Penilaian</h6>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-blue-700">Kehadiran:</span>
                            <span class="font-medium">{{ $weights->attendance_weight }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-700">Tugas:</span>
                            <span class="font-medium">{{ $weights->assignment_weight }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-700">UTS:</span>
                            <span class="font-medium">{{ $weights->midterm_weight }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-700">UAS:</span>
                            <span class="font-medium">{{ $weights->final_weight }}%</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Grades Form -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-amber-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <h5 class="text-lg font-bold text-gray-800">
                                <i class="fas fa-edit mr-2"></i>Input Nilai Mahasiswa
                            </h5>
                            <p class="text-sm text-gray-600 mt-1">Masukkan nilai untuk setiap komponen penilaian (0-100)</p>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="clearAllGrades()" 
                                    class="bg-red-600 text-white px-3 py-1 text-sm rounded-md hover:bg-red-700 transition duration-200">
                                <i class="fas fa-eraser mr-1"></i>Clear All
                            </button>
                            <button type="button" onclick="calculateAllGrades()" 
                                    class="bg-purple-600 text-white px-3 py-1 text-sm rounded-md hover:bg-purple-700 transition duration-200">
                                <i class="fas fa-calculator mr-1"></i>Hitung Semua
                            </button>
                        </div>
                    </div>
                </div>

                <form action="{{ route('grades.store') }}" method="POST" id="gradesForm">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $selectedCourseId }}">
                    <input type="hidden" name="semester_id" value="{{ $selectedSemesterId }}">
                    
                    <div class="overflow-x-auto">
                        <table class="w-full" id="gradesTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10 border-r border-gray-200">
                                        <i class="fas fa-user mr-1"></i>Mahasiswa
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                                        <i class="fas fa-calendar-check mr-1 text-blue-600"></i>
                                        Kehadiran<br>
                                        <span class="text-xs font-normal">({{ $weights->attendance_weight ?? 0 }}%)</span>
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                                        <i class="fas fa-tasks mr-1 text-green-600"></i>
                                        Tugas<br>
                                        <span class="text-xs font-normal">({{ $weights->assignment_weight ?? 0 }}%)</span>
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                                        <i class="fas fa-book-open mr-1 text-yellow-600"></i>
                                        UTS<br>
                                        <span class="text-xs font-normal">({{ $weights->midterm_weight ?? 0 }}%)</span>
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                                        <i class="fas fa-medal mr-1 text-red-600"></i>
                                        UAS<br>
                                        <span class="text-xs font-normal">({{ $weights->final_weight ?? 0 }}%)</span>
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[100px]">
                                        <i class="fas fa-calculator mr-1 text-purple-600"></i>
                                        Nilai Akhir
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[80px]">
                                        <i class="fas fa-trophy mr-1 text-orange-600"></i>
                                        Grade
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($students as $student)
                                    @php
                                        $grade = $grades->get($student->id);
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors duration-150" data-student-id="{{ $student->id }}">
                                        <!-- Student Info -->
                                        <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white z-10 border-r border-gray-200">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                                    <span class="text-blue-600 font-semibold text-sm">
                                                        {{ strtoupper(substr($student->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $student->nim }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Attendance Score -->
                                        <td class="px-4 py-4 text-center">
                                            <input type="number" 
                                                   name="grades[{{ $student->id }}][attendance_score]" 
                                                   value="{{ old("grades.{$student->id}.attendance_score", $grade->attendance_score ?? '') }}"
                                                   min="0" max="100" step="0.01"
                                                   class="w-20 text-center border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 grade-input"
                                                   data-field="attendance_score"
                                                   placeholder="0-100">
                                        </td>

                                        <!-- Assignment Score -->
                                        <td class="px-4 py-4 text-center">
                                            <input type="number" 
                                                   name="grades[{{ $student->id }}][assignment_score]" 
                                                   value="{{ old("grades.{$student->id}.assignment_score", $grade->assignment_score ?? '') }}"
                                                   min="0" max="100" step="0.01"
                                                   class="w-20 text-center border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-green-500 grade-input"
                                                   data-field="assignment_score"
                                                   placeholder="0-100">
                                        </td>

                                        <!-- Midterm Score -->
                                        <td class="px-4 py-4 text-center">
                                            <input type="number" 
                                                   name="grades[{{ $student->id }}][midterm_score]" 
                                                   value="{{ old("grades.{$student->id}.midterm_score", $grade->midterm_score ?? '') }}"
                                                   min="0" max="100" step="0.01"
                                                   class="w-20 text-center border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-yellow-500 grade-input"
                                                   data-field="midterm_score"
                                                   placeholder="0-100">
                                        </td>

                                        <!-- Final Score -->
                                        <td class="px-4 py-4 text-center">
                                            <input type="number" 
                                                   name="grades[{{ $student->id }}][final_score]" 
                                                   value="{{ old("grades.{$student->id}.final_score", $grade->final_score ?? '') }}"
                                                   min="0" max="100" step="0.01"
                                                   class="w-20 text-center border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-red-500 grade-input"
                                                   data-field="final_score"
                                                   placeholder="0-100">
                                        </td>

                                        <!-- Final Grade (Calculated) -->
                                        <td class="px-4 py-4 text-center">
                                            <div class="final-grade font-bold text-lg {{ $grade && $grade->final_grade ? 'text-purple-600' : 'text-gray-400' }}">
                                                {{ $grade && $grade->final_grade ? number_format($grade->final_grade, 2) : '-' }}
                                            </div>
                                        </td>

                                        <!-- Letter Grade -->
                                        <td class="px-4 py-4 text-center">
                                            <div class="letter-grade">
                                                @if($grade && $grade->letter_grade)
                                                    @php
                                                        $gradeColors = [
                                                            'A+' => 'bg-green-100 text-green-800',
                                                            'A' => 'bg-green-100 text-green-800',
                                                            'A-' => 'bg-blue-100 text-blue-800',
                                                            'B+' => 'bg-blue-100 text-blue-800',
                                                            'B' => 'bg-yellow-100 text-yellow-800',
                                                            'B-' => 'bg-yellow-100 text-yellow-800',
                                                            'C' => 'bg-red-100 text-red-800'
                                                        ];
                                                        $colorClass = $gradeColors[$grade->letter_grade] ?? 'bg-gray-100 text-gray-800';
                                                    @endphp
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colorClass }}">
                                                        {{ $grade->letter_grade }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Nilai akan otomatis dihitung berdasarkan bobot yang telah ditentukan
                            </div>
                            <div class="flex gap-3">
                                <button type="button" onclick="resetForm()" 
                                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                                    <i class="fas fa-undo mr-2"></i>Reset
                                </button>
                                <button type="submit" 
                                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                                    <i class="fas fa-save mr-2"></i>Simpan Nilai
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <!-- No Students Enrolled -->
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <i class="fas fa-user-slash text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Tidak Ada Mahasiswa Terdaftar</h3>
                <p class="text-gray-600 mb-4">
                    Belum ada mahasiswa yang terdaftar pada mata kuliah ini di semester yang dipilih.
                </p>
                <a href="{{ route('enrollments.create', ['course_id' => $selectedCourseId, 'semester_id' => $selectedSemesterId]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-user-plus mr-2"></i>Tambah Enrollment
                </a>
            </div>
        @endif
    @else
        <!-- Initial State - No Selection -->
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Pilih semester dan Mata Kuliah</h3>
            <p class="text-gray-600">
                Pilih semester dan mata kuliah di atas untuk mulai mengelola nilai mahasiswa.
            </p>
        </div>
    @endif
</main>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Grade weights from backend
    const weights = {
        attendance: {{ $weights->attendance_weight ?? 0 }} / 100,
        assignment: {{ $weights->assignment_weight ?? 0 }} / 100,
        midterm: {{ $weights->midterm_weight ?? 0 }} / 100,
        final: {{ $weights->final_weight ?? 0 }} / 100
    };

    // Add event listeners to all grade inputs
    document.querySelectorAll('.grade-input').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('tr');
            calculateRowGrade(row);
        });

        input.addEventListener('blur', function() {
            // Validate input value
            const value = parseFloat(this.value);
            if (value > 100) {
                this.value = 100;
                this.classList.add('border-red-500');
                setTimeout(() => this.classList.remove('border-red-500'), 2000);
            } else if (value < 0) {
                this.value = 0;
                this.classList.add('border-red-500');
                setTimeout(() => this.classList.remove('border-red-500'), 2000);
            }
        });
    });

    function calculateRowGrade(row) {
        const attendanceInput = row.querySelector('[data-field="attendance_score"]');
        const assignmentInput = row.querySelector('[data-field="assignment_score"]');
        const midtermInput = row.querySelector('[data-field="midterm_score"]');
        const finalInput = row.querySelector('[data-field="final_score"]');
        
        const attendance = parseFloat(attendanceInput.value) || 0;
        const assignment = parseFloat(assignmentInput.value) || 0;
        const midterm = parseFloat(midtermInput.value) || 0;
        const final = parseFloat(finalInput.value) || 0;

        // Calculate final grade only if all scores are entered
        if (attendance > 0 || assignment > 0 || midterm > 0 || final > 0) {
            const finalGrade = (
                attendance * weights.attendance +
                assignment * weights.assignment +
                midterm * weights.midterm +
                final * weights.final
            );

            // Update final grade display
            const finalGradeElement = row.querySelector('.final-grade');
            finalGradeElement.textContent = finalGrade.toFixed(2);
            finalGradeElement.classList.remove('text-gray-400');
            finalGradeElement.classList.add('text-purple-600');

            // Update letter grade
            const letterGrade = getLetterGrade(finalGrade);
            const letterGradeElement = row.querySelector('.letter-grade');
            letterGradeElement.innerHTML = `<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${getGradeColor(letterGrade)}">${letterGrade}</span>`;
        }
    }

    function getLetterGrade(score) {
        if (score >= 95) return 'A+';
        if (score >= 90) return 'A';
        if (score >= 85) return 'A-';
        if (score >= 80) return 'B+';
        if (score >= 75) return 'B';
        if (score >= 70) return 'B-';
        return 'C';
    }

    function getGradeColor(grade) {
        const gradeColors = {
            'A+': 'bg-green-100 text-green-800',
            'A': 'bg-green-100 text-green-800',
            'A-': 'bg-blue-100 text-blue-800',
            'B+': 'bg-blue-100 text-blue-800',
            'B': 'bg-yellow-100 text-yellow-800',
            'B-': 'bg-yellow-100 text-yellow-800',
            'C': 'bg-red-100 text-red-800'
        };
        return gradeColors[grade] || 'bg-gray-100 text-gray-800';
    }

    // Global functions
    window.calculateAllGrades = function() {
        document.querySelectorAll('tbody tr').forEach(row => {
            calculateRowGrade(row);
        });
    };

    window.clearAllGrades = function() {
        if (confirm('Yakin ingin menghapus semua nilai yang sudah diinput?')) {
            document.querySelectorAll('.grade-input').forEach(input => {
                input.value = '';
            });
            document.querySelectorAll('.final-grade').forEach(element => {
                element.textContent = '-';
                element.classList.remove('text-purple-600');
                element.classList.add('text-gray-400');
            });
            document.querySelectorAll('.letter-grade').forEach(element => {
                element.innerHTML = '<span class="text-gray-400">-</span>';
            });
        }
    };

    window.resetForm = function() {
        if (confirm('Yakin ingin mereset form ke kondisi awal?')) {
            document.getElementById('gradesForm').reset();
            calculateAllGrades();
        }
    };

    window.exportGrades = function() {
        const courseId = {{ $selectedCourseId ?? 'null' }};
        const semesterId = {{ $selectedSemesterId ?? 'null' }};
        
        if (courseId && semesterId) {
            window.location.href = `/grades/export?course_id=${courseId}&semester_id=${semesterId}`;
        }
    };

    // Form submission handler
    document.getElementById('gradesForm').addEventListener('submit', function(e) {
        const hasData = Array.from(document.querySelectorAll('.grade-input')).some(input => input.value.trim() !== '');
        
        if (!hasData) {
            e.preventDefault();
            alert('Masukkan setidaknya satu nilai sebelum menyimpan.');
            return false;
        }

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    });
});
</script>
@endsection

<style>
/* Custom scrollbar for horizontal table scroll */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Sticky column styling */
.sticky {
    position: sticky;
    z-index: 10;
}

/* Input focus styles */
.grade-input:focus {
    transform: scale(1.02);
    transition: transform 0.1s ease-in-out;
}
</style>
@endsection
