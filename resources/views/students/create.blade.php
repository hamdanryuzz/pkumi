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

            <form action="{{ route('students.store') }}" method="POST" id="studentForm">
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
                <div class="mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 border-b pb-2">Informasi Akademik</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="year_id" class="block text-sm font-medium text-gray-700 mb-1">Angkatan <span class="text-red-500">*</span></label>
                            <select class="form-select block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('year_id') border-red-500 @enderror" 
                                    id="year_id" name="year_id" required>
                                <option value="">Pilih Angkatan</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}" {{ old('year_id') == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('year_id')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="student_class_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas <span class="text-red-500">*</span></label>
                            <select class="form-select block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('student_class_id') border-red-500 @enderror" 
                                    id="student_class_id" name="student_class_id" required disabled>
                                <option value="">Pilih Kelas</option>
                            </select>
                            @error('student_class_id')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 border-b pb-2">Informasi Kontak</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                            <input type="text" 
                                   class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('phone') border-red-500 @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <input type="text" 
                                   class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('address') border-red-500 @enderror" 
                                   id="address" name="address" value="{{ old('address') }}">
                            @error('address')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
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
@endsection