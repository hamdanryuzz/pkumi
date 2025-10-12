@extends('layouts.app')

@section('title', 'Edit Angkatan - Sistem Penilaian PKUMI')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">Edit Angkatan</h2>
                    <p class="mt-1 text-sm text-gray-500">Perbarui informasi angkatan "{{ $year->name }}"</p>
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
                    <div class="h-10 w-10 rounded-lg bg-yellow-100 flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Form Edit Angkatan</h3>
                        <p class="text-sm text-gray-500 mt-1">Perbarui informasi angkatan yang sudah ada</p>
                    </div>
                </div>
            </div>

            <!-- Current Data Info -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                        <span class="text-blue-600 font-semibold text-sm">{{ substr($year->name, -2) }}</span>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">Data Saat Ini: {{ $year->name }}</div>
                        <div class="text-xs text-gray-500">
                            Dibuat: {{ $year->created_at->format('d M Y, H:i') }}
                            @if($year->updated_at != $year->created_at)
                                | Terakhir diubah: {{ $year->updated_at->format('d M Y, H:i') }}
                            @endif
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
                            <label for="name" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                                Nama Angkatan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', $year->name) }}"
                                    class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500 transition duration-200 @error('name') border-red-500 @enderror"
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
                                Pastikan nama angkatan unik dan mudah diidentifikasi
                            </p>
                        </div>

                        <!-- Preview Section -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Preview Perubahan</h4>
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
                    <div class="mt-8 flex flex-col sm:flex-row sm:justify-end gap-2">
                        <a href="{{ route('years.index') }}" 
                           class="inline-flex items-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Angkatan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Warning Notice -->
        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-amber-800">Perhatian</h4>
                    <div class="mt-2 text-sm text-amber-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Perubahan nama angkatan akan mempengaruhi semua data yang terkait</li>
                            <li>Pastikan nama baru tidak duplikat dengan angkatan yang sudah ada</li>
                            <li>Data mahasiswa dan nilai yang sudah ada tetap akan terhubung dengan angkatan ini</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('name').addEventListener('input', function() {
    const value = this.value || 'XX';
    document.getElementById('preview-year').textContent = value.slice(-2);
    document.getElementById('preview-name').textContent = value;
});
</script>
@endsection