@extends('layouts.app')

@section('title', 'Edit Mahasiswa - Sistem Penilaian PKUMI')

@section('content')
<main class="py-6 px-4 md:px-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Edit Mahasiswa</h2>
            <p class="text-base text-gray-500 mt-1">Update data mahasiswa dan kredensial login.</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-lg font-bold text-gray-800">Edit Data Mahasiswa</h5>
                    <p class="text-sm text-gray-500 mt-1">NIM: <code class="bg-gray-100 px-2 py-1 rounded">{{ $student->nim }}</code></p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($student->status) }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('students.update', $student) }}" method="POST" id="editStudentForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Basic Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Informasi Dasar
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- NIM -->
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">
                                NIM <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nim" id="nim" 
                                value="{{ old('nim', $student->nim) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                placeholder="Nomor Induk Mahasiswa">
                            @error('nim')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                NIM saat ini: <span class="font-semibold text-blue-600">{{ $student->nim }}</span>
                            </p>
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" 
                                value="{{ old('name', $student->name) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                placeholder="Nama lengkap mahasiswa">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="email" 
                                value="{{ old('email', $student->email) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                placeholder="Email mahasiswa (optional)">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="username" id="username" 
                                value="{{ old('username', $student->username) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                placeholder="Username untuk login">
                            @error('username')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                                    <option value="{{ $year->id }}" {{ old('year_id', $student->year_id) == $year->id ? 'selected' : '' }}>
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
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('student_class_id', $student->student_class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
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
                                <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                Status akun mahasiswa saat ini: 
                                <span class="font-semibold {{ $student->status == 'active' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </p>
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
                                value="{{ old('phone', $student->phone) }}"
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
                            
                            <!-- Current Image -->
                            @if($student->image)
                            <div class="mt-3">
                                <p class="text-sm text-gray-600 mb-2">Foto Saat Ini:</p>
                                <div class="relative inline-block">
                                    <img src="{{ asset('storage/students/' . $student->image) }}" 
                                        alt="{{ $student->name }}" 
                                        class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300"
                                        id="currentImage">
                                    <div class="absolute top-1 right-1 bg-green-500 text-white text-xs px-2 py-1 rounded-md">
                                        Current
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- New Image Preview -->
                            <div id="imagePreviewContainer" class="mt-3 hidden">
                                <p class="text-sm text-gray-600 mb-2">Preview Foto Baru:</p>
                                <img id="imagePreview" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border-2 border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mt-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea name="address" id="address" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                placeholder="Alamat lengkap mahasiswa">{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Login Credentials -->
                <div class="mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 border-b pb-2">
                        <i class="fas fa-key text-blue-500 mr-2"></i>Kredensial Login
                    </h6>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('username') border-red-500 @enderror" 
                                       id="username" name="username" value="{{ old('username', $student->username) }}" required>
                                @error('username')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                <div class="relative">
                                    <input type="password" 
                                           class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('password') border-red-500 @enderror pr-10" 
                                           id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                                    <button type="button" 
                                            class="absolute inset-y-0 right-0 px-3 py-2 text-gray-400 hover:text-gray-600"
                                            id="generateNewPassword">
                                        <i class="fas fa-sync-alt text-sm"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @else
                                    <div class="text-gray-500 text-xs mt-1">Kosongkan jika tidak ingin mengubah password</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Actions -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <div class="flex space-x-2">
                        <a href="{{ route('students.show', $student) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition duration-200">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>
                        <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Update Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.getElementById('year_id');
    const classSelect = document.getElementById('student_class_id');
    const generatePasswordBtn = document.getElementById('generateNewPassword');
    const passwordInput = document.getElementById('password');
    
    // Load student classes when year changes
    yearSelect.addEventListener('change', function() {
        const yearId = this.value;
        const currentClassId = classSelect.value; // Preserve current selection
        
        classSelect.innerHTML = '<option value="">Pilih Kelas</option>';
        classSelect.disabled = !yearId;
        
        if (yearId) {
            fetch(`{{ url('/api/student-classes') }}/${yearId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        classSelect.innerHTML = '<option value="">Tidak ada kelas untuk tahun ini</option>';
                    } else {
                        data.forEach(studentClass => {
                            const option = document.createElement('option');
                            option.value = studentClass.id;
                            option.textContent = studentClass.name;
                            if (studentClass.id == currentClassId) {
                                option.selected = true;
                            }
                            classSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching student classes:', error);
                    classSelect.innerHTML = '<option value="">Error loading classes</option>';
                });
        }
    });
    
    // Generate new password
    generatePasswordBtn.addEventListener('click', function() {
        const password = generateRandomPassword();
        passwordInput.value = password;
        passwordInput.type = 'text';
        
        // Change icon to indicate generation
        this.innerHTML = '<i class="fas fa-check text-green-500 text-sm"></i>';
        
        // Reset icon after 2 seconds
        setTimeout(() => {
            this.innerHTML = '<i class="fas fa-sync-alt text-sm"></i>';
            passwordInput.type = 'password';
        }, 2000);
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
});
</script>
<script>
// Preview Image Function
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const currentImage = document.getElementById('currentImage');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
            if (currentImage) {
                currentImage.style.opacity = '0.5';
            }
        }
        reader.readAsDataURL(file);
    } else {
        previewContainer.classList.add('hidden');
        if (currentImage) {
            currentImage.style.opacity = '1';
        }
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