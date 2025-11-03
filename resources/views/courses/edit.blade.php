@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center mb-2">
            <a href="{{ route('courses.index') }}" 
               class="text-gray-600 hover:text-gray-800 mr-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Mata Kuliah</h1>
                <p class="text-gray-600 mt-1">Perbarui informasi mata kuliah: <strong>{{ $course->name }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Informasi Mata Kuliah</h2>
            <p class="text-sm text-gray-600 mt-1">Perbarui informasi mata kuliah yang sudah ada</p>
        </div>

        <form action="{{ route('courses.update', $course->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <!-- Course Name -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Mata Kuliah <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name', $course->name) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                       placeholder="Contoh: Pemrograman Web"
                       required>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Course Code -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kode Mata Kuliah <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="code" 
                       value="{{ old('code', $course->code) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('code') border-red-500 @enderror"
                       placeholder="Contoh: CS101"
                       required>
                <p class="mt-1 text-xs text-gray-500">Kode unik untuk mata kuliah ini</p>
                @error('code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- SKS -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    SKS (Satuan Kredit Semester)
                </label>
                <input type="number" 
                       name="sks" 
                       value="{{ old('sks', $course->sks) }}"
                       min="1"
                       max="10"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sks') border-red-500 @enderror"
                       placeholder="Contoh: 3">
                @error('sks')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-6"></div>

            <!-- Class Pattern -->
            <div class="mb-6">
                <label for="class_pattern" class="block text-sm font-medium text-gray-700 mb-2">
                    Pola Kelas (Opsional)
                </label>

                @php
                    // Opsi bisa kamu ubah/ambil dari DB bila perlu
                    $patterns = ['S2 PKU', 'S2 PKUP', 'S3 PKU'];
                    $selected = old('class_pattern', $course->class_pattern);
                @endphp

                <select
                    name="class_pattern"
                    id="class_pattern"
                    class="select2-pattern w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('class_pattern') border-red-500 @enderror"
                >
                    <option value="">Pilih Pola</option>
                    @foreach ($patterns as $p)
                        <option value="{{ $p }}" {{ $selected === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>

                <p class="mt-1 text-xs text-gray-500">
                    Pilih pola untuk auto-assign (contoh: "S2 PKU" akan match dengan S2 PKU A, S2 PKU B, dll)
                </p>

                @error('class_pattern')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- OR Divider -->
            <div class="relative mb-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500 font-medium">ATAU</span>
                </div>
            </div>

            <!-- Manual Class Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Kelas Secara Manual
                </label>
                <select name="student_class_ids[]" 
                        multiple 
                        class="select2-classes w-full"
                        data-placeholder="Pilih satu atau lebih kelas...">
                    @foreach($studentClasses as $class)
                        <option value="{{ $class->id }}" 
                            {{ in_array($class->id, old('student_class_ids', $course->studentClasses->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $class->name }} - {{ $class->year->name ?? '' }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">
                    Mata kuliah dapat terhubung dengan banyak kelas sekaligus
                </p>
                @error('student_class_ids')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Classes Info -->
            @if($course->studentClasses->count() > 0)
            <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <p class="text-sm font-medium text-gray-700 mb-2">Kelas Terhubung Saat Ini:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($course->studentClasses as $class)
                        <span class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-full">
                            {{ $class->name }} - {{ $class->year->name ?? '' }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Info Box -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-medium mb-1">Catatan Penting:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Jika mengisi <strong>Pola Kelas</strong>, sistem akan otomatis menghubungkan dengan kelas yang cocok</li>
                            <li>Jika memilih <strong>Kelas Manual</strong>, mata kuliah hanya terhubung dengan kelas yang dipilih</li>
                            <li>Pilihan manual akan menimpa pola kelas jika keduanya diisi</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('courses.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition">
                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Mata Kuliah
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2 for multiple class selection
    $('.select2-classes').select2({
        theme: 'default',
        placeholder: 'Pilih satu atau lebih kelas...',
        allowClear: true,
        width: '100%'
    });
});

    /**
     * Initialize Select2 for Pattern dropdown
     */
    $('.select2-pattern').select2({
        placeholder: '-- Pilih Pola --',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() { return "Pola tidak ditemukan"; },
            searching: function() { return "Mencari..."; }
        }
    });
</script>
@endsection
