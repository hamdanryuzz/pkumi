@extends('layouts.app')

@section('title', 'Edit Semester - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('semester.index') }}" 
               class="text-gray-600 hover:text-gray-800 transition duration-200">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Edit Semester</h2>
                <p class="text-base text-gray-500 mt-1">Ubah informasi Semester: {{ $semester->name }}</p>
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
                Form Edit Semester
            </h5>
            <p class="text-sm text-gray-600 mt-1">Perbarui informasi Semester</p>
        </div>

        <form action="{{ route('semester.update', $semester) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Semester -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1"></i>Nama Semester
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $semester->name) }}" 
                           placeholder="contoh: Semester Ganjil 2024/2025"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" 
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kode Semester -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-code mr-1"></i>Kode Semester
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           id="code" 
                           value="{{ old('code', $semester->code) }}" 
                           placeholder="contoh: 2024-1"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('code') border-red-500 @enderror" 
                           required>
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-info-circle mr-1"></i>Status 
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            id="status" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" 
                            required>
                        <option value="draft" {{ old('status', $semester->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ old('status', $semester->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="completed" {{ old('status', $semester->status) === 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Mulai Semester -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-play mr-1 text-green-600"></i>Tanggal Mulai Semester
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="start_date" 
                           id="start_date" 
                           value="{{ old('start_date', $semester->start_date->format('Y-m-d')) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror" 
                           required>
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Berakhir Semester -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-stop mr-1 text-red-600"></i>Tanggal Berakhir Semester
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="end_date" 
                           id="end_date" 
                           value="{{ old('end_date', $semester->end_date->format('Y-m-d')) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror" 
                           required>
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Mulai Pendaftaran -->
                <div>
                    <label for="enrollment_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-door-open mr-1 text-blue-600"></i>Tanggal Mulai Pendaftaran 
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="enrollment_start_date" 
                           id="enrollment_start_date" 
                           value="{{ old('enrollment_start_date', $semester->enrollment_start_date->format('Y-m-d')) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('enrollment_start_date') border-red-500 @enderror" 
                           required>
                    @error('enrollment_start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Berakhir Pendaftaran -->
                <div>
                    <label for="enrollment_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-door-closed mr-1 text-orange-600"></i>Tanggal Berakhir Pendaftaran 
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="enrollment_end_date" 
                           id="enrollment_end_date" 
                           value="{{ old('enrollment_end_date', $semester->enrollment_end_date->format('Y-m-d')) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('enrollment_end_date') border-red-500 @enderror" 
                           required>
                    @error('enrollment_end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Enrollment Info -->
            @if($semester->enrollments()->count() > 0)
                <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-2"></i>
                        <div class="text-sm text-yellow-800">
                            <strong>Peringatan:</strong>
                            Semester ini memiliki {{ $semester->enrollments()->count() }} enrollment aktif. 
                            Perubahan tanggal dapat mempengaruhi data yang sudah ada.
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Actions -->
            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('semester.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-200">
                    <i class="fas fa-save mr-2"></i>Update Semester
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Date validation - same as create form
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const enrollmentStartDate = document.getElementById('enrollment_start_date');
    const enrollmentEndDate = document.getElementById('enrollment_end_date');

    function updateDateConstraints() {
        if (startDate.value) {
            endDate.min = startDate.value;
            enrollmentStartDate.max = startDate.value;
            enrollmentEndDate.max = startDate.value;
        }
        
        if (enrollmentStartDate.value) {
            enrollmentEndDate.min = enrollmentStartDate.value;
        }
    }

    startDate.addEventListener('change', updateDateConstraints);
    enrollmentStartDate.addEventListener('change', updateDateConstraints);
    
    // Initial validation
    updateDateConstraints();
});
</script>
@endsection
