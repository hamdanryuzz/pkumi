@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-plus-circle mr-2 text-green-600"></i>Tambah Mata Kuliah
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Buat mata kuliah baru dalam sistem
        </p>
    </div>

    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('courses.index') }}" class="text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400">
                    <i class="fas fa-home mr-2"></i>Mata Kuliah
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500 dark:text-gray-400">Tambah</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                Informasi Mata Kuliah
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Isi informasi mata kuliah yang akan ditambahkan
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 mt-0.5 mr-3"></i>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-300 mb-2">
                            Terdapat beberapa kesalahan:
                        </h3>
                        <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-400 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('courses.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Kode Mata Kuliah -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kode Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           id="code" 
                           value="{{ old('code') }}"
                           placeholder="Contoh: MK001"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 @error('code') border-red-500 @enderror">
                    @error('code')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>Kode unik untuk mata kuliah ini
                    </p>
                </div>

                <!-- Nama Mata Kuliah -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nama Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}"
                           placeholder="Contoh: Pemrograman Web"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Filter Angkatan -->
                <div>
                    <label for="year_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>Filter Angkatan
                    </label>
                    <select id="year_filter" class="select2-year w-full">
                        <option value="">-- Pilih Angkatan Terlebih Dahulu --</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>Pilih angkatan untuk memfilter kelas
                    </p>
                </div>

                <!-- Kelas -->
                <div>
                    <label for="student_class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <select name="student_class_id" 
                            id="student_class_id" 
                            required
                            class="select2-class w-full @error('student_class_id') border-red-500 @enderror">
                        <option value="">-- Pilih angkatan terlebih dahulu --</option>
                        @foreach($studentClasses as $class)
                            <option value="{{ $class->id }}" 
                                    data-year-id="{{ $class->year_id }}"
                                    {{ old('student_class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_class_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('courses.index') }}" 
                   class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 flex items-center shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript untuk Select2 dan Cascade Dropdown -->
<script>
$(document).ready(function() {
    // Simpan semua data kelas
    const allClassOptions = [];
    $('#student_class_id option').each(function() {
        if ($(this).val() !== '') {
            allClassOptions.push({
                id: $(this).val(),
                text: $(this).text(),
                yearId: $(this).data('year-id')
            });
        }
    });
    
    // Initialize Select2 untuk Filter Angkatan
    $('#year_filter').select2({
        placeholder: '-- Pilih Angkatan Terlebih Dahulu --',
        allowClear: true,
        width: '100%'
    });
    
    // Initialize Select2 untuk Kelas
    $('#student_class_id').select2({
        placeholder: '-- Pilih angkatan terlebih dahulu --',
        allowClear: false,
        width: '100%'
    });
    
    // Disable kelas dropdown pada awal
    $('#student_class_id').prop('disabled', true);
    
    // Fungsi untuk update options kelas berdasarkan angkatan
    function updateClassOptions(yearId) {
        // Clear options kecuali placeholder
        $('#student_class_id').empty().append(
            $('<option>', { value: '', text: '-- Pilih Kelas --' })
        );
        
        if (!yearId || yearId === '') {
            $('#student_class_id').prop('disabled', true);
            $('#student_class_id').empty().append(
                $('<option>', { value: '', text: '-- Pilih angkatan terlebih dahulu --' })
            );
            $('#student_class_id').trigger('change');
            return;
        }
        
        // Filter kelas berdasarkan angkatan
        const filteredOptions = allClassOptions.filter(option => option.yearId == yearId);
        
        if (filteredOptions.length > 0) {
            $('#student_class_id').prop('disabled', false);
            
            filteredOptions.forEach(option => {
                const newOption = $('<option>', {
                    value: option.id,
                    text: option.text,
                    'data-year-id': option.yearId
                });
                $('#student_class_id').append(newOption);
            });
        } else {
            $('#student_class_id').prop('disabled', true);
            $('#student_class_id').empty().append(
                $('<option>', { value: '', text: '-- Tidak ada kelas untuk angkatan ini --' })
            );
        }
        
        // Trigger change untuk update Select2 display
        $('#student_class_id').trigger('change');
    }
    
    // Event listener untuk perubahan dropdown angkatan
    $('#year_filter').on('change', function() {
        const selectedYearId = $(this).val();
        updateClassOptions(selectedYearId);
    });
});
</script>
@endsection
