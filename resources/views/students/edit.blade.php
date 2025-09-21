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

            <form action="{{ route('students.update', $student) }}" method="POST" id="editStudentForm">
                @csrf
                @method('PUT')
                
                <!-- Basic Info -->
                <div class="mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 border-b pb-2">Informasi Dasar</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name', $student->name) }}" required>
                            @error('name')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" 
                                   class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('email') border-red-500 @enderror" 
                                   id="email" name="email" value="{{ old('email', $student->email) }}">
                            @error('email')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Academic Info -->
                <div class="mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-3 border-b pb-2">Informasi Akademik</h6>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NIM <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('nim') border-red-500 @enderror" 
                                   id="nim" name="nim" value="{{ old('nim', $student->nim) }}" required>
                            @error('nim')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="year_id" class="block text-sm font-medium text-gray-700 mb-1">Tahun <span class="text-red-500">*</span></label>
                            <select class="form-select block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('year_id') border-red-500 @enderror" 
                                    id="year_id" name="year_id" required>
                                <option value="">Pilih Tahun</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}" {{ old('year_id', $student->year_id) == $year->id ? 'selected' : '' }}>
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
                                    id="student_class_id" name="student_class_id" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('student_class_id', $student->student_class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
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
                                   id="phone" name="phone" value="{{ old('phone', $student->phone) }}">
                            @error('phone')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select class="form-select block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('status') border-red-500 @enderror" 
                                    id="status" name="status" required>
                                <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea class="form-textarea block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('address') border-red-500 @enderror" 
                                   id="address" name="address" rows="3">{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
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
                        <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus mahasiswa ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition duration-200">
                                <i class="fas fa-trash mr-2"></i>
                                Hapus
                            </button>
                        </form>
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
@endsection