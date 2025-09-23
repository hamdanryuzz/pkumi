@extends('layouts.app')

@section('title', 'Edit Enrollment - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('enrollments.index') }}" 
               class="text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Edit Enrollment</h2>
                <p class="text-base text-gray-500 mt-1">
                    Ubah informasi enrollment: {{ $enrollment->student->name }} - {{ $enrollment->course->name }}
                </p>
            </div>
        </div>
    </div>

    <!-- Error Alert -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <strong class="font-bold">Terjadi kesalahan!</strong>
            <ul class="list-disc list-inside mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <h5 class="text-lg font-bold text-gray-800">
                <i class="fas fa-edit mr-2"></i>
                Form Edit Enrollment
            </h5>
            <p class="text-sm text-gray-600 mt-1">Perbarui informasi pendaftaran mahasiswa</p>
        </div>

        <form action="{{ route('enrollments.update', $enrollment) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Period -->
                <div>
                    <label for="period_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-1"></i>Period 
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="period_id" id="period_id" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('period_id') border-red-500 @enderror" 
                            required>
                        <option value="">Pilih Period</option>
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}" 
                                    {{ (old('period_id', $enrollment->period_id) == $period->id) ? 'selected' : '' }}>
                                {{ $period->name }}
                                @if($period->status === 'active')
                                    <span class="text-green-600">(Aktif)</span>
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('period_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course -->
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-book mr-1"></i>Mata Kuliah 
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="course_id" id="course_id" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('course_id') border-red-500 @enderror" 
                            required>
                        <option value="">Pilih Mata Kuliah</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ (old('course_id', $enrollment->course_id) == $course->id) ? 'selected' : '' }}>
                                {{ $course->name }} ({{ $course->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Student -->
                <div class="md:col-span-2">
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-1"></i>Mahasiswa 
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="student_id" id="student_id" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('student_id') border-red-500 @enderror" 
                            required>
                        <option value="">Pilih Mahasiswa</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ (old('student_id', $enrollment->student_id) == $student->id) ? 'selected' : '' }}>
                                {{ $student->name }} - {{ $student->nim }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Enrollment Date -->
                <div>
                    <label for="enrollment_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-plus mr-1"></i>Tanggal Pendaftaran 
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="enrollment_date" 
                           id="enrollment_date" 
                           value="{{ old('enrollment_date', $enrollment->enrollment_date->format('Y-m-d')) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('enrollment_date') border-red-500 @enderror" 
                           required>
                    @error('enrollment_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-info-circle mr-1"></i>Status 
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" 
                            required>
                        <option value="enrolled" {{ old('status', $enrollment->status) === 'enrolled' ? 'selected' : '' }}>Terdaftar</option>
                        <option value="dropped" {{ old('status', $enrollment->status) === 'dropped' ? 'selected' : '' }}>Dropped</option>
                        <option value="completed" {{ old('status', $enrollment->status) === 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Grade Info -->
            @php
                $hasGrades = $enrollment->student->grades()->where('course_id', $enrollment->course_id)->where('period_id', $enrollment->period_id)->exists();
            @endphp
            @if($hasGrades)
                <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-2"></i>
                        <div class="text-sm text-yellow-800">
                            <strong>Peringatan:</strong>
                            Enrollment ini sudah memiliki nilai. Perubahan data dapat mempengaruhi nilai yang sudah diinput.
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Actions -->
            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('enrollments.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-200">
                    <i class="fas fa-save mr-2"></i>Update Enrollment
                </button>
            </div>
        </form>
    </div>
</main>
@endsection
