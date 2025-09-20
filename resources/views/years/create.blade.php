@extends('layouts.app')

@section('title', 'Tambah Tahun - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Tambah Tahun Akademik</h2>
                <p class="text-base text-gray-500 mt-1">Buat tahun akademik baru untuk sistem</p>
            </div>
            <a href="{{ route('years.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 transition duration-200 inline-flex items-center shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                    <i class="fas fa-calendar-plus text-blue-600 text-lg"></i>
                </div>
                <div>
                    <h5 class="text-lg font-bold text-gray-800">Form Tambah Tahun</h5>
                    <p class="text-sm text-gray-600 mt-1">Lengkapi informasi tahun akademik yang akan ditambahkan</p>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <div class="p-6">
            <form action="{{ route('years.store') }}" method="POST" id="yearForm">
                @csrf
                
                <div class="space-y-6">
                    <!-- Year Name Input -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1"></i>Nama Tahun Akademik
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('name') border-red-500 @enderror"
                                placeholder="Contoh: 2024/2025, Tahun 2024, etc."
                                required
                                autocomplete="off"
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Masukkan nama tahun akademik yang unik dan mudah diidentifikasi
                        </p>
                    </div>

                    <!-- Preview Section -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h6 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-eye mr-1"></i>Preview
                        </h6>
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900" id="preview-name">Nama tahun akan muncul di sini</div>
                                <div class="text-xs text-gray-500">Tahun akademik baru</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex flex-col sm:flex-row gap-3 sm:justify-end">
                    <a href="{{ route('years.index') }}" class="w-full sm:w-auto bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 transition duration-200 inline-flex items-center justify-center border border-gray-300 shadow-sm">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 inline-flex items-center justify-center shadow-sm">
                        <i class="fas fa-save mr-2"></i>Simpan Tahun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Helper Tips -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-lightbulb text-blue-400 text-lg"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Tips Pengisian</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Gunakan format yang konsisten, misalnya "2024/2025" atau "Tahun 2024"</li>
                        <li>Pastikan nama tahun unik dan mudah diidentifikasi</li>
                        <li>Tahun akan digunakan untuk pengelompokan data mahasiswa dan nilai</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
