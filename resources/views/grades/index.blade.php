@extends('layouts.app')

@section('title', 'Kelola Nilai - Sistem Penilaian PKUMI')

@section('page-title', 'Kelola Nilai')

@section('content')
<main class="py-6 px-4 md:px-8">
    <!-- Header Section -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Kelola Nilai</h2>
        <p class="text-base text-gray-500 mt-1">Input dan kelola nilai mahasiswa per mata kuliah berdasarkan semester</p>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <div>
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif
 
    <!-- Error Alert -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
                <div>
                    <strong class="font-bold">Error!</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h5 class="text-lg font-bold text-gray-800">
                <i class="fas fa-filter mr-2"></i>Filter Data Nilai
            </h5>
            <p class="text-sm text-gray-600 mt-1">Pilih kelas terlebih dahulu untuk menampilkan mata kuliah yang relevan</p>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('grades.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Semester Filter -->
                    <div>
                        <label for="semester_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-1 text-green-600"></i>Semester 
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="semester_id" id="semester_id" 
                                class="select2-semester w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                required>
                            <option value="">-- Pilih Semester --</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}" {{ $selectedSemesterId == $semester->id ? 'selected' : '' }}>
                                    {{ $semester->name }}
                                    @if($semester->status === 'active')
                                    (Aktif)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Angkatan (Year) -->
                    <div>
                        <label for="year_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-indigo-600"></i>Angkatan
                        </label>
                        <select name="year_id" id="year_id" 
                                class="select2-year w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">-- Pilih Angkatan --</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}" {{ request('year_id') == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Kelas (Student Class) -->
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-users mr-2 text-indigo-600"></i>Kelas
                        </label>
                        <select name="class_id" id="class_id" 
                                class="select2-class w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                {{ !request('year_id') ? 'disabled' : '' }}>
                            <option value="">-- Pilih Kelas --</option>
                            @if(request('year_id'))
                                @foreach($studentClasses->where('year_id', request('year_id')) as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Course Filter (Dynamic) -->
                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-book mr-1 text-purple-600"></i>Mata Kuliah 
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="course_id" id="course_id" 
                                class="select2-course w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                required
                                {{ !$selectedClassId ? 'disabled' : '' }}>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @if($selectedClassId && isset($courses))
                                @foreach($courses->where('student_class_id', $selectedClassId) as $course)
                                    <option value="{{ $course->id }}" {{ $selectedCourseId == $course->id ? 'selected' : '' }}>
                                        {{ $course->code }} - {{ $course->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <p class="text-xs text-gray-500 mt-1" id="course-hint">
                            @if(!$selectedClassId)
                                Pilih kelas terlebih dahulu
                            @else
                                Ketik untuk mencari mata kuliah
                            @endif
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 items-center">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                            <i class="fas fa-search mr-2"></i>Terapkan
                        </button>
                        <a href="{{ route('grades.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Import Section -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-green-50 flex justify-between items-center">
            <h5 class="text-lg font-bold text-gray-800">
                <i class="fas fa-file-import mr-2 text-green-600"></i>Import Nilai Mahasiswa
            </h5>
        
        </div>

        <div class="p-6">
            @if(session('import_errors'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <strong class="font-bold">Beberapa data gagal diimport:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('grades.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" 
                        class="border border-gray-300 rounded-md px-3 py-2 w-full md:w-2/3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        required>
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md shadow-sm transition duration-200">
                        <i class="fas fa-upload mr-2"></i>Upload & Import
                    </button>
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    File harus berformat <strong>.xlsx</strong> atau <strong>.csv</strong> dan mengikuti template yang disediakan.
                </p>
            </form>
        </div>
    </div>


    <!-- Grades Display Section -->
    @if($selectedCourseId && $selectedSemesterId && $selectedClassId)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Course Info Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h5 class="text-xl font-bold text-gray-800">
                            {{ $courses->find($selectedCourseId)->name ?? 'Unknown Course' }}
                        </h5>
                        <p class="text-sm text-gray-600 mt-1">
                            <i class="fas fa-calendar mr-1"></i>{{ $semesters->find($selectedSemesterId)->name ?? 'Unknown Semester' }} 
                            | <i class="fas fa-users mr-1"></i>{{ $students->count() }} mahasiswa terdaftar
                        </p>
                    </div>
                </div>

                <!-- Grade Weights Info -->
                @if($weights) 
                    <div class="mt-4 p-4 bg-white rounded-lg border border-indigo-200">
                        <h6 class="text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-balance-scale mr-1"></i>Bobot Penilaian
                        </h6>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                            <div class="flex items-center">
                                <span class="font-medium text-gray-600">Kehadiran:</span>
                                <span class="ml-2 text-blue-600 font-bold">{{ $weights->first()->attendance_weight ?? 0 }}%</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-medium text-gray-600">Tugas:</span>
                                <span class="ml-2 text-green-600 font-bold">{{ $weights->first()->assignment_weight ?? 0 }}%</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-medium text-gray-600">UTS:</span>
                                <span class="ml-2 text-yellow-600 font-bold">{{ $weights->first()->midterm_weight ?? 0 }}%</span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-medium text-gray-600">UAS:</span>
                                <span class="ml-2 text-red-600 font-bold">{{ $weights->first()->final_weight ?? 0 }}%</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Grades Input Form -->
            @if($students->isNotEmpty())
                <div class="p-6">
                    <h5 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-edit mr-2"></i>Input Nilai Mahasiswa
                    </h5>
                    <p class="text-sm text-gray-600 mb-4">Masukkan nilai untuk setiap komponen penilaian (0-100). Nilai akhir dan grade akan dihitung otomatis.</p>

                    <form method="POST" action="{{ route('grades.store') }}" id="gradesForm">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $selectedCourseId }}">
                        <input type="hidden" name="semester_id" value="{{ $selectedSemesterId }}">

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">NIM</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Mahasiswa</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-blue-700 uppercase tracking-wider">Kehadiran</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-green-700 uppercase tracking-wider">Tugas</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-yellow-700 uppercase tracking-wider">UTS</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider">UAS</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-indigo-700 uppercase tracking-wider">Nilai Akhir</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-purple-700 uppercase tracking-wider">Grade</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($students as $index => $student)
                                        @php
                                            $grade = $grades->get($student->id);
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition duration-150" data-student-row="{{ $student->id }}">
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $student->nim }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $student->name }}</td>
                                            
                                            <input type="hidden" name="grades[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                            
                                            <!-- Attendance Score -->
                                            <td class="px-4 py-3">
                                                <input type="number" 
                                                    name="grades[{{ $student->id }}][attendance_score]" 
                                                    value="{{ old('grades.'.$student->id.'.attendance_score', $grade->attendance_score ?? '') }}"
                                                    class="grade-input w-20 px-2 py-1 text-center border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    data-student-id="{{ $student->id }}"
                                                    data-field="attendance"
                                                    min="0" max="100" step="0.01"
                                                    placeholder="0">
                                            </td>
                                            
                                            <!-- Assignment Score -->
                                            <td class="px-4 py-3">
                                                <input type="number" 
                                                    name="grades[{{ $student->id }}][assignment_score]" 
                                                    value="{{ old('grades.'.$student->id.'.assignment_score', $grade->assignment_score ?? '') }}"
                                                    class="grade-input w-20 px-2 py-1 text-center border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                    data-student-id="{{ $student->id }}"
                                                    data-field="assignment"
                                                    min="0" max="100" step="0.01"
                                                    placeholder="0">
                                            </td>
                                            
                                            <!-- Midterm Score -->
                                            <td class="px-4 py-3">
                                                <input type="number" 
                                                    name="grades[{{ $student->id }}][midterm_score]" 
                                                    value="{{ old('grades.'.$student->id.'.midterm_score', $grade->midterm_score ?? '') }}"
                                                    class="grade-input w-20 px-2 py-1 text-center border border-gray-300 rounded focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                                    data-student-id="{{ $student->id }}"
                                                    data-field="midterm"
                                                    min="0" max="100" step="0.01"
                                                    placeholder="0">
                                            </td>
                                            
                                            <!-- Final Score -->
                                            <td class="px-4 py-3">
                                                <input type="number" 
                                                    name="grades[{{ $student->id }}][final_score]" 
                                                    value="{{ old('grades.'.$student->id.'.final_score', $grade->final_score ?? '') }}"
                                                    class="grade-input w-20 px-2 py-1 text-center border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                                    data-student-id="{{ $student->id }}"
                                                    data-field="final"
                                                    min="0" max="100" step="0.01"
                                                    placeholder="0">
                                            </td>
                                            
                                            <!-- Display Final Grade (Auto-calculated) -->
                                            <td class="px-4 py-3 text-center">
                                                <span class="text-sm font-bold text-indigo-600" id="final-grade-{{ $student->id }}">
                                                    {{ $grade->final_grade ?? '-' }}
                                                </span>
                                            </td>
                                            
                                            <!-- Display Letter Grade (Auto-calculated) -->
                                            <td class="px-4 py-3 text-center">
                                                <span id="letter-grade-{{ $student->id }}">
                                                    @if(isset($grade->letter_grade))
                                                        <span class="px-3 py-1 rounded-full text-xs font-bold grade-badge
                                                            @if($grade->letter_grade == 'A') bg-green-100 text-green-800
                                                            @elseif($grade->letter_grade == 'B') bg-blue-100 text-blue-800
                                                            @elseif($grade->letter_grade == 'C') bg-yellow-100 text-yellow-800
                                                            @elseif($grade->letter_grade == 'D') bg-orange-100 text-orange-800
                                                            @else bg-red-100 text-red-800
                                                            @endif">
                                                            {{ $grade->letter_grade }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-between items-center">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Total: {{ $students->count() }} mahasiswa
                            </p>
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition duration-200 shadow-md">
                                <i class="fas fa-save mr-2"></i>Simpan Semua Nilai
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <!-- No Students Enrolled -->
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Ada Mahasiswa Terdaftar</h3>
                    <p class="text-gray-600 mb-4">Belum ada mahasiswa yang terdaftar pada mata kuliah ini di semester yang dipilih.</p>
                    <a href="{{ route('enrollments.create') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Tambah Enrollment
                    </a>
                </div>
            @endif
        </div>
    @else
        <!-- Initial State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100 mb-4">
                <i class="fas fa-clipboard-list text-blue-600 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Pilih Kelas, Semester dan Mata Kuliah</h3>
            <p class="text-gray-600 max-w-md mx-auto">Pilih kelas, semester dan mata kuliah di atas untuk mulai mengelola nilai mahasiswa.</p>
        </div>
    @endif
</main>
@endsection

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Custom Select2 Styling - Match Tailwind Input Height */
    .select2-container--default .select2-selection--single {
        height: 38px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
        padding-left: 12px !important;
        padding-right: 40px !important;
        color: #374151 !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #9ca3af !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px !important;
        right: 8px !important;
        top: 0 !important;
    }
    
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        outline: none !important;
    }
    
    .select2-container--default.select2-container--disabled .select2-selection--single {
        background-color: #f3f4f6 !important;
        cursor: not-allowed !important;
    }
    
    /* Dropdown styling */
    .select2-dropdown {
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        margin-top: 4px !important;
    }
    
    .select2-search--dropdown {
        padding: 8px !important;
    }
    
    .select2-search__field {
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        padding: 8px 12px !important;
        font-size: 14px !important;
    }
    
    .select2-search__field:focus {
        border-color: #3b82f6 !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }
    
    .select2-results__option {
        padding: 8px 12px !important;
        font-size: 14px !important;
    }
    
    .select2-results__option--highlighted {
        background-color: #3b82f6 !important;
        color: white !important;
    }
    
    .select2-results__option--selected {
        background-color: #eff6ff !important;
        color: #1e40af !important;
    }
    
    /* Fix alignment untuk container Select2 */
    .select2-container {
        width: 100% !important;
    }
    
    /* Animation for grade calculation */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.8;
            transform: scale(1.05);
        }
    }
    
    .animate-pulse {
        animation: pulse 1s cubic-bezier(0.4, 0, 0.6, 1);
    }
    
    /* Highlight input when focused */
    .grade-input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        border-color: #3b82f6;
    }
    
    /* Grade badge styling */
    .grade-badge {
        display: inline-block;
        transition: all 0.3s ease;
    }
    
    /* Input number spinner removal for cleaner look */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@endpush

