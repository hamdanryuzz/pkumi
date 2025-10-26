@extends('layouts.app')

@section('title', 'Tambah Angkatan - Sistem Penilaian PKUMI')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('years.index') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white shadow-sm hover:shadow-md transition-all duration-200 group">
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-gray-900 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tambah Angkatan Baru</h1>
                    <p class="text-gray-600 mt-1">Buat angkatan baru untuk sistem</p>
                </div>
            </div>
            <p class="text-sm text-gray-500 ml-13">Lengkapi informasi angkatan yang akan ditambahkan</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="p-8">
                <!-- Error Messages -->
                @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-red-800 mb-2">Terdapat kesalahan:</h3>
                            <ul class="text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Form -->
                <form action="{{ route('years.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Tahun Ajaran (Period) -->
                    <div class="space-y-2">
                        <label for="period_id" class="block text-sm font-semibold text-gray-700">
                            Tahun Ajaran
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="period_id" id="period_id" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('period_id') border-red-300 bg-red-50 @enderror"
                                required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @foreach($periods as $period)
                            <option value="{{ $period->id }}" {{ old('period_id') == $period->id ? 'selected' : '' }}>
                                {{ $period->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('period_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Pilih tahun ajaran untuk angkatan ini</p>
                    </div>

                    <!-- Nomor Angkatan -->
                    <div class="space-y-2">
                        <label for="year_number" class="block text-sm font-semibold text-gray-700">
                            Nomor Angkatan
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="year_number" 
                               id="year_number" 
                               value="{{ old('year_number') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('year_number') border-red-300 bg-red-50 @enderror" 
                               placeholder="Contoh: 1, 2, 3"
                               required>
                        @error('year_number')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Masukkan nomor angkatan (akan menjadi "Angkatan [nomor] [semester]")</p>
                    </div>

                    <!-- Semester -->
                    <div class="space-y-2">
                        <label for="semester" class="block text-sm font-semibold text-gray-700">
                            Semester
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="semester" id="semester" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('semester') border-red-300 bg-red-50 @enderror"
                                required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                        @error('semester')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Pilih semester (Ganjil atau Genap)</p>
                    </div>

                    <!-- Preview -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <p class="text-sm font-semibold text-blue-800 mb-1">Preview Nama Angkatan:</p>
                        <p class="text-lg font-bold text-blue-900" id="preview">Angkatan - -</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                        <button type="submit" 
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Angkatan
                            </span>
                        </button>
                        <a href="{{ route('years.index') }}" 
                           class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-all duration-200 text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Helper Info -->
        <div class="mt-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 border border-blue-100">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Informasi Penting</h3>
                    <ul class="text-sm text-gray-700 space-y-1">
                        <li>• Nama angkatan akan dibuat otomatis dengan format: <strong>Angkatan [nomor] [semester]</strong></li>
                        <li>• Contoh: Jika nomor = "1" dan semester = "Ganjil", maka nama = <strong>"Angkatan 1 Ganjil"</strong></li>
                        <li>• Pastikan memilih tahun ajaran yang sesuai</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Live preview nama angkatan
    document.addEventListener('DOMContentLoaded', function() {
        const yearNumberInput = document.getElementById('year_number');
        const semesterSelect = document.getElementById('semester');
        const preview = document.getElementById('preview');

        function updatePreview() {
            const yearNumber = yearNumberInput.value.trim() || '-';
            const semester = semesterSelect.value || '-';
            preview.textContent = `Angkatan ${yearNumber} ${semester}`;
        }

        yearNumberInput.addEventListener('input', updatePreview);
        semesterSelect.addEventListener('change', updatePreview);
    });
</script>
@endsection
