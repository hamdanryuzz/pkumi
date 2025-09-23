@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            Edit Mata Kuliah
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Perbarui informasi mata kuliah: {{ $course->name }}
                        </p>
                    </div>
                    <div class="mt-4 flex md:mt-0 md:ml-4">
                        <a href="{{ route('courses.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg inline-flex items-center">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Form Edit Course
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Perbarui informasi mata kuliah yang sudah ada
                </p>
            </div>

            <form action="{{ route('courses.update', $course) }}" method="POST" class="px-6 py-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Course Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Nama Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $course->name) }}"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 @enderror"
                               placeholder="Contoh: Pemrograman Web Lanjut">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course Code -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">
                        Kode Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <input type="text" 
                               name="code" 
                               id="code" 
                               value="{{ old('code', $course->code) }}"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('code') border-red-300 @enderror"
                               placeholder="Contoh: PWL201">
                    </div>
                    @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        Kode unik untuk mata kuliah ini
                    </p>
                </div>

                <!-- Course Info -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Informasi Course</h4>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500">Dibuat pada:</dt>
                            <dd class="text-gray-900">{{ $course->created_at->format('d M Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Terakhir diupdate:</dt>
                            <dd class="text-gray-900">{{ $course->updated_at->format('d M Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('courses.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg inline-flex items-center">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Mata Kuliah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
