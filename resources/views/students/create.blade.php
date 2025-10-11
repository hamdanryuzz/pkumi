@extends('layouts.app')

@section('title', 'Tambah Mahasiswa - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Tambah Mahasiswa</h2>
            <p class="text-base text-gray-500 mt-1">Buat akun mahasiswa dengan kredensial login otomatis atau manual.</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h5 class="text-lg font-bold text-gray-800">Form Tambah Mahasiswa</h5>
                
                <!-- Mode Toggle -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-700">Mode Input:</span>
                    <div class="flex bg-gray-100 p-1 rounded-lg">
                        <button type="button" id="autoModeBtn" class="px-4 py-2 text-sm font-medium rounded-md transition-colors bg-blue-500 text-white">
                            <i class="fas fa-magic mr-2"></i>Auto Generate
                        </button>
                        <button type="button" id="manualModeBtn" class="px-4 py-2 text-sm font-medium rounded-md transition-colors text-gray-600 hover:text-gray-800">
                            <i class="fas fa-edit mr-2"></i>Manual Input
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('students.store') }}" method="POST" id="studentForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="generation_mode" id="generationMode" value="auto">
                
                <!-- Basic Info - Always Visible -->
                <div class="mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 border-b pb-2">Informasi Dasar</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email (Optional)</label>
                            <input type="email" 
                                   class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('email') border-red-500 @enderror" 
                                   id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Academic Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Informasi Akademik
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Angkatan -->
                        <div>
                            <label for="year_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Angkatan <span class="text-red-500">*</span>
                            </label>
                            <select name="year_id" id="year_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Pilih Angkatan</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}" {{ old('year_id') == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('year_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kelas -->
                        <div>
                            <label for="student_class_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Kelas <span class="text-red-500">*</span>
                            </label>
                            <select name="student_class_id" id="student_class_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Pilih Kelas</option>
                            </select>
                            @error('student_class_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                    <span class="flex items-center">
                                        <span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        Active
                                    </span>
                                </option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                    <span class="flex items-center">
                                        <span class="inline-block w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                        Inactive
                                    </span>
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Status akun mahasiswa (default: Active)</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Informasi Kontak
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Telepon -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                            <input type="text" name="phone" id="phone" 
                                value="{{ old('phone') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                placeholder="Nomor telepon mahasiswa">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto Mahasiswa -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Mahasiswa
                                <span class="text-gray-500 text-xs font-normal">(Optional, Max: 2MB)</span>
                            </label>
                            <div class="relative">
                                <input type="file" name="image" id="image" accept="image/jpeg,image/jpg,image/png"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    onchange="previewImage(event)">
                            </div>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- Image Preview -->
                            <div id="imagePreviewContainer" class="mt-3 hidden">
                                <p class="text-sm text-gray-600 mb-2">Preview:</p>
                                <img id="imagePreview" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300">
                            </div>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mt-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea name="address" id="address" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                placeholder="Alamat lengkap mahasiswa">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Auto Mode Preview -->
                <div id="autoModeSection" class="mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 border-b pb-2">
                        <i class="fas fa-magic text-blue-500 mr-2"></i>Preview Auto-Generated Data
                    </h6>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Preview NIM</label>
                                <div class="px-3 py-2 bg-white border rounded-md text-sm text-gray-600" id="previewNim">
                                    <em>Pilih tahun dan kelas</em>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Preview Username</label>
                                <div class="px-3 py-2 bg-white border rounded-md text-sm text-gray-600" id="previewUsername">
                                    <em>Masukkan nama</em>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Preview Password</label>
                                <div class="flex">
                                    <div class="px-3 py-2 bg-white border rounded-l-md text-sm text-gray-600 flex-1" id="previewPassword">
                                        <em>Klik generate</em>
                                    </div>
                                    <button type="button" 
                                            class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-r-md transition"
                                            id="generatePasswordBtn">
                                        Generate
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-blue-600 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Data akan di-generate otomatis saat form disubmit
                        </p>
                    </div>
                </div>

                <!-- Manual Mode Fields -->
                <div id="manualModeSection" class="mb-6 hidden">
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 border-b pb-2">
                        <i class="fas fa-edit text-green-500 mr-2"></i>Input Manual Credentials
                    </h6>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NIM <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('nim') border-red-500 @enderror" 
                                       id="nim" name="nim" value="{{ old('nim') }}">
                                @error('nim')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('username') border-red-500 @enderror" 
                                       id="username" name="username" value="{{ old('username') }}">
                                @error('username')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                                <input type="password" 
                                       class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('password') border-red-500 @enderror" 
                                       id="password" name="password">
                                @error('password')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between items-center">
                    <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <button type="submit" id="submitBtn" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-200">
                        <i class="fas fa-magic mr-2"></i>
                        <span id="submitText">Generate & Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

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
            autoModeBtn.className = 'px-4 py-2 text-sm font-medium rounded-md transition-colors bg-blue-500 text-white';
            manualModeBtn.className = 'px-4 py-2 text-sm font-medium rounded-md transition-colors text-gray-600 hover:text-gray-800';
            
            // Show/hide sections
            autoModeSection.classList.remove('hidden');
            manualModeSection.classList.add('hidden');
            
            // Update form
            generationMode.value = 'auto';
            submitBtn.className = 'inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-200';
            submitBtn.innerHTML = '<i class="fas fa-magic mr-2"></i><span>Generate & Simpan</span>';
            
            // Clear manual fields
            document.getElementById('nim').value = '';
            document.getElementById('username').value = '';
            document.getElementById('password').value = '';
            
        } else {
            // Manual mode styling
            manualModeBtn.className = 'px-4 py-2 text-sm font-medium rounded-md transition-colors bg-green-500 text-white';
            autoModeBtn.className = 'px-4 py-2 text-sm font-medium rounded-md transition-colors text-gray-600 hover:text-gray-800';
            
            // Show/hide sections
            manualModeSection.classList.remove('hidden');
            autoModeSection.classList.add('hidden');
            
            // Update form
            generationMode.value = 'manual';
            submitBtn.className = 'inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition duration-200';
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i><span>Simpan Manual</span>';
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
            // Simple preview logic - actual generation happens server-side
            const yearText = yearSelect.options[yearSelect.selectedIndex].text;
            const yearCode = yearText.slice(-2); // ambil 2 digit terakhir tahun
            const classCode = String(this.value).padStart(2, '0');
            const preview = `${yearCode}${classCode}XXX`;
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

// Copy to clipboard function
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent || element.value;
    
    navigator.clipboard.writeText(text).then(function() {
        // Show success feedback
        const originalText = element.textContent;
        if (element.tagName === 'CODE') {
            element.textContent = 'Copied!';
            element.classList.add('text-green-600');
            setTimeout(() => {
                element.textContent = originalText;
                element.classList.remove('text-green-600');
            }, 2000);
        }
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
<script>
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
<script>
// Update status indicator
document.getElementById('status').addEventListener('change', function() {
    const statusSelect = this;
    const statusValue = statusSelect.value;
    
    // Update select styling based on status
    if (statusValue === 'active') {
        statusSelect.classList.remove('border-red-300');
        statusSelect.classList.add('border-green-300');
    } else {
        statusSelect.classList.remove('border-green-300');
        statusSelect.classList.add('border-red-300');
    }
});

// Trigger on page load
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    statusSelect.dispatchEvent(new Event('change'));
});
</script>
@endsection