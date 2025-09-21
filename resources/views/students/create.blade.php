@extends('layouts.app')

@section('title', 'Generate Account Mahasiswa - Sistem Penilaian PKUMI')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                <li class="inline-flex items-center">
                                    <a href="{{ route('students.index') }}" class="text-gray-700 hover:text-blue-600 inline-flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                        </svg>
                                        Mahasiswa
                                    </a>
                                </li>
                                <li aria-current="page">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="ml-1 text-gray-500 md:ml-2">Generate Account</span>
                                    </div>
                                </li>
                            </ol>
                        </nav>
                        <h1 class="text-3xl font-bold text-gray-900 mt-2">Generate Account Mahasiswa</h1>
                        <p class="mt-2 text-gray-600">Buat akun mahasiswa dengan username dan password yang digenerate otomatis.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Error Messages -->
        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Generated Credentials Alert -->
        @if(session('student_generated'))
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-lg font-medium text-blue-900">Account berhasil digenerate!</h3>
                    <div class="mt-4 bg-white rounded-lg p-4 border border-blue-200">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama</dt>
                                <dd class="text-sm text-gray-900">{{ session('student_generated')['name'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">NIM</dt>
                                <dd class="text-sm text-gray-900">{{ session('student_generated')['nim'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Username</dt>
                                <dd class="text-sm text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded">{{ session('student_generated')['username'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Password</dt>
                                <dd class="text-sm text-gray-900">
                                    <div class="flex items-center space-x-2">
                                        <code class="bg-gray-50 px-2 py-1 rounded" id="generated-password">{{ session('student_generated')['generated_password'] }}</code>
                                        <button type="button" onclick="copyToClipboard('generated-password')" 
                                                class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-blue-700">
                            <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Pastikan untuk menyimpan credentials ini dengan aman. Password tidak akan ditampilkan lagi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Form Generate Account</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Isi data berikut untuk membuat akun mahasiswa baru. Username dan password akan digenerate otomatis.
                </p>
            </div>

            <form action="{{ route('students.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                           placeholder="Masukkan nama lengkap mahasiswa">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Year Field -->
                <div>
                    <label for="year_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Tahun Akademik <span class="text-red-500">*</span>
                    </label>
                    <select id="year_id" name="year_id" required
                            class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('year_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        <option value="">Pilih Tahun Akademik</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ old('year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('year_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Student Class Field -->
                <div>
                    <label for="student_class_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <select id="student_class_id" name="student_class_id" required disabled
                            class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 disabled:text-gray-500 @error('student_class_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        <option value="">Pilih kelas terlebih dahulu</option>
                    </select>
                    @error('student_class_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                           placeholder="contoh@email.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Section -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Preview Generated Data</h3>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Username Preview</label>
                            <input type="text" id="generated_username" readonly
                                   class="mt-1 block w-full px-3 py-2 bg-white border border-gray-200 rounded-md text-sm font-mono text-gray-700"
                                   placeholder="Username akan muncul otomatis">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Password Preview</label>
                            <div class="mt-1 flex">
                                <input type="text" id="generated_password_preview" readonly
                                       class="block w-full px-3 py-2 bg-white border border-gray-200 rounded-l-md text-sm font-mono text-gray-700"
                                       placeholder="Klik tombol untuk generate">
                                <button type="button" id="generatePasswordBtn"
                                        class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium border border-blue-600 rounded-r-md transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">8 karakter: huruf besar, kecil, angka, dan simbol</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <a href="{{ route('students.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Generate Account Mahasiswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const yearSelect = document.getElementById('year_id');
    const classSelect = document.getElementById('student_class_id');
    const usernameInput = document.getElementById('generated_username');
    const passwordInput = document.getElementById('generated_password_preview');
    const generatePasswordBtn = document.getElementById('generatePasswordBtn');

    // Auto generate username preview when name changes
    nameInput.addEventListener('input', function() {
        const username = this.value.toLowerCase().replace(/\s+/g, '.');
        usernameInput.value = username;
    });

    // Load student classes when year changes
    yearSelect.addEventListener('change', function() {
        const yearId = this.value;
        console.log('Year selected:', yearId);
        
        classSelect.innerHTML = '<option value="">Pilih kelas</option>';
        classSelect.disabled = !yearId;

        if (yearId) {
            const url = `/api/student-classes/${yearId}`;
            console.log('Fetching from URL:', url);
            
            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Student classes data:', data);
                    
                    if (data.length === 0) {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Tidak ada kelas untuk tahun ini';
                        classSelect.appendChild(option);
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
                    }
                })
                .catch(error => {
                    console.error('Error fetching student classes:', error);
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Error loading classes';
                    classSelect.appendChild(option);
                });
        }
    });

    // Generate password preview
    generatePasswordBtn.addEventListener('click', function() {
        const password = generateRandomPassword();
        passwordInput.value = password;
    });

    // Load classes if year is already selected
    if (yearSelect.value) {
        yearSelect.dispatchEvent(new Event('change'));
    }

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
