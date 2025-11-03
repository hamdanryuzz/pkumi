@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center">
                <a href="{{ route('courses.index') }}" 
                   class="text-gray-600 hover:text-gray-800 mr-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Detail Mata Kuliah</h1>
                    <p class="text-gray-600 mt-1">Informasi lengkap mata kuliah</p>
                </div>
            </div>
            <a href="{{ route('courses.edit', $course->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg shadow-sm transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Mata Kuliah
            </a>
        </div>
    </div>

    <!-- Course Info Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $course->name }}</h2>
                    <div class="flex items-center gap-4 mt-2">
                        <span class="px-3 py-1 text-sm font-semibold text-blue-700 bg-blue-200 rounded-full">
                            {{ $course->code }}
                        </span>
                        @if($course->sks)
                        <span class="text-gray-600 text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                            {{ $course->sks }} SKS
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Course Name -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Nama Mata Kuliah</dt>
                    <dd class="text-base text-gray-900 font-medium">{{ $course->name }}</dd>
                </div>

                <!-- Course Code -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Kode Mata Kuliah</dt>
                    <dd class="text-base text-gray-900 font-medium">{{ $course->code }}</dd>
                </div>

                <!-- SKS -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">SKS</dt>
                    <dd class="text-base text-gray-900 font-medium">
                        {{ $course->sks ?? '-' }}
                    </dd>
                </div>

                <!-- Class Pattern -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Pola Kelas</dt>
                    <dd class="text-base text-gray-900 font-medium">
                        {{ $course->class_pattern ?? '-' }}
                    </dd>
                </div>

                <!-- Created At -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Tanggal Dibuat</dt>
                    <dd class="text-base text-gray-900 font-medium">
                        {{ $course->created_at->format('d F Y, H:i') }}
                    </dd>
                </div>

                <!-- Updated At -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Terakhir Diperbarui</dt>
                    <dd class="text-base text-gray-900 font-medium">
                        {{ $course->updated_at->format('d F Y, H:i') }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Connected Classes Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Kelas Terhubung</h3>
            <p class="text-sm text-gray-600 mt-1">Daftar kelas yang menggunakan mata kuliah ini</p>
        </div>

        <div class="p-6">
            @if($course->studentClasses->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($course->studentClasses as $class)
                        <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $class->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $class->year->name ?? 'Tidak ada angkatan' }}
                                    </p>
                                </div>
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">
                                    Aktif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <p class="text-gray-500 font-medium">Belum ada kelas yang terhubung</p>
                    <a href="{{ route('courses.edit', $course->id) }}" 
                       class="inline-block mt-4 text-blue-600 hover:text-blue-800 font-medium">
                        Hubungkan dengan kelas →
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Button -->
    <div class="mt-6 p-6 bg-red-50 border border-red-200 rounded-lg">
        <h3 class="text-lg font-semibold text-red-800 mb-2">Zona Bahaya</h3>
        <p class="text-sm text-red-700 mb-4">
            Menghapus mata kuliah akan menghapus semua data terkait. Aksi ini tidak dapat dibatalkan.
        </p>
        <form action="{{ route('courses.destroy', $course->id) }}" 
              method="POST" 
              onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini? Semua data terkait akan hilang.')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-sm transition">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus Mata Kuliah
            </button>
        </form>
    </div>
</div>
@endsection
