@extends('layouts.app')

@section('title', 'Edit Enrollment - Sistem Penilaian PKUMI')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Enrollment</h1>
        <p class="text-gray-600">Ubah informasi enrollment: {{ $enrollment->studentClass->name }} - {{ $enrollment->course->name }}</p>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm" role="alert">
        <div class="flex items-start">
            <i class="fas fa-exclamation-circle mr-3 text-xl mt-1"></i>
            <div>
                <p class="font-bold mb-2">Terjadi kesalahan!</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Form -->
    <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200">
        <div class="mb-6">
            <h5 class="text-lg font-semibold text-gray-800 mb-1">Form Edit Enrollment</h5>
            <p class="text-sm text-gray-600">Perbarui informasi pendaftaran kelas</p>
        </div>

        <form action="{{ route('enrollments.update', $enrollment->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Semester -->
                <div>
                    <label for="semester_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Semester <span class="text-red-500">*</span>
                    </label>
                    <select name="semester_id" id="semester_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('semester_id') border-red-500 @enderror">
                        <option value="">Pilih Semester</option>
                        @foreach($semesters as $semester)
                        <option value="{{ $semester->id }}" {{ old('semester_id', $enrollment->semester_id) == $semester->id ? 'selected' : '' }}>
                            {{ $semester->name }}
                            @if($semester->status === 'active')
                            (Aktif)
                            @endif
                        </option>
                        @endforeach
                    </select>
                    @error('semester_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course -->
                <div>
                    <label for="course_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <select name="course_id" id="course_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('course_id') border-red-500 @enderror">
                        <option value="">Pilih Mata Kuliah</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $enrollment->course_id) == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} ({{ $course->code }})
                        </option>
                        @endforeach
                    </select>
                    @error('course_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Student Class -->
                <div>
                    <label for="student_class_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <select name="student_class_id" id="student_class_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('student_class_id') border-red-500 @enderror">
                        <option value="">Pilih Kelas</option>
                        @foreach($studentClasses as $class)
                        <option value="{{ $class->id }}" {{ old('student_class_id', $enrollment->student_class_id) == $class->id ? 'selected' : '' }}>
                            {{ $class->name }} - {{ $class->year->name ?? 'Tahun tidak diketahui' }}
                        </option>
                        @endforeach
                    </select>
                    @error('student_class_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Enrollment Date -->
                <div>
                    <label for="enrollment_date" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Pendaftaran <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="enrollment_date" id="enrollment_date" value="{{ old('enrollment_date', $enrollment->enrollment_date) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('enrollment_date') border-red-500 @enderror">
                    @error('enrollment_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('status') border-red-500 @enderror">
                        <option value="enrolled" {{ old('status', $enrollment->status) === 'enrolled' ? 'selected' : '' }}>Terdaftar</option>
                        <option value="dropped" {{ old('status', $enrollment->status) === 'dropped' ? 'selected' : '' }}>Dropped</option>
                        <option value="completed" {{ old('status', $enrollment->status) === 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Warning Message -->
            <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 mt-1"></i>
                    <div>
                        <p class="font-semibold text-yellow-800">Perhatian:</p>
                        <p class="text-sm text-yellow-700">Perubahan data enrollment dapat mempengaruhi data nilai yang sudah terinput untuk kelas ini.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('enrollments.index') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition duration-200 inline-flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Update Enrollment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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
</script>
@endsection

