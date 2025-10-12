@extends('layouts.app')

@section('title', 'Tambah Angkatan - Sistem Penilaian PKUMI')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">Tambah Angkatan</h2>
                    <p class="mt-1 text-sm text-gray-500">Buat angkatan baru untuk sistem</p>
                </div>
                <a href="{{ route('years.index') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
        <!-- Form Card -->
        <div class="bg-white rounded-lg border border-gray-200">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Form Tambah Angkatan</h3>
                        <p class="text-sm text-gray-500 mt-1">Lengkapi informasi angkatan yang akan ditambahkan</p>
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
                            <label for="name" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                                Nama Angkatan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}"
                                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 @error('name') border-red-500 @enderror"
                                    placeholder="Contoh: 2024, Angkatan 2024, etc."
                                    required
                                    autocomplete="off"
                                >
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Masukkan nama angkatan yang unik dan mudah diidentifikasi
                            </p>
                        </div>

                        <!-- Preview Section -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Preview</h4>
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900" id="preview-name">Nama angkatan akan muncul di sini</div>
                                    <div class="text-xs text-gray-500">Angkatan baru</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex flex-col sm:flex-row sm:justify-end gap-2">
                        <a href="{{ route('years.index') }}" 
                           class="inline-flex items-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batal
                        </a>
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Angkatan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Helper Tips -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Tips Pengisian</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Gunakan format yang konsisten, misalnya "2024" atau "Angkatan 2024"</li>
                            <li>Pastikan nama angkatan unik dan mudah diidentifikasi</li>
                            <li>Angkatan akan digunakan untuk pengelompokan data mahasiswa dan nilai</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update preview when input changes
document.getElementById('name').addEventListener('input', function() {
    const value = this.value || 'Nama angkatan akan muncul di sini';
    document.getElementById('preview-name').textContent = value;
});
</script>
@endsection