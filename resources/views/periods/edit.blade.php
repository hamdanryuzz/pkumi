@extends('layouts.app')

@section('title', 'Edit Tahun Ajaran - Sistem Penilaian PKUMI')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('periods.index') }}" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-900 text-sm font-medium transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">Edit Tahun Ajaran</h2>
            <p class="mt-1 text-sm text-gray-500">Perbarui informasi periode akademik</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-sm">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
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

        <!-- Form Card -->
        <div class="bg-white rounded-lg border border-gray-200">
            <form action="{{ route('periods.update', $period) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama Tahun Ajaran -->
                <div>
                    <label for="name" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                        Nama Tahun Ajaran <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $period->name) }}"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors duration-200 @error('name') border-red-500 @enderror"
                           placeholder="Contoh: Tahun Ajaran 2024/2025"
                           required>
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kode Tahun Ajaran -->
                <div>
                    <label for="code" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                        Kode Tahun Ajaran <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="code" 
                           id="code" 
                           value="{{ old('code', $period->code) }}"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-colors duration-200 @error('code') border-red-500 @enderror"
                           placeholder="Contoh: 2024-2025"
                           required>
                    @error('code')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Kode unik untuk mengidentifikasi tahun ajaran ini</p>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            id="status" 
                            class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white transition-colors duration-200 @error('status') border-red-500 @enderror"
                            required>
                        <option value="draft" {{ old('status', $period->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ old('status', $period->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ old('status', $period->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Metadata -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Informasi Tambahan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Dibuat:</span> 
                            {{ $period->created_at->format('d M Y, H:i') }}
                        </div>
                        <div>
                            <span class="font-medium">Terakhir Diupdate:</span> 
                            {{ $period->updated_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row sm:justify-end gap-2">
                    <a href="{{ route('periods.index') }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Tahun Ajaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection