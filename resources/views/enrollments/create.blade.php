@extends('layouts.app')

@section('title', 'Tambah Enrollment - Sistem Penilaian PKUMI')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header with Breadcrumb -->
    <div class="mb-6">
        <nav class="text-sm mb-4">
            <ol class="flex items-center space-x-2 text-gray-600">
                <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('enrollments.index') }}" class="hover:text-blue-600 transition">Enrollments</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900 font-medium">Tambah Baru</li>
            </ol>
        </nav>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tambah Enrollment</h1>
                <p class="text-gray-600 mt-2">Pilih metode pendaftaran yang sesuai dengan kebutuhan</p>
            </div>
            <a href="{{ route('enrollments.index') }}" 
               class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all shadow-sm hover:shadow">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4 shadow-sm animate-fade-in">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-red-800 font-semibold text-sm">Terjadi kesalahan!</h3>
                <div class="mt-2 text-red-700 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-sm animate-fade-in">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Main Card Container -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <nav class="flex -mb-px">
                <button type="button" 
                        onclick="switchTab('single')"
                        id="tab-single"
                        class="tab-button group relative min-w-0 flex-1 overflow-hidden py-5 px-6 text-center font-semibold text-gray-700 hover:text-blue-600 focus:z-10 transition-all duration-200 border-b-3 border-transparent">
                    <div class="flex items-center justify-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-base">Single Enrollment</span>
                    </div>
                    <span class="text-xs text-gray-500 block mt-1">Daftarkan satu kelas</span>
                </button>
                
                <button type="button" 
                        onclick="switchTab('bulk')"
                        id="tab-bulk"
                        class="tab-button group relative min-w-0 flex-1 overflow-hidden py-5 px-6 text-center font-semibold text-gray-700 hover:text-green-600 focus:z-10 transition-all duration-200 border-b-3 border-transparent">
                    <div class="flex items-center justify-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="text-base">Bulk Enrollment</span>
                    </div>
                    <span class="text-xs text-gray-500 block mt-1">Daftarkan multiple mata kuliah</span>
                </button>
            </nav>
        </div>

        <!-- Tab Content Container -->
        <div class="p-8">
            
            <!-- SINGLE ENROLLMENT FORM -->
            <div id="content-single" class="tab-content">
                <div class="max-w-3xl mx-auto">
                    <!-- Info Banner -->
                    <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 rounded-r-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h3 class="text-sm font-semibold text-blue-900">Single Enrollment Mode</h3>
                                <p class="text-sm text-blue-800 mt-1">Gunakan form ini untuk mendaftarkan satu kelas ke satu mata kuliah tertentu.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('enrollments.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Semester -->
                            <div>
                                <label for="semester_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Semester <span class="text-red-500">*</span>
                                </label>
                                <select name="semester_id" id="semester_id" 
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                                        required>
                                    <option value="">-- Pilih Semester --</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
                                            {{ $semester->name }} ({{ $semester->period->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status" 
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                                        required>
                                    <option value="enrolled" {{ old('status', 'enrolled') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="dropped" {{ old('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filter Angkatan (Year) -->
                        <div class="mb-4">
                            <label for="year_filter_single" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-filter text-gray-500 mr-1"></i>Angkatan
                            </label>
                            <select id="year_id" name="year_id" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                <option value="">-- Semua Angkatan --</option>
                                @foreach($years ?? [] as $year)
                                    <option value="{{ $year->id }}" {{ ($selectedYear ?? '') == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>Pilih angkatan untuk memfilter kelas yang tersedia
                            </p>
                        </div>

                        <!-- Filter Kelas -->
                        <div class="mb-4">
                            <label for="student_class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kelas <span class="text-red-500">*</span>
                            </label>
                            <select id="student_class_id" name="student_class_id" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" disabled>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($studentClasses as $class)
                                    <option value="{{ $class->id }}" data-year-id="{{ $class->year_id ?? '' }}" 
                                        {{ ($selectedClass ?? '') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_class_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Course -->
                        <div>
                            <label for="course_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Mata Kuliah <span class="text-red-500">*</span>
                            </label>
                            <select name="course_id" id="course_id" 
                                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                                    required disabled>
                                <option value="">Pilih kelas terlebih dahulu...</option>
                            </select>
                            <div class="mt-2 flex items-start">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-xs text-gray-500">Pilih kelas terlebih dahulu untuk memuat daftar mata kuliah</p>
                            </div>
                        </div>

                        <!-- Enrollment Date -->
                        <div>
                            <label for="enrollment_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Enrollment
                            </label>
                            <input type="date" name="enrollment_date" id="enrollment_date" 
                                   value="{{ old('enrollment_date', date('Y-m-d')) }}" 
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <p class="text-xs text-gray-500 mt-1.5">Default: tanggal hari ini</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('enrollments.index') }}" 
                               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Enrollment
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- BULK ENROLLMENT FORM -->
            <div id="content-bulk" class="tab-content hidden">
                <div class="max-w-4xl mx-auto">
                    <!-- Info Banner -->
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-r-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h3 class="text-sm font-semibold text-green-900">Bulk Enrollment Mode</h3>
                                <p class="text-sm text-green-800 mt-1">Pilih multiple mata kuliah sekaligus untuk didaftarkan ke satu kelas.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('enrollments.bulk') }}" method="POST" id="bulkEnrollmentForm" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Semester -->
                            <div>
                                <label for="bulk_semester_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Semester <span class="text-red-500">*</span>
                                </label>
                                <select name="semester_id" id="bulk_semester_id" 
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all" 
                                        required>
                                    <option value="">-- Pilih Semester --</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester->id }}">
                                            {{ $semester->name }} ({{ $semester->period->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Angkatan (Year) -->
                        <div class="mb-4">
                            <label for="year_filter_single" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-filter text-gray-500 mr-1"></i>Angkatan
                            </label>
                            <select id="bulk_year_id" name="bulk_year_id" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                <option value="">-- Pilih Angkatan --</option>
                                @foreach($years ?? [] as $year)
                                    <option value="{{ $year->id }}" {{ ($selectedYear ?? '') == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>Pilih angkatan untuk memfilter kelas yang tersedia
                            </p>
                        </div>

                            <!-- Student Class -->
                            <div>
                                <label for="bulk_student_class_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kelas <span class="text-red-500">*</span>
                                </label>
                                <select name="student_class_id" id="bulk_student_class_id" 
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all" 
                                        required onchange="handleBulkClassChange(this.value)" disabled>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($studentClasses as $class)
                                        <option value="{{ $class->id }}">
                                            {{ $class->name }} {{ $class->year->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Courses Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Pilih Mata Kuliah <span class="text-red-500">*</span>
                            </label>
                            
                            <!-- Courses Container -->
                            <div id="bulk_courses_container" 
                                class="border-2 border-dashed border-gray-300 rounded-xl p-6 bg-gray-50 min-h-[320px] max-h-[450px] overflow-y-auto">
                                <div class="flex items-center justify-center h-full text-gray-400">
                                    <div class="text-center">
                                        <svg class="w-20 h-20 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <p class="text-base font-medium text-gray-600">Pilih kelas terlebih dahulu</p>
                                        <p class="text-sm text-gray-500 mt-2">Daftar mata kuliah akan muncul di sini</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Counter Badge -->
                            <div class="mt-4 flex items-center justify-between bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200 shadow-sm">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <span class="ml-3 text-sm font-semibold text-gray-700">Mata kuliah terpilih</span>
                                </div>
                                <span id="badge_count" 
                                    class="inline-flex items-center justify-center min-w-[48px] h-10 px-4 bg-gradient-to-r from-green-600 to-green-600 text-white text-lg font-bold rounded-full shadow-md">0</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('enrollments.index') }}" 
                               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                                Batal
                            </a>
                            <button type="submit" 
                                    id="bulkSubmitBtn"
                                    disabled
                                    class="w-full px-6 py-3 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed transition-all duration-200">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Daftarkan <span id="button_count">0</span> Mata Kuliah
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.tab-button.active {
    color: #1d4ed8;
    border-bottom-color: #1d4ed8;
    background: linear-gradient(to bottom, rgba(59, 130, 246, 0.05), transparent);
}
.tab-button:hover {
    background: rgba(59, 130, 246, 0.03);
}
#tab-bulk.active {
    color: #059669;
    border-bottom-color: #059669;
    background: linear-gradient(to bottom, rgba(5, 150, 105, 0.05), transparent);
}
#tab-bulk:hover {
    background: rgba(5, 150, 105, 0.03);
}
.animate-fade-in {
    animation: fadeIn 0.3s ease-in;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
console.log('=== ENROLLMENT CREATE PAGE LOADED ===');

const API_URL = '{{ url("/enrollments/courses-by-class") }}';

// Initialize Select2 on page load
$(document).ready(function() {
    console.log('Initializing Select2...');
    
    // Initialize Select2 for Single Enrollment
    $('#semester_id').select2({
        placeholder: '-- Pilih Semester --',
        allowClear: true,
        width: '100%',
        theme: 'default'
    });

    $('#year_id').select2({
        placeholder: '-- Pilih Angkatan --',
        allowClear: true,
        width: '100%',
        theme: 'default'
    });
    
    $('#student_class_id').select2({
        placeholder: '-- Pilih Kelas --',
        allowClear: true,
        width: '100%',
        theme: 'default'
    });
    
    $('#course_id').select2({
        placeholder: 'Pilih kelas terlebih dahulu...',
        allowClear: true,
        width: '100%',
        theme: 'default'
    });
    
    $('#status').select2({
        placeholder: '-- Pilih Status --',
        allowClear: false,
        width: '100%',
        minimumResultsForSearch: -1, // Hide search for status
        theme: 'default'
    });
    
    // Initialize Select2 for Bulk Enrollment
    $('#bulk_semester_id').select2({
        placeholder: '-- Pilih Semester --',
        allowClear: true,
        width: '100%',
        containerCssClass: 'select2-green',
        theme: 'default'
    });

    $('#bulk_year_id').select2({
        placeholder: '-- Pilih Angkatan --',
        allowClear: true,
        width: '100%',
        containerCssClass: 'select2-green',
        theme: 'default'
    });
    
    $('#bulk_student_class_id').select2({
        placeholder: '-- Pilih Kelas --',
        allowClear: true,
        width: '100%',
        containerCssClass: 'select2-green',
        theme: 'default'
    });
    
    // Trigger change event when Select2 changes
    $('#student_class_id').on('select2:select', function(e) {
        const studentClassId = $(this).val();
        handleStudentClassChange(studentClassId);
    });
    
    $('#student_class_id').on('select2:clear', function(e) {
        handleStudentClassChange('');
    });
    
    $('#bulk_student_class_id').on('select2:select', function(e) {
        const studentClassId = $(this).val();
        handleBulkClassChange(studentClassId);
    });
    
    $('#bulk_student_class_id').on('select2:clear', function(e) {
        handleBulkClassChange('');
    });
    
    console.log('Select2 initialized successfully');
});

// Tab switching function
function switchTab(tab) {
    console.log('Switching to tab:', tab);
    
    // Update tab buttons
    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    
    // Update content visibility
    document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
    document.getElementById('content-' + tab).classList.remove('hidden');
}

// Initialize with single tab active
document.addEventListener('DOMContentLoaded', function() {
    switchTab('bulk');
});

// Single enrollment handler
function handleBulkClassChange(studentClassId) {
    console.log('Bulk enrollment - Class changed:', studentClassId);
    const container = document.getElementById('bulk_courses_container');
    
    // Reset search box
    resetCourseSearch();
    
    if (!studentClassId) {
        container.innerHTML = `
            <div class="flex items-center justify-center h-full text-gray-400">
                <div class="text-center">
                    <svg class="w-20 h-20 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="text-base font-medium text-gray-600">Pilih kelas terlebih dahulu</p>
                    <p class="text-sm text-gray-500 mt-2">Daftar mata kuliah akan muncul di sini</p>
                </div>
            </div>
        `;
        updateBulkCount();
        return;
    }
    
    container.innerHTML = '<div class="text-center py-16"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto"></div><p class="text-gray-600 mt-4 font-medium">Memuat mata kuliah...</p></div>';
    
    fetch(`${API_URL}?class_id=${studentClassId}`, {  // PERBAIKAN: Ganti student_class_id menjadi class_id
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);  // Debug response
        
        // Clear Select2
        $('#course_id').empty();
        $('#course_id').append(new Option('-- Pilih Mata Kuliah --', '', false, false));
        
        // PERBAIKAN: Validasi response dengan benar
        if (data && data.courses && Array.isArray(data.courses) && data.courses.length > 0) {
            console.log('Courses loaded:', data.courses.length);
            
            data.courses.forEach(course => {
                const optionText = `${course.name} (${course.code})${course.sks ? ' - ' + course.sks + ' SKS' : ''}`;
                $('#course_id').append(new Option(optionText, course.id, false, false));
            });
            $('#course_id').prop('disabled', false);
        } else {
            console.warn('No courses found for this class');
            $('#course_id').append(new Option('Tidak ada mata kuliah untuk kelas ini', '', false, false));
            $('#course_id').prop('disabled', true);
        }
        
        $('#course_id').trigger('change');
    })
    .catch(error => {
        console.error('Error loading courses:', error);
        $('#course_id').empty();
        $('#course_id').append(new Option('Error memuat data', '', false, false));
        $('#course_id').prop('disabled', true);
        $('#course_id').trigger('change');
        
        alert('Gagal memuat mata kuliah. Silakan coba lagi atau refresh halaman.\nError: ' + error.message);
    });
}

// Bulk enrollment handler
function handleBulkClassChange(studentClassId) {
    console.log('Bulk enrollment - Class changed:', studentClassId);
    const container = document.getElementById('bulk_courses_container');
    
    if (!studentClassId) {
        container.innerHTML = `
            <div class="flex items-center justify-center h-full text-gray-400">
                <div class="text-center">
                    <svg class="w-20 h-20 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="text-base font-medium text-gray-600">Pilih kelas terlebih dahulu</p>
                    <p class="text-sm text-gray-500 mt-2">Daftar mata kuliah akan muncul di sini</p>
                </div>
            </div>
        `;
        updateBulkCount();
        return;
    }
    
    container.innerHTML = '<div class="text-center py-16"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto"></div><p class="text-gray-600 mt-4 font-medium">Memuat mata kuliah...</p></div>';
    
    fetch(`${API_URL}?class_id=${studentClassId}`, {  // PERBAIKAN: Ganti student_class_id menjadi class_id
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Bulk response data:', data);  // Debug response
        
        // PERBAIKAN: Validasi response dengan benar
        if (data && data.courses && Array.isArray(data.courses)) {
            console.log('Bulk courses loaded:', data.courses.length);
            renderBulkCourses(data.courses);
        } else {
            // Jika tidak ada courses, tampilkan pesan kosong
            container.innerHTML = `
                <div class="flex items-center justify-center h-full text-gray-400">
                    <div class="text-center">
                        <svg class="w-20 h-20 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <p class="text-base font-medium text-gray-600">Tidak ada mata kuliah</p>
                        <p class="text-sm text-gray-500 mt-2">Kelas ini belum memiliki mata kuliah terhubung</p>
                    </div>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading bulk courses:', error);
        container.innerHTML = `
            <div class="text-center py-16 text-red-600">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-semibold text-lg">Gagal memuat data</p>
                <p class="text-sm mt-2">${error.message}</p>
                <button onclick="handleBulkClassChange(${studentClassId})" 
                        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Coba Lagi
                </button>
            </div>
        `;
    });
}

// Toggle all courses checkbox
function toggleAllCourses(checkbox) {
    console.log('Toggle all called, checked:', checkbox.checked);
    
    const courseCheckboxes = document.querySelectorAll('.bulk-course-checkbox:not(:disabled)');
    
    if (courseCheckboxes.length === 0) {
        console.warn('⚠ No course checkboxes found');
        return;
    }
    
    courseCheckboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    
    console.log(`✓ Toggled ${courseCheckboxes.length} checkboxes to: ${checkbox.checked}`);
    
    // Update dengan delay
    setTimeout(() => updateBulkCount(), 50);
}

// Update bulk enrollment counter dan button state
function updateBulkCount() {
    requestAnimationFrame(() => {
        const checkedBoxes = document.querySelectorAll('.bulk-course-checkbox:checked');
        const totalBoxes = document.querySelectorAll('.bulk-course-checkbox');
        const count = checkedBoxes.length;
        
        console.log('=== UPDATE BULK COUNT ===');
        console.log('Checked boxes:', count);
        console.log('Total boxes:', totalBoxes.length);
        
        // 1. Update counter di header courses (yang di dalam container)
        const headerCounter = document.getElementById('bulk_selected_count');
        if (headerCounter) {
            headerCounter.textContent = count;
            console.log('✓ Header counter updated to:', count);
        }
        
        // 2. Update badge counter (hijau di bawah)
        const badgeCounter = document.getElementById('badge_count');
        if (badgeCounter) {
            badgeCounter.textContent = count;
            badgeCounter.style.transform = count > 0 ? 'scale(1.1)' : 'scale(1)';
            console.log('✓ Badge counter updated to:', count);
        } else {
            console.error('✗ Badge counter NOT FOUND!');
        }
        
        // 3. Update button counter
        const buttonCounter = document.getElementById('button_count');
        if (buttonCounter) {
            buttonCounter.textContent = count;
            console.log('✓ Button counter updated to:', count);
        } else {
            console.error('✗ Button counter NOT FOUND!');
        }
        
        // Update submit button state
        const submitBtn = document.getElementById('bulkSubmitBtn');
        if (submitBtn) {
            submitBtn.disabled = count === 0;
            
            if (count === 0) {
                submitBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            } else {
                submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                submitBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            }
            
            console.log('✓ Button state updated');
        }
        
        // Update "Select All" checkbox state
        const selectAllCheckbox = document.getElementById('selectAllCourses');
        if (selectAllCheckbox && totalBoxes.length > 0) {
            const allChecked = count === totalBoxes.length;
            const someChecked = count > 0 && count < totalBoxes.length;
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked;
            
            console.log('✓ Select all checkbox updated');
        }
        
        console.log('=== END UPDATE ===\n');
    });
}

// Helper function untuk reset bulk selection
function resetBulkSelection() {
    console.log('Reset bulk selection called');
    
    document.querySelectorAll('.bulk-course-checkbox').forEach(cb => {
        cb.checked = false;
    });
    
    const selectAllCheckbox = document.getElementById('selectAllCourses');
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    }
    
    setTimeout(() => updateBulkCount(), 50);
}

// Enhanced renderBulkCourses dengan checkbox yang benar
function renderBulkCourses(courses) {
    const container = document.getElementById('bulk_courses_container');
    
    if (!courses || courses.length === 0) {
        container.innerHTML = `
            <div class="flex items-center justify-center h-full text-gray-400">
                <div class="text-center py-12">
                    <svg class="w-20 h-20 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="text-base font-medium text-gray-600">Tidak ada mata kuliah</p>
                    <p class="text-sm text-gray-500 mt-2">Kelas ini belum memiliki mata kuliah terhubung</p>
                </div>
            </div>
        `;
        updateBulkCount();
        return;
    }
    
    console.log('Rendering', courses.length, 'courses...');
    
    let html = `
        <!-- Search Box -->
        <div class="mb-4 sticky top-0 bg-white z-20 pb-2 border-b">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       id="course_search" 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                       placeholder="Cari mata kuliah...">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <span id="search_result_count" class="text-sm text-gray-500 hidden"></span>
                </div>
            </div>
            <p class="mt-1 text-xs text-gray-500">Ketik untuk mencari berdasarkan kode atau nama</p>
        </div>

        <!-- Select All Courses -->
        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg flex items-center justify-between sticky top-24 z-10">
            <label class="flex items-center cursor-pointer hover:text-blue-700 transition">
                <input type="checkbox" 
                       id="selectAllCourses"
                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                       onclick="toggleAllCourses(this)">
                <span class="ml-3 font-semibold text-gray-800">Pilih Semua</span>
            </label>
            <div class="text-sm font-medium">
                <span id="bulk_selected_count" class="bulk-counter-value font-bold text-blue-600 text-lg">0</span>
                <span class="text-gray-600"> / <span class="bulk-total-count">${courses.length}</span> dipilih</span>
            </div>
        </div>

        <!-- Courses List -->
        <div id="courses_list" class="space-y-2">
    `;
    
    courses.forEach((course, index) => {
        const uniqueId = `course_${course.id}_${index}`;
        
        // PENTING: Escape HTML dan pastikan data clean
        const courseName = (course.name || '').toLowerCase().replace(/"/g, '&quot;');
        const courseCode = (course.code || '').toLowerCase().replace(/"/g, '&quot;');
        
        html += `
            <label for="${uniqueId}" 
                class="course-item flex items-center p-4 bg-gray-50 hover:bg-blue-50 rounded-lg border border-gray-200 hover:border-blue-300 cursor-pointer transition-all duration-150 group"
                data-course-name="${courseName}"
                data-course-code="${courseCode}">
                <input type="checkbox" 
                    id="${uniqueId}"
                    name="course_ids[]" 
                    value="${course.id}" 
                    class="bulk-course-checkbox w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 flex-shrink-0"
                    onclick="updateBulkCount()">
                <div class="ml-3 flex-grow">
                    <div class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">
                        ${course.name}
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs font-medium">
                            ${course.code}
                        </span>
                        ${course.sks ? `<span class="ml-2 text-gray-500">${course.sks} SKS</span>` : ''}
                    </div>
                </div>
            </label>
        `;
    });
    
    html += `</div>`;
    
    container.innerHTML = html;
    console.log('✓ Courses rendered');
    
    setupSearchFunctionality();
    setTimeout(() => updateBulkCount(), 50);
}

console.log('=== SCRIPT READY ===');
</script>

<script>
$(document).ready(function() {

    // Simpan semua data kelas untuk filtering
    const allStudentClasses = @json($studentClasses);

    // SINGLE ENROLLMENT SECTION
    // Filter Angkatan - untuk memfilter dropdown kelas
    $('#year_id').on('change', function() {
        const yearId = $(this).val();
        const $classDropdown = $('#student_class_id');
        const $courseDropdown = $('#course_id');

        // Reset course dropdown
        $courseDropdown.prop('disabled', true)
            .html('<option value="">-- Pilih kelas terlebih dahulu --</option>')
            .trigger('change');

        if (yearId) {
            // Filter kelas berdasarkan year_id
            const filteredClasses = allStudentClasses.filter(c => c.year_id == yearId);
            
            $classDropdown.html('<option value="">-- Pilih Kelas --</option>');
            
            if (filteredClasses.length > 0) {
                filteredClasses.forEach(function(studentClass) {
                    $classDropdown.append(
                        `<option value="${studentClass.id}" data-year-id="${studentClass.year_id}">${studentClass.name}</option>`
                    );
                });
                $classDropdown.prop('disabled', false);
            } else {
                $classDropdown.html('<option value="">-- Tidak ada kelas untuk angkatan ini --</option>');
                $classDropdown.prop('disabled', true);
            }
        } else {
            // Tampilkan semua kelas jika tidak ada filter
            $classDropdown.html('<option value="">-- Pilih Kelas --</option>');
            allStudentClasses.forEach(function(studentClass) {
                $classDropdown.append(
                    `<option value="${studentClass.id}" data-year-id="${studentClass.year_id}">${studentClass.name}</option>`
                );
            });
            $classDropdown.prop('disabled', false);
        }

        // Trigger change untuk update Select2
        $classDropdown.trigger('change');
    });

    // Handler untuk dropdown Kelas (existing functionality)
    $('#student_class_id').on('change', function() {
        const classId = $(this).val();
        const $courseDropdown = $('#course_id');

        if (classId) {
            $courseDropdown.prop('disabled', true)
                .html('<option value="">-- Memuat mata kuliah... --</option>');

            // AJAX call ke server untuk mendapatkan courses
            $.ajax({
                url: '{{ route("enrollments.courses-by-class") }}',
                type: 'GET',
                data: { student_class_id: classId },
                success: function(response) {
                    $courseDropdown.html('<option value="">-- Pilih Mata Kuliah --</option>');
                    
                    if (response.courses && response.courses.length > 0) {
                        response.courses.forEach(function(course) {
                            $courseDropdown.append(
                                `<option value="${course.id}">${course.name} (${course.code})</option>`
                            );
                        });
                        $courseDropdown.prop('disabled', false);
                    } else {
                        $courseDropdown.html('<option value="">-- Tidak ada mata kuliah --</option>');
                    }
                    $courseDropdown.trigger('change');
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    $courseDropdown.html('<option value="">-- Error memuat data --</option>');
                }
            });
        } else {
            $courseDropdown.prop('disabled', true)
                .html('<option value="">-- Pilih kelas terlebih dahulu --</option>')
                .trigger('change');
        }
    });

    // BULK ENROLLMENT SECTION
    // Filter Angkatan - untuk memfilter dropdown kelas
    $('#bulk_year_id').on('change', function() {
        const yearId = $(this).val();
        const $classDropdown = $('#bulk_student_class_id');
        const $courseDropdown = $('#course_id');

        // Reset course dropdown
        $courseDropdown.prop('disabled', true)
            .html('<option value="">-- Pilih kelas terlebih dahulu --</option>')
            .trigger('change');

        if (yearId) {
            // Filter kelas berdasarkan year_id
            const filteredClasses = allStudentClasses.filter(c => c.year_id == yearId);
            
            $classDropdown.html('<option value="">-- Pilih Kelas --</option>');
            
            if (filteredClasses.length > 0) {
                filteredClasses.forEach(function(studentClass) {
                    $classDropdown.append(
                        `<option value="${studentClass.id}" data-year-id="${studentClass.year_id}">${studentClass.name}</option>`
                    );
                });
                $classDropdown.prop('disabled', false);
            } else {
                $classDropdown.html('<option value="">-- Tidak ada kelas untuk angkatan ini --</option>');
                $classDropdown.prop('disabled', true);
            }
        } else {
            // Tampilkan semua kelas jika tidak ada filter
            $classDropdown.html('<option value="">-- Pilih Kelas --</option>');
            allStudentClasses.forEach(function(studentClass) {
                $classDropdown.append(
                    `<option value="${studentClass.id}" data-year-id="${studentClass.year_id}">${studentClass.name}</option>`
                );
            });
            $classDropdown.prop('disabled', false);
        }

        // Trigger change untuk update Select2
        $classDropdown.trigger('change');
    });

    // Handler untuk dropdown Kelas (existing functionality)
    $('#bulk_student_class_id').on('change', function() {
        const classId = $(this).val();
        const $courseDropdown = $('#course_id');

        if (classId) {
            $courseDropdown.prop('disabled', true)
                .html('<option value="">-- Memuat mata kuliah... --</option>');

            // AJAX call ke server untuk mendapatkan courses
            $.ajax({
                url: '{{ route("enrollments.courses-by-class") }}',
                type: 'GET',
                data: { student_class_id: classId },
                success: function(response) {
                    $courseDropdown.html('<option value="">-- Pilih Mata Kuliah --</option>');
                    
                    if (response.courses && response.courses.length > 0) {
                        response.courses.forEach(function(course) {
                            $courseDropdown.append(
                                `<option value="${course.id}">${course.name} (${course.code})</option>`
                            );
                        });
                        $courseDropdown.prop('disabled', false);
                    } else {
                        $courseDropdown.html('<option value="">-- Tidak ada mata kuliah --</option>');
                    }
                    $courseDropdown.trigger('change');
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    $courseDropdown.html('<option value="">-- Error memuat data --</option>');
                }
            });
        } else {
            $courseDropdown.prop('disabled', true)
                .html('<option value="">-- Pilih kelas terlebih dahulu --</option>')
                .trigger('change');
        }
    });
});
</script>

<script>
// ========== COURSE SEARCH FUNCTIONALITY ==========

/**
 * Setup search functionality
 */
function setupSearchFunctionality() {
    const $searchInput = $('#course_search');
    
    if ($searchInput.length === 0) {
        console.error('❌ Search input not found');
        return;
    }
    
    console.log('✓ Setting up search functionality');
    
    // Clear previous listeners
    $searchInput.off('input keydown');
    
    // Real-time filter
    $searchInput.on('input', function() {
        const searchQuery = $(this).val().toLowerCase().trim();
        let visibleCount = 0;
        let totalCount = 0;
        
        // Cari semua .course-item
        $('label.course-item').each(function() {
            const $label = $(this);
            
            // PERBAIKAN: Gunakan .attr() atau ambil dari HTML langsung
            const courseName = ($label.attr('data-course-name') || '').toLowerCase();
            const courseCode = ($label.attr('data-course-code') || '').toLowerCase();
            
            totalCount++;
            
            // DEBUG: Log jika ada yang kosong
            if (!courseName && !courseCode) {
                console.warn('⚠ Empty data attributes on:', $label.html().substring(0, 50));
            }
            
            // PENTING: Cek apakah string sebelum .includes()
            const matches = !searchQuery || 
                           (typeof courseName === 'string' && courseName.includes(searchQuery)) || 
                           (typeof courseCode === 'string' && courseCode.includes(searchQuery));
            
            if (matches) {
                $label.show();
                visibleCount++;
            } else {
                $label.hide();
            }
        });
        
        console.log(`✓ Search: "${searchQuery}" → ${visibleCount} / ${totalCount} visible`);
        
        // Update counter
        const $counter = $('#search_result_count');
        if (searchQuery) {
            $counter.text(`${visibleCount} / ${totalCount}`).removeClass('hidden');
        } else {
            $counter.addClass('hidden');
        }
        
        // No results message
        const hasVisible = visibleCount > 0;
        const $container = $('#bulk_courses_container');
        
        if (!hasVisible && totalCount > 0) {
            if ($container.find('#no_search_results').length === 0) {
                $container.append(`
                    <div id="no_search_results" class="text-center py-8 mt-4">
                        <p class="text-gray-500 font-medium">Tidak ada hasil untuk: "${searchQuery}"</p>
                    </div>
                `);
            }
        } else {
            $container.find('#no_search_results').remove();
        }
    });
    
    // ESC to clear
    $searchInput.on('keydown', function(e) {
        if (e.key === 'Escape') {
            console.log('✓ ESC pressed - clearing search');
            $(this).val('').trigger('input');
        }
    });
    
    console.log('✓ Search functionality ready - WITH TYPE CHECKING');
}

/**
 * Reset search box
 */
function resetCourseSearch() {
    $('#course_search').val('').prop('disabled', true);
    $('#search_result_count').addClass('hidden');
    $('#bulk_courses_container').find('#no_search_results').remove();
    console.log('✓ Course search reset');
}

console.log('✓ All search scripts loaded - CLEAN & WORKING');

</script>
@endsection
