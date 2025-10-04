@extends('layouts.app')

@section('title', 'Kelola Semester - Sistem Penilaian PKUMI')

@section('content')
<div class="min-h-screen bg-gradient-to-br py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('semesters.index') }}" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
            <h1 class="mt-4 text-3xl font-bold text-gray-900">Edit Semester</h1>
            <p class="mt-2 text-sm text-gray-600">Ubah informasi semester: {{ $semester->name }}</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4 shadow-sm">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-medium text-red-800">Terdapat beberapa kesalahan:</p>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Warning for existing enrollments -->
        @if($semester->enrollments->count() > 0)
        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4 shadow-sm">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="text-sm text-yellow-800">
                    <p class="font-semibold">Perhatian!</p>
                    <p class="mt-1">Semester ini memiliki {{ $semester->enrollments->count() }} enrollment aktif. Perubahan tanggal dapat mempengaruhi data yang sudah ada.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <form action="{{ route('semesters.update', $semester) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Period Selection -->
                <div>
                    <label for="period_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Period <span class="text-red-500">*</span>
                    </label>
                    <select name="period_id" 
                            id="period_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('period_id') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Period</option>
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}" {{ old('period_id', $semester->period_id) == $period->id ? 'selected' : '' }}>
                                {{ $period->name }} ({{ $period->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('period_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Semester -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Semester <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $semester->name) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('name') border-red-500 @enderror"
                           placeholder="Contoh: Semester Ganjil 2024/2025"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kode Semester -->
                <div>
                    <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">
                        Kode Semester <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           id="code" 
                           value="{{ old('code', $semester->code) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('code') border-red-500 @enderror"
                           placeholder="Contoh: 2024-1"
                           required>
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Semester -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Mulai Semester <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="start_date" 
                               id="start_date" 
                               value="{{ old('start_date', $semester->start_date->format('Y-m-d')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('start_date') border-red-500 @enderror"
                               required>
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Berakhir Semester <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="end_date" 
                               id="end_date" 
                               value="{{ old('end_date', $semester->end_date->format('Y-m-d')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('end_date') border-red-500 @enderror"
                               required>
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tanggal Pendaftaran -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="enrollment_start_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Mulai Pendaftaran <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="enrollment_start_date" 
                               id="enrollment_start_date" 
                               value="{{ old('enrollment_start_date', $semester->enrollment_start_date->format('Y-m-d')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('enrollment_start_date') border-red-500 @enderror"
                               required>
                        @error('enrollment_start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="enrollment_end_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Berakhir Pendaftaran <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="enrollment_end_date" 
                               id="enrollment_end_date" 
                               value="{{ old('enrollment_end_date', $semester->enrollment_end_date->format('Y-m-d')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('enrollment_end_date') border-red-500 @enderror"
                               required>
                        @error('enrollment_end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            id="status" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('status') border-red-500 @enderror"
                            required>
                        <option value="draft" {{ old('status', $semester->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ old('status', $semester->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ old('status', $semester->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Metadata -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Informasi Tambahan</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Dibuat:</span> 
                            {{ $semester->created_at->format('d M Y, H:i') }}
                        </div>
                        <div>
                            <span class="font-medium">Terakhir Diupdate:</span> 
                            {{ $semester->updated_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('semesters.index') }}" 
                       class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200">
                        Update Semester
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