@section('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    console.log('Grade Management System - Initialized');
    
    // Grade weights dari backend
    const gradeWeights = {
        attendance: {{ $weights->first()->attendance_weight ?? 10 }},
        assignment: {{ $weights->first()->assignment_weight ?? 20 }},
        midterm: {{ $weights->first()->midterm_weight ?? 30 }},
        final: {{ $weights->first()->final_weight ?? 40 }}
    };

    console.log('Grade Weights Configuration:', gradeWeights);

    // ========== GRADE CALCULATION FUNCTIONS ==========
    
    /**
     * Calculate final grade based on component scores and weights
     */
    function calculateFinalGrade(attendance, assignment, midterm, finalScore) {
        const finalGrade = (
            (parseFloat(attendance || 0) * gradeWeights.attendance / 100) +
            (parseFloat(assignment || 0) * gradeWeights.assignment / 100) +
            (parseFloat(midterm || 0) * gradeWeights.midterm / 100) +
            (parseFloat(finalScore || 0) * gradeWeights.final / 100)
        );
        return finalGrade.toFixed(2);
    }

    /**
     * Convert numeric grade to letter grade
     */
    function getLetterGrade(finalGrade) {
        const grade = parseFloat(finalGrade);
        if (grade >= 85) return 'A';
        if (grade >= 70) return 'B';
        if (grade >= 60) return 'C';
        if (grade >= 50) return 'D';
        return 'E';
    }

    /**
     * Get badge color class based on letter grade
     */
    function getBadgeClass(letterGrade) {
        const badgeClasses = {
            'A': 'bg-green-100 text-green-800',
            'B': 'bg-blue-100 text-blue-800',
            'C': 'bg-yellow-100 text-yellow-800',
            'D': 'bg-orange-100 text-orange-800',
            'E': 'bg-red-100 text-red-800'
        };
        return badgeClasses[letterGrade] || 'bg-gray-100 text-gray-800';
    }

    /**
     * Update grade display for a specific student
     */
    function updateGradeDisplay(studentId) {
        const row = $(`tr[data-student-row="${studentId}"]`);
        
        // Get all score inputs for this student
        const scores = {
            attendance: row.find('input[data-field="attendance"]').val(),
            assignment: row.find('input[data-field="assignment"]').val(),
            midterm: row.find('input[data-field="midterm"]').val(),
            final: row.find('input[data-field="final"]').val()
        };

        // Check if at least one score is filled
        const hasAnyScore = Object.values(scores).some(val => val && val !== '');

        if (hasAnyScore) {
            // Calculate final grade
            const finalGrade = calculateFinalGrade(
                scores.attendance, 
                scores.assignment, 
                scores.midterm, 
                scores.final
            );
            const letterGrade = getLetterGrade(finalGrade);
            const badgeClass = getBadgeClass(letterGrade);

            // Update final grade display
            const $finalGrade = $(`#final-grade-${studentId}`);
            $finalGrade.text(finalGrade).addClass('text-indigo-600 font-bold');

            // Update letter grade display with badge
            $(`#letter-grade-${studentId}`).html(
                `<span class="px-3 py-1 rounded-full text-xs font-bold ${badgeClass}">${letterGrade}</span>`
            );

            // Add animation effect
            $finalGrade.addClass('animate-pulse');
            setTimeout(() => {
                $finalGrade.removeClass('animate-pulse');
            }, 1000);
        }
    }

    // ========== SELECT2 INITIALIZATION ==========
    
    /**
     * Initialize Select2 for Semester dropdown
     */
    $('.select2-semester').select2({
        placeholder: '-- Pilih Semester --',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return "Semester tidak ditemukan"; },
            searching: function() { return "Mencari..."; }
        }
    });

    /**
     * Initialize Select2 for Year dropdown
     */
    $('.select2-year').select2({
        placeholder: '-- Pilih Angkatan --',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return "Angkatan tidak ditemukan"; },
            searching: function() { return "Mencari..."; }
        }
    });

    /**
     * Initialize Select2 for Class dropdown
     */
    $('.select2-class').select2({
        placeholder: '-- Pilih Kelas --',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return "Kelas tidak ditemukan"; },
            searching: function() { return "Mencari..."; }
        }
    });

    /**
     * Initialize Course Select2 with AJAX
     */
    function initCourseSelect2() {
        const classId = $('#class_id').val();
        const semesterId = $('#semester_id').val(); // TAMBAHAN: Ambil semester
        
        $('#course_id').select2({
            placeholder: '-- Pilih Mata Kuliah --',
            allowClear: true,
            width: '100%',
            minimumInputLength: 0,
            language: {
                noResults: function() { return "Mata kuliah tidak ditemukan"; },
                searching: function() { return "Mencari..."; }
            },
            ajax: {
                url: '{{ route("grades.courses-by-class") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term || '',
                        class_id: classId,
                        semester_id: semesterId // TAMBAHAN: Kirim semester
                    };
                },
                processResults: function (data) {
                    console.log('Select2 processResults - Raw data:', data);
                    
                    // PERBAIKAN: Akses data.courses, bukan data langsung
                    let courses = [];
                    
                    if (data && data.courses && Array.isArray(data.courses)) {
                        courses = data.courses;
                    } else if (Array.isArray(data)) {
                        courses = data;
                    } else {
                        console.error('Invalid data format:', data);
                        return { results: [] };
                    }
                    
                    console.log('Courses to process:', courses);
                    
                    if (courses.length === 0) {
                        return { results: [] };
                    }
                    
                    // Map courses ke format Select2
                    const results = courses.map(function(item) {
                        console.log('Processing item:', item);
                        
                        // PERBAIKAN: Validasi setiap property
                        const id = item.id;
                        const name = item.name || 'Unknown Course';
                        const code = item.code || 'N/A';
                        
                        if (!id) {
                            console.warn('Item without ID:', item);
                            return null;
                        }
                        
                        return {
                            id: id,
                            text: code + ' - ' + name
                        };
                    }).filter(item => item !== null); // Hapus item null
                    
                    console.log('Select2 results:', results);
                    
                    return { results: results };
                },
                error: function(xhr, status, error) {
                    console.error('Select2 AJAX Error:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                },
                cache: true
            }
        });
    }


    // Event handler when class changes
    $('#class_id').on('change', function() {
        const classId = $(this).val();
        const $courseSelect = $('#course_id');
        const $courseHint = $('#course-hint');
        
        // Reset course dropdown
        $courseSelect.val(null).trigger('change');
        
        if (!classId) {
            $courseSelect.prop('disabled', true);
            if ($courseHint.length) $courseHint.text('Pilih kelas terlebih dahulu');
            
            if ($courseSelect.hasClass("select2-hidden-accessible")) {
                $courseSelect.select2('destroy');
            }
            
            $courseSelect.select2({
                placeholder: '-- Pilih Mata Kuliah --',
                disabled: true,
                width: '100%'
            });
        } else {
            $courseSelect.prop('disabled', false);
            if ($courseHint.length) $courseHint.text('Ketik untuk mencari mata kuliah');
            
            if ($courseSelect.hasClass("select2-hidden-accessible")) {
                $courseSelect.select2('destroy');
            }
            
            initCourseSelect2();
        }
    });

    // TAMBAHAN: Event handler when semester changes
    $('#semester_id').on('change', function() {
        const classId = $('#class_id').val();
        const $courseSelect = $('#course_id');
        
        // Reset course dropdown
        $courseSelect.val(null).trigger('change');
        
        // Re-init Select2 jika kelas sudah dipilih
        if (classId) {
            if ($courseSelect.hasClass("select2-hidden-accessible")) {
                $courseSelect.select2('destroy');
            }
            initCourseSelect2();
        }
    });

    /**
     * Form validation for filter form
     */
    $('#filterForm').on('submit', function(e) {
        const classId = $('#class_id').val();
        const semesterId = $('#semester_id').val();
        const courseId = $('#course_id').val();
        
        if (!classId || !semesterId || !courseId) {
            e.preventDefault();
            alert('Mohon lengkapi semua filter (Kelas, Semester, dan Mata Kuliah)');
            return false;
        }
    });

    /**
     * Confirmation before submitting grades
     */
    $('#gradesForm').on('submit', function(e) {
        if (!confirm('Apakah Anda yakin ingin menyimpan semua nilai?')) {
            e.preventDefault();
            return false;
        }
    });

    // ========== INITIALIZATION ==========
    
    // Initialize display for existing grades on page load
    @foreach($students as $student)
        updateGradeDisplay({{ $student->id }});
    @endforeach

    // Initial state check for course dropdown
    @if($selectedClassId)
        initCourseSelect2();
    @else
        $('#course_id').prop('disabled', true);
        $('#course_id').select2({
            placeholder: '-- Pilih Mata Kuliah --',
            disabled: true,
            width: '100%'
        });
    @endif

    console.log('Grade Management System - Ready');
});
</script>

