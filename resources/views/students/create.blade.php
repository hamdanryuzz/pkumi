@extends('layouts.app')

@section('title', 'Tambah Mahasiswa - Sistem Penilaian PKUMI')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">Tambah Mahasiswa</h2>
                    <p class="mt-1 text-sm text-gray-500">Buat akun mahasiswa dengan kredensial login otomatis atau manual.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
        <!-- Success / Error Messages -->
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form Tambah Mahasiswa -->
        <form action="{{ route('students.store') }}" method="POST" id="studentForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="generation_mode" id="generationMode" value="auto">

            <!-- Mode Toggle -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Form Tambah Mahasiswa</h3>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs font-medium text-gray-700">Mode Input:</span>
                        <div class="flex bg-gray-100 rounded-lg p-1 text-xs">
                            <button type="button" id="autoModeBtn" class="px-3 py-1.5 rounded-md transition-colors bg-blue-500 text-white">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Auto Generate
                            </button>
                            <button type="button" id="manualModeBtn" class="px-3 py-1.5 rounded-md transition-colors text-gray-600 hover:text-gray-800">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Manual Input
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Basic Info -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Dasar
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 @error('name') border-red-500 @enderror"
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">Email (Optional)</label>
                        <input type="email" 
                               class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 @error('email') border-red-500 @enderror"
                               id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Academic Info -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Informasi Akademik
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Angkatan -->
                    <div>
                        <label for="year_id" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                            Angkatan <span class="text-red-500">*</span>
                        </label>
                        <select name="year_id" id="year_id" required
                                class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white transition-all duration-200">
                            <option value="">Pilih Angkatan</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}" {{ old('year_id') == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('year_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label for="student_class_id" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <select name="student_class_id" id="student_class_id" required
                                class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white transition-all duration-200">
                            <option value="">Pilih Kelas</option>
                        </select>
                        @error('student_class_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                                class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white transition-all duration-200">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Status akun mahasiswa (default: Active)</p>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Informasi Kontak
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Telepon -->
                    <div>
                        <label for="phone" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">Telepon</label>
                        <input type="text" name="phone" id="phone" 
                            value="{{ old('phone') }}"
                            class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200"
                            placeholder="Nomor telepon mahasiswa">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto Mahasiswa -->
                    <div>
                        <label for="image" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                            Foto Mahasiswa
                        </label>
                        <div class="relative">
                            <input type="file" name="image" id="image" accept="image/jpeg,image/jpg,image/png"
                                class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100"
                                onchange="previewImage(event)">
                        </div>
                        @error('image')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- Image Preview -->
                        <div id="imagePreviewContainer" class="mt-3 hidden">
                            <p class="text-xs text-gray-500 mb-2">Preview:</p>
                            <img id="imagePreview" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300">
                        </div>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mt-4">
                    <label for="address" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">Alamat</label>
                    <textarea name="address" id="address" rows="3"
                            class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200"
                            placeholder="Alamat lengkap mahasiswa">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Auto Mode Preview -->
            <div id="autoModeSection" class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Preview Auto-Generated Data
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">Preview NIM</label>
                        <div class="px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-600" id="previewNim">
                            <em>Pilih tahun dan kelas</em>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">Preview Username</label>
                        <div class="px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-600" id="previewUsername">
                            <em>Masukkan nama</em>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">Preview Password</label>
                        <div class="flex">
                            <div class="px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-l-lg text-sm text-gray-600 flex-1" id="previewPassword">
                                <em>Klik generate</em>
                            </div>
                            <button type="button" 
                                    class="px-3 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-r-lg transition"
                                    id="generatePasswordBtn">
                                Generate
                            </button>
                        </div>
                    </div>
                </div>
                <p class="mt-3 text-xs text-gray-500">
                    Data akan di-generate otomatis saat form disubmit.
                </p>
            </div>

            <!-- Manual Mode Fields -->
            <div id="manualModeSection" class="hidden">
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Input Manual Credentials
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="nim" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                                NIM <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 @error('nim') border-red-500 @enderror"
                                   id="nim" name="nim" value="{{ old('nim') }}">
                            @error('nim')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="username" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 @error('username') border-red-500 @enderror"
                                   id="username" name="username" value="{{ old('username') }}">
                            @error('username')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 @error('password') border-red-500 @enderror"
                                   id="password" name="password">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-gray-200 bg-white">
                <a href="{{ route('students.index') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                <button type="submit" id="submitBtn" 
                        class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span id="submitText">Generate & Simpan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const autoModeBtn = document.getElementById('autoModeBtn');
    const manualModeBtn = document.getElementById('manualModeBtn');
    const autoModeSection = document.getElementById('autoModeSection');
    const manualModeSection = document.getElementById('manualModeSection');
    const generationMode = document.getElementById('generationMode');
    const submitBtn = document.getElementById('submitBtn');
    
    const nameInput = document.getElementById('name');
    const yearSelect = document.getElementById('year_id');
    const classSelect = document.getElementById('student_class_id');
    const previewNim = document.getElementById('previewNim');
    const previewUsername = document.getElementById('previewUsername');
    const previewPassword = document.getElementById('previewPassword');
    const generatePasswordBtn = document.getElementById('generatePasswordBtn');
    
    // Mode switching functions
    autoModeBtn.addEventListener('click', function() {
        setMode('auto');
    });
    
    manualModeBtn.addEventListener('click', function() {
        setMode('manual');
    });
    
    function setMode(mode) {
        if (mode === 'auto') {
            // Auto mode styling
            autoModeBtn.classList.add('bg-blue-500', 'text-white');
            manualModeBtn.classList.remove('bg-green-500', 'text-white');
            manualModeBtn.classList.add('text-gray-600', 'hover:text-gray-800');
            
            // Show/hide sections
            autoModeSection.classList.remove('hidden');
            manualModeSection.classList.add('hidden');
            
            // Update form
            generationMode.value = 'auto';
            submitBtn.className = 'inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200';
            submitBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span id="submitText">Generate & Simpan</span>
            `;
            
            // Clear manual fields
            document.getElementById('nim').value = '';
            document.getElementById('username').value = '';
            document.getElementById('password').value = '';
            
        } else {
            // Manual mode styling
            manualModeBtn.classList.add('bg-green-500', 'text-white');
            manualModeBtn.classList.remove('text-gray-600', 'hover:text-gray-800');
            autoModeBtn.classList.remove('bg-blue-500', 'text-white');
            autoModeBtn.classList.add('text-gray-600', 'hover:text-gray-800');
            
            // Show/hide sections
            manualModeSection.classList.remove('hidden');
            autoModeSection.classList.add('hidden');
            
            // Update form
            generationMode.value = 'manual';
            submitBtn.className = 'inline-flex items-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all duration-200';
            submitBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span id="submitText">Simpan Manual</span>
            `;
        }
    }
    
    // Auto preview functions
    nameInput.addEventListener('input', function() {
        if (generationMode.value === 'auto' && this.value.trim()) {
            const username = this.value.toLowerCase().replace(/\s+/g, '.');
            previewUsername.innerHTML = `<code class="bg-blue-50 px-2 py-1 rounded text-blue-700">${username}</code>`;
        } else {
            previewUsername.innerHTML = '<em>Masukkan nama</em>';
        }
    });
    
    // Year change handler
    yearSelect.addEventListener('change', function() {
        const yearId = this.value;
        classSelect.innerHTML = '<option value="">Pilih Kelas</option>';
        classSelect.disabled = !yearId;
        
        // Reset preview NIM
        previewNim.innerHTML = '<em>Pilih tahun dan kelas</em>';
        
        if (yearId) {
            // Fetch period data untuk preview NIM
            fetch(`{{ url('/api/year-period') }}/${yearId}`)
                .then(response => response.json())
                .then(periodData => {
                    // Simpan period data untuk preview NIM
                    yearSelect.dataset.periodName = periodData.period_name || periodData.year_name;
                    yearSelect.dataset.yearName = periodData.year_name;
                });
            
            // Fetch student classes
            fetch(`{{ url('/api/student-classes') }}/${yearId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.length === 0) {
                        classSelect.innerHTML = '<option value="">Tidak ada kelas untuk tahun ini</option>';
                    } else {
                        data.forEach(studentClass => {
                            const option = document.createElement('option');
                            option.value = studentClass.id;
                            option.textContent = studentClass.name;
                            classSelect.appendChild(option);
                        });
                    }
                    
                    // Restore selected value if exists
                    const oldClassId = '{{ old('student_class_id') }}';
                    if (oldClassId) {
                        classSelect.value = oldClassId;
                        classSelect.dispatchEvent(new Event('change'));
                    }
                })
                .catch(error => {
                    console.error('Error fetching student classes:', error);
                    classSelect.innerHTML = '<option value="">Error loading classes</option>';
                });
        }
    });
    
    // Class change handler for NIM preview
    classSelect.addEventListener('change', function() {
        if (generationMode.value === 'auto' && yearSelect.value && this.value) {
            // Ambil period name dari dataset
            const periodName = yearSelect.dataset.periodName || '';
            const yearName = yearSelect.dataset.yearName || '';
            const classText = this.options[this.selectedIndex].text;
            
            // Ekstrak tahun dari period name (ambil 2 digit terakhir)
            const yearMatch = periodName.match(/(\d{4})/);
            const yearCode = yearMatch ? yearMatch[1].substr(-2) : 'YY';
            
            // Tentukan kode angkatan dari year name (ganjil=1, genap=2)
            const angkatanMatch = yearName.match(/(\d+)/);
            const angkatanNum = angkatanMatch ? parseInt(angkatanMatch[1]) : 0;
            const angkatanCode = (angkatanNum % 2 === 0) ? '2' : '1';
            
            // Tentukan kode prodi dari nama kelas
            let prodiCode = '00';
            const upperClassName = classText.toUpperCase();
            
            if (/S2\s*PKU\s*[ABC]?$/i.test(upperClassName)) {
                prodiCode = '01'; // S2 PKU A/B/C
            } else if (/S2\s*PKUP/i.test(upperClassName)) {
                prodiCode = '02'; // S2 PKUP
            } else if (/S3\s*PKU/i.test(upperClassName)) {
                prodiCode = '03'; // S3 PKU
            }
            
            const preview = `${yearCode}${angkatanCode}${prodiCode}XXXX`;
            previewNim.innerHTML = `<code class="bg-green-50 px-2 py-1 rounded text-green-700">${preview}</code>`;
        } else {
            previewNim.innerHTML = '<em>Pilih tahun dan kelas</em>';
        }
    });
    
    // Password generation
    generatePasswordBtn.addEventListener('click', function() {
        const password = generateRandomPassword();
        previewPassword.innerHTML = `<code class="bg-green-50 px-2 py-1 rounded text-green-700">${password}</code>`;
    });
    
    function generateRandomPassword() {
        const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const lowercase = 'abcdefghijklmnopqrstuvwxyz';
        const numbers = '0123456789';
        const symbols = '!@#$%^&*';
        
        let password = '';
        password += uppercase.charAt(Math.floor(Math.random() * uppercase.length));
        password += lowercase.charAt(Math.floor(Math.random() * lowercase.length));
        password += numbers.charAt(Math.floor(Math.random() * numbers.length));
        password += symbols.charAt(Math.floor(Math.random() * symbols.length));
        
        const allChars = uppercase + lowercase + numbers + symbols;
        for (let i = 4; i < 8; i++) {
            password += allChars.charAt(Math.floor(Math.random() * allChars.length));
        }
        
        return password.split('').sort(() => Math.random() - 0.5).join('');
    }
    
    // Initialize with auto mode
    setMode('auto');
    
    // Load classes if year is already selected (for validation errors)
    if (yearSelect.value) {
        yearSelect.dispatchEvent(new Event('change'));
    }
    
    // Trigger username preview if name already filled (for validation errors)
    if (nameInput.value) {
        nameInput.dispatchEvent(new Event('input'));
    }
});

// Preview Image Function
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewContainer = document.getElementById('imagePreviewContainer');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        previewContainer.classList.add('hidden');
    }
}
</script>
@endsection