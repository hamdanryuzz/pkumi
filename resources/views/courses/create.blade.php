@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Tambah Mata Kuliah</h1>
            <p class="text-gray-600 mt-1">Buat mata kuliah baru dalam sistem</p>
        </div>
        <a href="{{ route('courses.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Form Mata Kuliah</h2>
            <p class="text-sm text-gray-600 mt-1">Isi informasi mata kuliah yang akan ditambahkan</p>
        </div>

        <form method="POST" action="{{ route('courses.store') }}">
            @csrf

            <!-- Course Code -->
            <div class="mb-6">
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                    Kode Mata Kuliah <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="code" 
                       name="code" 
                       value="{{ old('code') }}" 
                       placeholder="Masukkan kode mata kuliah (misal: CS101)"
                       class="w-full px-3 py-2 border {{ $errors->has('code') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                       required>
                @error('code')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Kode unik untuk mata kuliah ini</p>
            </div>

            <!-- Course Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Mata Kuliah <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       placeholder="Masukkan nama mata kuliah"
                       class="w-full px-3 py-2 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Filter Angkatan -->
            <div class="mb-4">
                <label for="year_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Angkatan
                </label>
                <select id="year_filter" 
                        class="select2-year w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">-- Pilih Angkatan --</option>
                    @foreach($years as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Dropdown Kelas (Student Class) -->
            <div class="mb-4">
                <label for="student_class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Kelas <span class="text-red-500">*</span>
                </label>
                <select name="student_class_id" 
                        id="student_class_id" 
                        required
                        class="select2-class w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        disabled>
                    <option value="">-- Pilih angkatan terlebih dahulu --</option>
                </select>
                @error('student_class_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('courses.index') }}" 
                   class="px-4 py-2 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 transition duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
                    <i class="fas fa-save mr-2"></i>Simpan Mata Kuliah
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Data Kelas dalam JSON untuk JavaScript -->
<script>
    const studentClassesData = @json($studentClasses);
</script>

<!-- JavaScript untuk Cascade Dropdown -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const yearFilter = document.getElementById('year_filter');
    const studentClassSelect = document.getElementById('student_class_id');
    
    yearFilter.addEventListener('change', function() {
        const selectedYearId = this.value;
        
        // Reset dropdown kelas
        studentClassSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
        
        if (selectedYearId === '') {
            studentClassSelect.disabled = true;
            studentClassSelect.innerHTML = '<option value="">-- Pilih angkatan terlebih dahulu --</option>';
            return;
        }
        
        // Filter kelas berdasarkan angkatan yang dipilih
        const filteredClasses = studentClassesData.filter(
            studentClass => studentClass.year_id == selectedYearId
        );
        
        if (filteredClasses.length > 0) {
            studentClassSelect.disabled = false;
            filteredClasses.forEach(studentClass => {
                const option = document.createElement('option');
                option.value = studentClass.id;
                option.textContent = studentClass.name;
                studentClassSelect.appendChild(option);
            });
        } else {
            studentClassSelect.disabled = true;
            studentClassSelect.innerHTML = '<option value="">-- Tidak ada kelas untuk angkatan ini --</option>';
        }
    });
});
</script>
@endsection
