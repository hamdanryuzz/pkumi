@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detail Mata Kuliah</h1>
            <p class="text-gray-600 mt-1">{{ $course->code }} - {{ $course->name }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('courses.edit', $course) }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('courses.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Course Info Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Mata Kuliah</h2>
                    <p class="text-sm text-gray-600 mt-1">Detail lengkap mata kuliah</p>
                </div>

                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:justify-between border-b border-gray-200 pb-4">
                        <span class="text-sm font-medium text-gray-600">Kode Mata Kuliah</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $course->code }}</span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:justify-between border-b border-gray-200 pb-4">
                        <span class="text-sm font-medium text-gray-600">Nama Mata Kuliah</span>
                        <span class="text-sm text-gray-900">{{ $course->name }}</span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:justify-between border-b border-gray-200 pb-4">
                        <span class="text-sm font-medium text-gray-600">Kelas</span>
                        <span class="text-sm text-gray-900">
                            {{ $course->studentClass->name ?? 'Tidak ada kelas' }}
                            @if($course->studentClass && $course->studentClass->year)
                                <span class="text-gray-500"> - {{ $course->studentClass->year->name }}</span>
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:justify-between border-b border-gray-200 pb-4">
                        <span class="text-sm font-medium text-gray-600">Dibuat</span>
                        <span class="text-sm text-gray-900">{{ $course->created_at->format('d F Y H:i') }}</span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:justify-between">
                        <span class="text-sm font-medium text-gray-600">Terakhir Diupdate</span>
                        <span class="text-sm text-gray-900">{{ $course->updated_at->format('d F Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Statistik</h2>
                    <p class="text-sm text-gray-600 mt-1">Data terkait mata kuliah</p>
                </div>

                <div class="space-y-6">
                    <!-- Total Grades -->
                    <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="text-2xl font-bold text-blue-600">
                            {{ $course->grades()->count() }}
                        </div>
                        <div class="text-sm text-blue-600 font-medium">Total Grades</div>
                        <div class="text-xs text-gray-500 mt-1">Jumlah total nilai</div>
                    </div>

                    <!-- Unique Students -->
                    <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="text-2xl font-bold text-green-600">
                            {{ $course->grades()
                                ->with('student')
                                ->get()
                                ->pluck('student.student_name')
                                ->unique()
                                ->count() }}
                        </div>
                        <div class="text-sm text-green-600 font-medium">Unique Students</div>
                        <div class="text-xs text-gray-500 mt-1">Mahasiswa unik</div>
                    </div>

                    <!-- Average Grade -->
                    <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="text-2xl font-bold text-yellow-600">
                            {{ $course->grades()->count() > 0 
                                ? number_format($course->grades()->avg('final_grade'), 1) 
                                : '0.0' }}
                        </div>
                        <div class="text-sm text-yellow-600 font-medium">Avg Grade</div>
                        <div class="text-xs text-gray-500 mt-1">Rata-rata nilai</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                    <a href="{{ route('courses.edit', $course) }}" 
                       class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg text-center block transition duration-200">
                        <i class="fas fa-edit mr-2"></i>Edit Mata Kuliah
                    </a>
                    
                    <form action="{{ route('courses.destroy', $course) }}" 
                          method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg transition duration-200">
                            <i class="fas fa-trash mr-2"></i>Hapus Mata Kuliah
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
