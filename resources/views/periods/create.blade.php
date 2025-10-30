@extends('layouts.app')

@section('title', 'Kelola Tahun Ajaran - Sistem Penilaian PKUMI')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Buat Tahun Ajaran Baru</h1>
                <p class="mt-2 text-sm text-gray-600">Pilih metode pembuatan tahun ajaran yang sesuai dengan kebutuhan</p>
            </div>
            <a href="{{ route('periods.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition duration-150 ease-in-out">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-red-800 font-semibold mb-2">Terdapat beberapa kesalahan:</h3>
                    <ul class="list-disc list-inside text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Method Selection Tabs -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="switchTab('manual')" 
                        id="manual-tab"
                        class="tab-button flex-1 py-4 px-6 text-center font-semibold text-gray-600 border-b-2 border-transparent hover:text-indigo-600 hover:border-indigo-300 transition-colors duration-200">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Manual
                    </div>
                    <p class="text-xs mt-1 text-gray-500">Buat tahun ajaran manual tanpa data tambahan</p>
                </button>
                
                <button onclick="switchTab('bulk')" 
                        id="bulk-tab"
                        class="tab-button flex-1 py-4 px-6 text-center font-semibold text-indigo-600 border-b-2 border-indigo-600 hover:text-indigo-600 transition-colors duration-200">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                        </svg>
                        Bulk Store (Otomatis)
                    </div>
                    <p class="text-xs mt-1 text-gray-500">Buat tahun ajaran dengan angkatan, semester, dan kelas otomatis</p>
                </button>
            </nav>
        </div>

        <!-- Manual Form -->
        <div id="manual-form" class="tab-content hidden p-8">
            <div class="max-w-2xl mx-auto">
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-blue-700 text-sm">Gunakan form ini untuk membuat tahun ajaran secara manual tanpa data tambahan.</p>
                    </div>
                </div>

                <form action="{{ route('periods.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Name Field -->
                    <div>
                        <label for="manual_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Tahun Ajaran <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="manual_name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                               placeholder="Contoh: 2024/2025"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Code Field -->
                    <div>
                        <label for="manual_code" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kode Tahun Ajaran <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="manual_code" 
                               name="code" 
                               value="{{ old('code') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                               placeholder="Contoh: TA2024-2025"
                               required>
                        <p class="mt-1 text-xs text-gray-500">Kode unik untuk mengidentifikasi tahun ajaran ini</p>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Field -->
                    <div>
                        <label for="manual_status" class="block text-sm font-semibold text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="manual_status" 
                                name="status" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                required>
                            <option value="">-- Pilih Status --</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('periods.index') }}" 
                           class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition-colors duration-200">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Tahun Ajaran
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bulk Store Form -->
        <div id="bulk-form" class="tab-content p-8">
            <div class="max-w-2xl mx-auto">
                <div class="mb-6 bg-indigo-50 border-l-4 border-indigo-400 p-4 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-indigo-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-indigo-700">
                            <p class="font-semibold mb-1">Bulk store akan otomatis membuat:</p>
                            <ul class="list-disc list-inside space-y-1 ml-2">
                                <li>2 Angkatan (Ganjil & Genap)</li>
                                <li>2 Semester (Ganjil & Genap)</li>
                                <li>8 Kelas (4 kelas per angkatan: S2 PKU A, S2 PKU B, S2 PKUP, S3 PKU)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <form action="{{ route('periods.bulk') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Name Field -->
                    <div>
                        <label for="bulk_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Tahun Ajaran <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="bulk_name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                               placeholder="Contoh: 2024/2025"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Code Field -->
                    <div>
                        <label for="bulk_code" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kode Tahun Ajaran <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="bulk_code" 
                               name="code" 
                               value="{{ old('code') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                               placeholder="Contoh: 2024-2025"
                               required>
                        <p class="mt-1 text-xs text-gray-500">Kode unik untuk mengidentifikasi tahun ajaran ini</p>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Field -->
                    <div>
                        <label for="bulk_status" class="block text-sm font-semibold text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="bulk_status" 
                                name="status" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                                required>
                            <option value="">-- Pilih Status --</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preview Section -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Preview Data yang Akan Dibuat:</h4>
                        <div class="space-y-3 text-sm text-gray-600">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <strong>2 Angkatan:</strong>
                                    <ul class="ml-4 mt-1 space-y-0.5">
                                        <li>• [Nama Tahun Ajaran] - Ganjil</li>
                                        <li>• [Nama Tahun Ajaran] - Genap</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <strong>2 Semester:</strong>
                                    <ul class="ml-4 mt-1 space-y-0.5">
                                        <li>• Semester Ganjil [Nama Tahun Ajaran]</li>
                                        <li>• Semester Genap [Nama Tahun Ajaran]</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <strong>4 Kelas (per angkatan):</strong>
                                    <ul class="ml-4 mt-1 space-y-0.5">
                                        <li>• S2 PKU A</li>
                                        <li>• S2 PKU B</li>
                                        <li>• S2 PKUP</li>
                                        <li>• S3 PKU</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('periods.index') }}" 
                           class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition-colors duration-200">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Buat Tahun Ajaran & Data Terkait
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Reset all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('text-indigo-600', 'border-indigo-600');
        button.classList.add('text-gray-600', 'border-transparent');
    });
    
    // Show selected content and highlight tab
    if (tab === 'manual') {
        document.getElementById('manual-form').classList.remove('hidden');
        document.getElementById('manual-tab').classList.add('text-indigo-600', 'border-indigo-600');
        document.getElementById('manual-tab').classList.remove('text-gray-600', 'border-transparent');
    } else {
        document.getElementById('bulk-form').classList.remove('hidden');
        document.getElementById('bulk-tab').classList.add('text-indigo-600', 'border-indigo-600');
        document.getElementById('bulk-tab').classList.remove('text-gray-600', 'border-transparent');
    }
}

// Set default tab on page load
document.addEventListener('DOMContentLoaded', function() {
    switchTab('bulk'); // Default to bulk form
});
</script>
@endsection
