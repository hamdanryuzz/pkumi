@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">Tambah Kelas</h2>
                    <p class="mt-1 text-sm text-gray-500">Buat kelas baru untuk siswa</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
        <!-- Form Tambah Kelas -->
        <form action="{{ route('student_classes.store') }}" method="POST">
            @csrf

            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Form Tambah Kelas</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Angkatan -->
                    <div>
                        <label for="year_id" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                            Angkatan <span class="text-red-500">*</span>
                        </label>
                        <select name="year_id" id="year_id" required
                                class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white transition-all duration-200 @error('year_id') border-red-500 @enderror">
                            <option value="">Pilih Angkatan</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}" {{ old('year_id') == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('year_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Kelas -->
                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                            Nama Kelas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name') }}" required
                               class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 @error('name') border-red-500 @enderror"
                               placeholder="Nama kelas">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-gray-200 bg-white">
                <a href="{{ route('student_classes.index') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection