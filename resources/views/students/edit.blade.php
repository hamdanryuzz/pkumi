@extends('layouts.app')

@section('title', 'Edit Mahasiswa - Sistem Penilaian PKUMI')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">Edit Mahasiswa</h2>
                    <p class="mt-1 text-sm text-gray-500">Perbarui data mahasiswa dan kredensial login.</p>
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

        <!-- Edit Form -->
        <form action="{{ route('students.update', $student) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Informasi Dasar -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Dasar
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- NIM -->
                    <div>
                        <label for="nim" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                            NIM <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nim" id="nim" 
                            value="{{ old('nim', $student->nim) }}" required
                            class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200"
                            placeholder="Nomor Induk Mahasiswa">
                        @error('nim')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            NIM saat ini: <span class="font-semibold text-blue-600">{{ $student->nim }}</span>
                        </p>
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" 
                            value="{{ old('name', $student->name) }}" required
                            class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200"
                            placeholder="Nama lengkap mahasiswa">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">Email</label>
                        <input type="email" name="email" id="email" 
                            value="{{ old('email', $student->email) }}"
                            class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200"
                            placeholder="Email mahasiswa (optional)">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" id="username" 
                            value="{{ old('username', $student->username) }}" required
                            class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200"
                            placeholder="Username untuk login">
                        @error('username')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">
                            Password Baru
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200"
                                placeholder="Kosongkan jika tidak ingin mengubah">
                            <button type="button" class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700" id="generateNewPassword">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @else
                            <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informasi Akademik -->
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
                                <option value="{{ $year->id }}" {{ old('year_id', $student->year_id) == $year->id ? 'selected' : '' }}>
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
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('student_class_id', $student->student_class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
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
                            <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Status akun saat ini: 
                            <span class="font-semibold {{ $student->status == 'active' ? 'text-green-600' : 'text-red-600' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informasi Kontak -->
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
                            value="{{ old('phone', $student->phone) }}"
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

                        <!-- Current Image -->
                        @if($student->image)
                        <div class="mt-3">
                            <p class="text-xs text-gray-500 mb-2">Foto Saat Ini:</p>
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
                            <p class="text-xs text-gray-500 mb-2">Preview Foto Baru:</p>
                            <img id="imagePreview" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border-2 border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mt-4">
                    <label for="address" class="block text-xs font-medium text-gray-700 uppercase tracking-wider mb-2">Alamat</label>
                    <textarea name="address" id="address" rows="3"
                            class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200"
                            placeholder="Alamat lengkap mahasiswa">{{ old('address', $student->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Alamat -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-map-marker-alt mr-2 text-red-600"></i>Alamat
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Jalan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jalan</label>
                        <input type="text" name="street" value="{{ old('street', $student->street) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- RT/RW -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">RT/RW</label>
                        <input type="text" name="rt_rw" value="{{ old('rt_rw', $student->rt_rw) }}"
                            placeholder="001/002"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Kelurahan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelurahan/Desa</label>
                        <input type="text" name="village" value="{{ old('village', $student->village) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                        <input type="text" name="district" value="{{ old('district', $student->district) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Kota/Kabupaten -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten</label>
                        <input type="text" name="city" value="{{ old('city', $student->city) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Provinsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                        <input type="text" name="province" value="{{ old('province', $student->province) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Data Akademik Tambahan -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-university mr-2 text-indigo-600"></i>Data Akademik Lainnya
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Program -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Program</label>
                        <input type="text" name="program" value="{{ old('program', $student->program) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Tahun Masuk -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Masuk</label>
                        <input type="text" name="admission_year" value="{{ old('admission_year', $student->admission_year) }}"
                            placeholder="2025"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Semester Awal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Semester Awal</label>
                        <input type="text" name="first_semester" value="{{ old('first_semester', $student->first_semester) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Universitas Asal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Universitas Asal</label>
                        <input type="text" name="origin_of_university" value="{{ old('origin_of_university', $student->origin_of_university) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Program Studi Awal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Program Studi Awal</label>
                        <input type="text" name="initial_study_program" value="{{ old('initial_study_program', $student->initial_study_program) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Tahun Lulus -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Lulus S1</label>
                        <input type="text" name="graduation_year" value="{{ old('graduation_year', $student->graduation_year) }}"
                            placeholder="2024"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- IPK S1 -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">IPK S1</label>
                        <input type="number" name="gpa" value="{{ old('gpa', $student->gpa) }}" step="0.01" min="0" max="4"
                            placeholder="3.50"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Pekerjaan Mahasiswa -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                        <input type="text" name="student_job" value="{{ old('student_job', $student->student_job) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Data Orang Tua -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-users mr-2 text-purple-600"></i>Data Orang Tua
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Data Ayah -->
                    <div class="space-y-4">
                        <h3 class="font-semibold text-gray-700">Data Ayah</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ayah</label>
                            <input type="text" name="father_name" value="{{ old('father_name', $student->father_name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Terakhir Ayah</label>
                            <input type="text" name="father_last_education" value="{{ old('father_last_education', $student->father_last_education) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ayah</label>
                            <input type="text" name="father_job" value="{{ old('father_job', $student->father_job) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Data Ibu -->
                    <div class="space-y-4">
                        <h3 class="font-semibold text-gray-700">Data Ibu</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ibu</label>
                            <input type="text" name="mother_name" value="{{ old('mother_name', $student->mother_name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Terakhir Ibu</label>
                            <input type="text" name="mother_last_education" value="{{ old('mother_last_education', $student->mother_last_education) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ibu</label>
                            <input type="text" name="mother_job" value="{{ old('mother_job', $student->mother_job) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keterangan Tambahan -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-file-alt mr-2 text-orange-600"></i>Keterangan
                </h2>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="description" rows="4"
                        placeholder="Catatan tambahan tentang mahasiswa..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $student->description) }}</textarea>
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
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const generatePasswordBtn = document.getElementById('generateNewPassword');
    const passwordInput = document.getElementById('password');

    // Generate new password
    generatePasswordBtn.addEventListener('click', function() {
        const password = generateRandomPassword();
        passwordInput.value = password;
        passwordInput.type = 'text';

        // Change icon to indicate generation
        this.innerHTML = '<svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';

        // Reset icon after 2 seconds
        setTimeout(() => {
            this.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';
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
        for (let i = 4; i < 10; i++) {
            password += allChars.charAt(Math.floor(Math.random() * allChars.length));
        }

        return password.split('').sort(() => Math.random() - 0.5).join('');
    }
});

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
@endsection