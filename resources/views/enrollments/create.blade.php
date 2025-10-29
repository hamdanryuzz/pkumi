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
                                <span id="bulk_selected_count" 
                                      class="inline-flex items-center justify-center min-w-[48px] h-10 px-4 bg-gradient-to-r from-green-600 to-green-600 text-white text-lg font-bold rounded-full shadow-md">0</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('enrollments.index') }}" 
                               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                                Batal
                            </a>
                            <button type="submit" id="bulkSubmitBtn" disabled
                                    class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-700 disabled:from-gray-400 disabled:to-gray-400 disabled:cursor-not-allowed text-white font-semibold rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:transform-none disabled:shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Daftarkan Semua Mata Kuliah
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
function handleStudentClassChange(studentClassId) {
    console.log('Single enrollment - Class changed:', studentClassId);
    
    // Reset Select2
    $('#course_id').empty().trigger('change');
    
    if (!studentClassId) {
        $('#course_id').append(new Option('Pilih kelas terlebih dahulu...', '', false, false)).trigger('change');
        $('#course_id').prop('disabled', true);
        return;
    }
    
    $('#course_id').append(new Option('Memuat mata kuliah...', '', false, false)).trigger('change');
    $('#course_id').prop('disabled', true);
    
    fetch(`${API_URL}?student_class_id=${studentClassId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Courses loaded:', data.courses.length);
        
        // Clear and repopulate Select2
        $('#course_id').empty();
        $('#course_id').append(new Option('-- Pilih Mata Kuliah --', '', false, false));
        
        if (data.courses && data.courses.length > 0) {
            data.courses.forEach(course => {
                const optionText = `${course.name} (${course.code}) - ${course.sks} SKS`;
                $('#course_id').append(new Option(optionText, course.id, false, false));
            });
            $('#course_id').prop('disabled', false);
        } else {
            $('#course_id').append(new Option('Tidak ada mata kuliah', '', false, false));
            $('#course_id').prop('disabled', true);
        }
        
        $('#course_id').trigger('change');
    })
    .catch(error => {
        console.error('Error:', error);
        $('#course_id').empty();
        $('#course_id').append(new Option('Error memuat data', '', false, false));
        $('#course_id').prop('disabled', true);
        $('#course_id').trigger('change');
        alert('Gagal memuat mata kuliah: ' + error.message);
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
    
    fetch(`${API_URL}?student_class_id=${studentClassId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Bulk courses loaded:', data.courses.length);
        renderBulkCourses(data.courses);
    })
    .catch(error => {
        console.error('Error:', error);
        container.innerHTML = `
            <div class="text-center py-16 text-red-600">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-semibold text-lg">Gagal memuat data</p>
                <p class="text-sm mt-2">${error.message}</p>
            </div>
        `;
    });
}

function renderBulkCourses(courses) {
    const container = document.getElementById('bulk_courses_container');
    
    if (!courses || courses.length === 0) {
        container.innerHTML = `
            <div class="flex items-center justify-center h-full text-gray-400">
                <div class="text-center">
                    <svg class="w-20 h-20 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-base font-medium text-gray-600">Tidak ada mata kuliah</p>
                    <p class="text-sm text-gray-500 mt-2">Kelas ini belum memiliki mata kuliah</p>
                </div>
            </div>
        `;
        updateBulkCount();
        return;
    }
    
    let html = `
        <div class="mb-4 pb-4 border-b-2 border-gray-300">
            <label class="flex items-center cursor-pointer hover:bg-white p-4 rounded-lg transition-all group">
                <input type="checkbox" id="select_all_courses" 
                       class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-2 focus:ring-green-500" 
                       onchange="toggleAllCourses(this)">
                <span class="ml-3 text-base font-bold text-gray-800 group-hover:text-green-700">Pilih Semua Mata Kuliah</span>
            </label>
        </div>
        <div class="space-y-3">
    `;
    
    courses.forEach((course, index) => {
        html += `
            <div class="border-2 border-gray-200 rounded-xl hover:border-green-400 hover:bg-green-50 hover:shadow-md transition-all duration-200">
                <label class="flex items-start p-4 cursor-pointer">
                    <input type="checkbox" name="course_ids[]" value="${course.id}" 
                           class="course-checkbox w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-2 focus:ring-green-500 mt-1 flex-shrink-0" 
                           onchange="updateBulkCount()">
                    <div class="ml-4 flex-1">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 text-base">${course.name}</p>
                                <p class="text-sm text-gray-600 mt-1 font-medium">${course.code}</p>
                            </div>
                            <span class="ml-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200">
                                ${course.sks} SKS
                            </span>
                        </div>
                    </div>
                </label>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;
    updateBulkCount();
}

function toggleAllCourses(checkbox) {
    document.querySelectorAll('.course-checkbox').forEach(cb => cb.checked = checkbox.checked);
    updateBulkCount();
}

function updateBulkCount() {
    const count = document.querySelectorAll('.course-checkbox:checked').length;
    document.getElementById('bulk_selected_count').textContent = count;
    document.getElementById('bulkSubmitBtn').disabled = count === 0;
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
@endsection