<script>
$(document).ready(function() {
    console.log('=== Grade Index Script Initialized ===');
    
    // Function untuk load courses dengan validasi lengkap
    function loadCourses() {
        const classId = $('#class_id').val();
        const semesterId = $('#semester_id').val();
        const $courseSelect = $('#course_id');
        
        console.log('Loading courses for:', { classId, semesterId });
        
        // Reset dropdown course
        $courseSelect.empty().append('<option value="">Pilih Mata Kuliah</option>');
        
        // Jika kelas belum dipilih, disable course dropdown
        if (!classId) {
            $courseSelect.prop('disabled', true);
            console.log('Class ID not selected, course dropdown disabled');
            return;
        }
        
        // Enable dan show loading
        $courseSelect.prop('disabled', false);
        $courseSelect.append('<option value="">Memuat mata kuliah...</option>');
        
        // AJAX call dengan parameter semester
        $.ajax({
            url: '{{ route("grades.courses-by-class") }}',
            type: 'GET',
            data: { 
                class_id: classId,
                semester_id: semesterId
            },
            dataType: 'json', // TAMBAHAN: Ensure response is parsed as JSON
            success: function(response) {
                console.log('=== AJAX Success ===');
                console.log('Response:', response);
                console.log('Response type:', typeof response);
                
                $courseSelect.empty().append('<option value="">Pilih Mata Kuliah</option>');
                
                // PERBAIKAN: Validasi response structure dengan benar
                let courses = [];
                
                // Handle different response formats
                if (response && response.courses) {
                    courses = response.courses;
                    console.log('Courses from response.courses:', courses.length);
                } else if (Array.isArray(response)) {
                    courses = response;
                    console.log('Courses from direct array:', courses.length);
                } else {
                    console.error('Unknown response format:', response);
                }
                
                if (courses.length > 0) {
                    $.each(courses, function(index, course) {
                        // PERBAIKAN: Validasi setiap property course
                        const courseName = course.name || 'Unknown Course';
                        const courseCode = course.code || 'N/A';
                        const courseId = course.id;
                        
                        if (!courseId) {
                            console.warn('Course without ID:', course);
                            return; // Skip course tanpa ID
                        }
                        
                        const optionText = `${courseName} (${courseCode})`;
                        console.log(`Adding course: ${optionText}`);
                        
                        $courseSelect.append(new Option(optionText, courseId, false, false));
                    });
                    
                    // Restore selected value jika ada
                    const selectedCourseId = '{{ $selectedCourseId ?? "" }}';
                    if (selectedCourseId) {
                        $courseSelect.val(selectedCourseId).trigger('change');
                        console.log('Restored selected course:', selectedCourseId);
                    }
                    
                    console.log(`Total ${courses.length} courses loaded successfully`);
                } else {
                    $courseSelect.append('<option value="">Tidak ada mata kuliah tersedia</option>');
                    console.warn('No courses available for this filter');
                }
            },
            error: function(xhr, status, error) {
                console.error('=== AJAX Error ===');
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response:', xhr.responseText);
                console.error('Status Code:', xhr.status);
                
                $courseSelect.empty().append('<option value="">Error memuat data</option>');
                
                let errorMessage = 'Gagal memuat mata kuliah.';
                if (xhr.status === 404) {
                    errorMessage += ' Route tidak ditemukan.';
                } else if (xhr.status === 500) {
                    errorMessage += ' Server error.';
                } else if (xhr.status === 0) {
                    errorMessage += ' Tidak ada koneksi ke server.';
                }
                
                alert(errorMessage + ' Silakan refresh halaman atau hubungi admin.');
            }
        });
    }
    
    // Trigger load courses saat kelas berubah
    $('#class_id').on('change', function() {
        console.log('Class changed event triggered');
        loadCourses();
    });
    
    // Trigger load courses saat semester berubah
    $('#semester_id').on('change', function() {
        console.log('Semester changed event triggered');
        const classId = $('#class_id').val();
        if (classId) {
            loadCourses();
        } else {
            console.log('Class not selected, skipping course load');
        }
    });
    
    // Load courses saat page load jika kelas sudah dipilih
    @if($selectedClassId)
        console.log('Initial load: Class already selected');
        loadCourses();
    @else
        console.log('Initial load: No class selected');
    @endif
    
    // Handle year change untuk filter kelas
    $('#year_id').on('change', function() {
        const yearId = $(this).val();
        const $classSelect = $('#class_id');
        
        console.log('Year changed:', yearId);
        
        if (!yearId) {
            $classSelect.prop('disabled', false);
            return;
        }
        
        $classSelect.prop('disabled', true).empty().append('<option value="">Memuat...</option>');
        
        $.ajax({
            url: '{{ route("grades.classes-by-year") }}',
            type: 'GET',
            data: { year_id: yearId },
            dataType: 'json',
            success: function(response) {
                console.log('Classes loaded:', response);
                
                $classSelect.prop('disabled', false).empty().append('<option value="">Semua Kelas</option>');
                
                $.each(response, function(index, studentClass) {
                    $classSelect.append(new Option(studentClass.name, studentClass.id, false, false));
                });
                
                const selectedClassId = '{{ $selectedClassId ?? "" }}';
                if (selectedClassId) {
                    $classSelect.val(selectedClassId).trigger('change');
                }
            },
            error: function(xhr) {
                console.error('Error loading classes:', xhr);
                $classSelect.prop('disabled', false).empty().append('<option value="">Error</option>');
            }
        });
    });
    
    // Auto submit form saat course dipilih
    // $('#course_id').on('change', function() {
    //     const courseId = $(this).val();
    //     const semesterId = $('#semester_id').val();
        
    //     console.log('Course selected:', { courseId, semesterId });
        
    //     if (courseId && semesterId) {
    //         console.log('Auto-submitting form...');
    //         $(this).closest('form').submit();
    //     }
    // });
    
    console.log('=== Script Initialization Complete ===');
});
</script>
@endsection
