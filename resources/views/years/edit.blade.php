@extends('layouts.app')

@section('title', 'Edit Tahun - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Edit Tahun Akademik</h2>
                <p class="text-base text-gray-500 mt-1">Perbarui informasi tahun akademik "{{ $year->name }}"</p>
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
                <div class="h-10 w-10 rounded-lg bg-yellow-100 flex items-center justify-center mr-4">
                    <i class="fas fa-edit text-yellow-600 text-lg"></i>
                </div>
                <div>
                    <h5 class="text-lg font-bold text-gray-800">Form Edit Tahun</h5>
                    <p class="text-sm text-gray-600 mt-1">Perbarui informasi tahun akademik yang sudah ada</p>
                </div>
            </div>
        </div>

        <!-- Current Data Info -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center">
                <div class="flex items-center flex-1">
                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                        <span class="text-blue-600 font-semibold text-sm">{{ substr($year->name, -2) }}</span>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">Data Saat Ini: {{ $year->name }}</div>
                        <div class="text-xs text-gray-500">
                            <i class="fas fa-calendar mr-1"></i>Dibuat: {{ $year->created_at->format('d M Y, H:i') }}
                            @if($year->updated_at != $year->created_at)
                                | <i class="fas fa-edit mr-1"></i>Terakhir diubah: {{ $year->updated_at->format('d M Y, H:i') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <div class="p-6">
            <form action="{{ route('years.update', $year->id) }}" method="POST" id="yearEditForm">
                @csrf
                @method('PUT')
                
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
                                value="{{ old('name', $year->name) }}"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200 @error('name') border-red-500 @enderror"
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
                            Pastikan nama tahun akademik unik dan mudah diidentifikasi
                        </p>
                    </div>

                    <!-- Preview Section -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h6 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-eye mr-1"></i>Preview Perubahan
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Before -->
                            <div class="bg-white p-3 rounded-lg border border-gray-200">
                                <div class="text-xs text-gray-500 mb-2">Sebelum</div>
                                <div class="flex items-center">
                                    <div class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center mr-2">
                                        <span class="text-gray-600 font-semibold text-xs">{{ substr($year->name, -2) }}</span>
                                    </div>
                                    <div class="text-sm text-gray-700">{{ $year->name }}</div>
                                </div>
                            </div>
                            <!-- After -->
                            <div class="bg-white p-3 rounded-lg border border-yellow-300">
                                <div class="text-xs text-yellow-600 mb-2">Sesudah</div>
                                <div class="flex items-center">
                                    <div class="h-6 w-6 rounded-full bg-yellow-100 flex items-center justify-center mr-2">
                                        <span class="text-yellow-600 font-semibold text-xs" id="preview-year">{{ substr(old('name', $year->name), -2) }}</span>
                                    </div>
                                    <div class="text-sm text-gray-900" id="preview-name">{{ old('name', $year->name) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex flex-col sm:flex-row gap-3 sm:justify-end">
                    <a href="{{ route('years.index') }}" class="w-full sm:w-auto bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 transition duration-200 inline-flex items-center justify-center border border-gray-300 shadow-sm">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-yellow-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-200 inline-flex items-center justify-center shadow-sm">
                        <i class="fas fa-save mr-2"></i>Update Tahun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Warning Notice -->
    <div class="mt-6 bg-amber-50 border border-amber-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-amber-400 text-lg"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-amber-800">Perhatian</h3>
                <div class="mt-2 text-sm text-amber-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Perubahan nama tahun akan mempengaruhi semua data yang terkait</li>
                        <li>Pastikan nama baru tidak duplikat dengan tahun yang sudah ada</li>
                        <li>Data mahasiswa dan nilai yang sudah ada tetap akan terhubung dengan tahun ini</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
