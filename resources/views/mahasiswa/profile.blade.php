@extends('layouts.slicing')

@section('title', 'My Profile Settings')

@section('content')

{{-- 
  Style ini disederhanakan. Warna custom sudah pindah ke tailwind.config.js.
  Kita hanya menyimpan style yang spesifik untuk state 'edit' di halaman ini.
--}}
<style>
    /* Saat form punya class 'form-editable', ubah style input 
      yang BUKAN readonly (agar bisa di-edit).
    */
    .form-editable .input-field:not(.input-readonly) { 
        background-color: white !important; 
        border-color: #4A90E2 !important; /* border-custom-blue */
    }

    /* Saat form 'form-editable', pastikan input readonly
      TETAP terlihat disabled (abu-abu).
    */
    .form-editable .input-readonly {
        background-color: #f9fafb !important; /* bg-gray-50 */
    }
    
    /* Data field styling dengan hover effect */
    .profile-data-row {
        display: flex;
        flex-direction: column;
        padding: 0.875rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: background-color 0.2s ease;
    }

    .profile-data-row:hover {
        background-color: rgba(74, 144, 226, 0.02);
    }

    .profile-data-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: rgba(0, 0, 0, 0.6);
        margin-bottom: 0.375rem;
        text-transform: capitalize;
    }

    .profile-data-value {
        font-size: 1rem;
        font-weight: 400;
        color: rgba(0, 0, 0, 0.85);
    }

    /* Section header styling */
    .profile-section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1rem;
        font-weight: 600;
        color: rgba(0, 0, 0, 0.9);
        margin-bottom: 1.5rem;
        padding-bottom: 0.875rem;
        border-bottom: 2px solid #4A90E2;
        position: relative;
    }

    .profile-section-header::before {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #4A90E2, transparent);
        animation: slideIn 0.6s ease forwards;
    }

    @keyframes slideIn {
        from {
            width: 0;
        }
        to {
            width: 100%;
        }
    }

    .profile-section-icon {
        width: 1.5rem;
        height: 1.5rem;
        flex-shrink: 0;
        opacity: 0.8;
    }

    /* Grid container dengan subtle shadow */
    .profile-info-grid {
        background: linear-gradient(135deg, rgba(74, 144, 226, 0.03) 0%, rgba(255, 255, 255, 0) 100%);
        border-radius: 0.5rem;
        padding: 1.5rem;
        border: 1px solid rgba(74, 144, 226, 0.1);
    }

    /* Parent data styling dengan accent border */
    .parent-data-section {
        position: relative;
        padding-left: 1rem;
        border-left: 3px solid #4A90E2;
        background-color: rgba(74, 144, 226, 0.02);
        border-radius: 0 0.375rem 0.375rem 0;
        padding: 1rem;
    }

    .parent-data-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: rgba(0, 0, 0, 0.8);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Responsive design untuk data fields */
    @media (min-width: 640px) {
        .profile-data-row {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        .profile-data-label {
            margin-bottom: 0;
            min-width: 150px;
        }
    }

    /* Status badge animation */
    .status-badge {
        display: inline-flex;
        align-items: center;
        animation: fadeInScale 0.4s ease;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Empty state styling */
    .data-empty {
        color: rgba(0, 0, 0, 0.4);
        font-style: italic;
    }
</style>

@php
    $student = Auth::guard('student')->user();
@endphp

<div class="w-full max-w-5xl mb-4">
    {{-- TOMBOL KEMBALI --}}
    <a href="{{ route('mahasiswa.dashboard') }}" class="inline-flex items-center text-black/70 hover:text-black transition-colors text-lg font-medium mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Dashboard
    </a>
    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md text-sm mt-3" role="alert">
            {{ session('success') }}
        </div>
    @endif
</div>

{{-- Tambahkan ID ke div ini untuk CSS state --}}
<div id="profile-card" class="bg-white rounded-xl shadow-sm w-full max-w-5xl p-8 md:p-10 lg:p-12 
    {{-- Jika ada error, langsung tambahkan class 'form-editable' saat load --}}
    {{ $errors->any() ? 'form-editable' : '' }}
">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
        <div class="flex items-center gap-6">
            @if($student->image)
                <img src="{{ asset('storage/students/' . $student->image) }}" 
                     alt="User Avatar" 
                     class="w-24 h-24 rounded-full object-cover flex-shrink-0">
            @else
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-2xl font-bold">
                        {{ strtoupper(substr($student->name, 0, 2)) }}
                    </span>
                </div>
            @endif
            <div>
                <h2 class="text-xl font-medium text-black leading-tight">{{ $student->name ?? 'Nama Mahasiswa' }}</h2>
                <p class="text-base font-normal text-black/50 leading-normal">{{ $student->nim ?? 'NIM Tidak Diketahui' }}</p>
            </div>
        </div>
        
        {{-- TOMBOL EDIT/SIMPAN --}}
        <button id="edit-button" type="button" class="bg-custom-blue text-white text-base font-normal px-8 py-2.5 rounded-lg hover:bg-blue-700 transition-colors w-full sm:w-auto flex items-center justify-center gap-2">
            {{-- Konten tombol ini akan diubah oleh JS --}}
        </button>
    </div>

    {{-- Form menggunakan route update --}}
    <form id="profile-form" class="mt-12" method="POST" action="{{ route('mahasiswa.profile.update') }}">
        @csrf
        @method('PUT')
        
        {{-- Menampilkan Error Validasi --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md text-sm mb-6" role="alert">
                <p class="font-bold">Error:</p>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-y-6 gap-x-8 xl:gap-x-24">
            {{-- FULL NAME (Readonly) --}}
            <div class="flex flex-col gap-2">
                <label for="full-name" class="text-base font-normal text-black/80">Full Name</label>
                <input type="text" id="full-name" name="name"
                       value="{{ old('name', $student->name ?? '') }}"
                       class="input-field input-readonly bg-gray-50 border border-black/40 rounded-lg px-5 py-[14px] text-base text-black/80 placeholder:text-black/40 focus:outline-none"
                       disabled readonly>
            </div>
            
            {{-- USERNAME (Readonly) --}}
            <div class="flex flex-col gap-2">
                <label for="username" class="text-base font-normal text-black/80">Username</label>
                <input type="text" id="username" name="username"
                       value="{{ old('username', $student->username ?? '') }}"
                       class="input-field input-readonly bg-gray-50 border border-black/40 rounded-lg px-5 py-[14px] text-base text-black/80 placeholder:text-black/40 focus:outline-none"
                       disabled readonly>
            </div>
            
            {{-- PHONE (Editable) --}}
            <div class="flex flex-col gap-2">
                <label for="phone" class="text-base font-normal text-black/80">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $student->phone ?? '') }}" disabled 
                       class="input-field bg-gray-50 border border-black/40 rounded-lg px-5 py-[14px] text-base text-black/80 placeholder:text-black/40 focus:outline-none focus:ring-2 focus:ring-custom-blue focus:border-transparent transition-colors duration-200">
            </div>
            
            {{-- ADDRESS (Editable) --}}
            <div class="flex flex-col gap-2">
                <label for="address" class="text-base font-normal text-black/80">Address</label>
                <input type="text" id="address" name="address" value="{{ old('address', $student->address ?? '') }}" disabled 
                       class="input-field bg-gray-50 border border-black/40 rounded-lg px-5 py-[14px] text-base text-black/80 focus:outline-none focus:ring-2 focus:ring-custom-blue focus:border-transparent transition-colors duration-200">
            </div>

            {{-- PASSWORD BARU (Hidden by Default) --}}
            <div class="flex flex-col gap-2 mt-4 hidden" id="password-fields">
                <label for="password" class="text-base font-normal text-black/80">New Password</label>
                <input type="password" id="password" name="password" placeholder="Leave blank to keep current password" 
                       class="input-field bg-white border border-custom-blue rounded-lg px-5 py-[14px] text-base text-black/80 focus:outline-none focus:ring-2 focus:ring-custom-blue focus:border-transparent transition-colors duration-200">
            </div>

            {{-- KONFIRMASI PASSWORD BARU (Hidden by Default) --}}
            <div class="flex flex-col gap-2 mt-4 hidden" id="password-confirm-fields">
                <label for="password_confirmation" class="text-base font-normal text-black/80">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" 
                       class="input-field bg-white border border-custom-blue rounded-lg px-5 py-[14px] text-base text-black/80 focus:outline-none focus:ring-2 focus:ring-custom-blue focus:border-transparent transition-colors duration-200">
            </div>
        </div>
        
        {{-- TOMBOL SUBMIT FORM (Hidden by Default) --}}
        <div class="flex justify-end mt-12 hidden" id="form-actions">
            <button type="submit" class="bg-custom-blue text-white text-base font-normal px-8 py-2.5 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                Save Changes
            </button>
        </div>
    </form>

    <div class="mt-12">
        <h3 class="text-lg font-medium text-black">My email Address</h3>
        <div class="flex items-center gap-4 mt-6">
            {{-- Menggunakan kelas dari config: bg-custom-blue-10 dan text-custom-blue --}}
            <div class="relative flex-shrink-0 w-12 h-12 flex items-center justify-center bg-custom-blue-10 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-custom-blue" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
            </div>
            <div>
                <p class="text-base font-normal text-black">{{ $student->email ?? 'Email tidak ditemukan' }}</p>
                <p class="text-base font-normal text-black/50">Joined {{ $student->created_at->diffForHumans() ?? 'Tanggal tidak tersedia' }}</p>
            </div>
        </div>
    </div>

    {{-- SECTION SEMUA DATA MAHASISWA (Read-Only) --}}
    <div class="mt-12">
        <h3 class="text-lg font-medium text-black">Profile Information</h3>
        
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-y-8 gap-x-8 xl:gap-x-24">
            
            {{-- INFORMASI AKADEMIK --}}
            <div class="profile-info-grid">
                <h4 class="profile-section-header">
                    <svg class="profile-section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path>
                    </svg>
                    Informasi Akademik
                </h4>
                
                <div>
                    <div class="profile-data-row">
                        <span class="profile-data-label">NIM</span>
                        <span class="profile-data-value">{{ $student->nim ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">Kelas</span>
                        <span class="profile-data-value">{{ $student->studentClass->name ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">Angkatan</span>
                        <span class="profile-data-value">{{ $student->year->name ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">Program Studi</span>
                        <span class="profile-data-value">{{ $student->program ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">Tahun Masuk</span>
                        <span class="profile-data-value">{{ $student->admission_year ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">Semester Awal</span>
                        <span class="profile-data-value">{{ $student->first_semester ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">Status</span>
                        <span class="status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            {{ $student->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $student->status === 'inactive' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $student->status === 'lulus' ? 'bg-blue-100 text-blue-700' : '' }}">
                            {{ ucfirst($student->status ?? 'active') }}
                        </span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">IPK</span>
                        <span class="profile-data-value font-bold text-custom-blue">{{ $student->ipk ? number_format($student->ipk, 2, ',', '.') : '0,00' }}</span>
                    </div>
                </div>
            </div>
            
            {{-- INFORMASI PERSONAL --}}
            <div class="profile-info-grid">
                <h4 class="profile-section-header">
                    <svg class="profile-section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Informasi Personal
                </h4>
                
                <div>
                    <div class="profile-data-row">
                        <span class="profile-data-label">Jenis Kelamin</span>
                        <span class="profile-data-value">{{ $student->gender ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">Tempat Lahir</span>
                        <span class="profile-data-value">{{ $student->place_of_birth ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">Tanggal Lahir</span>
                        <span class="profile-data-value">{{ $student->date_of_birth ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">Status Perkawinan</span>
                        <span class="profile-data-value">{{ $student->marital_status ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">Pekerjaan</span>
                        <span class="profile-data-value">{{ $student->student_job ?? '-' }}</span>
                    </div>
                </div>
            </div>
            
            {{-- ALAMAT --}}
            <div class="profile-info-grid">
                <h4 class="profile-section-header">
                    <svg class="profile-section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Alamat
                </h4>
                
                <div>
                    <div class="profile-data-row">
                        <span class="profile-data-label">Alamat Lengkap</span>
                        <span class="profile-data-value">{{ $student->address ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">RT/RW</span>
                        <span class="profile-data-value">{{ $student->rt_rw ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">Kelurahan</span>
                        <span class="profile-data-value">{{ $student->village ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">Kecamatan</span>
                        <span class="profile-data-value">{{ $student->district ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">Kab/Kota</span>
                        <span class="profile-data-value">{{ $student->city ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row">
                        <span class="profile-data-label">Provinsi</span>
                        <span class="profile-data-value">{{ $student->province ?? '-' }}</span>
                    </div>
                </div>
            </div>
            
            {{-- INFORMASI ORANG TUA --}}
            <div class="profile-info-grid">
                <h4 class="profile-section-header">
                    <svg class="profile-section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Informasi Orang Tua
                </h4>
                
                <div class="space-y-5">
                    <div>
                        <p class="parent-data-label">Data Ayah</p>
                        <div class="parent-data-section">
                            <div class="profile-data-row border-b border-black/10 pb-2">
                                <span class="profile-data-label text-xs">Nama</span>
                                <span class="profile-data-value text-sm">{{ $student->father_name ?? '-' }}</span>
                            </div>
                            <div class="profile-data-row border-b border-black/10 py-2">
                                <span class="profile-data-label text-xs">Pendidikan Terakhir</span>
                                <span class="profile-data-value text-sm">{{ $student->father_last_education ?? '-' }}</span>
                            </div>
                            <div class="profile-data-row pt-2">
                                <span class="profile-data-label text-xs">Pekerjaan</span>
                                <span class="profile-data-value text-sm">{{ $student->father_job ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <p class="parent-data-label">Data Ibu</p>
                        <div class="parent-data-section">
                            <div class="profile-data-row border-b border-black/10 pb-2">
                                <span class="profile-data-label text-xs">Nama</span>
                                <span class="profile-data-value text-sm">{{ $student->mother_name ?? '-' }}</span>
                            </div>
                            <div class="profile-data-row border-b border-black/10 py-2">
                                <span class="profile-data-label text-xs">Pendidikan Terakhir</span>
                                <span class="profile-data-value text-sm">{{ $student->mother_last_education ?? '-' }}</span>
                            </div>
                            <div class="profile-data-row pt-2">
                                <span class="profile-data-label text-xs">Pekerjaan</span>
                                <span class="profile-data-value text-sm">{{ $student->mother_job ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- RIWAYAT PENDIDIKAN --}}
            <div class="profile-info-grid lg:col-span-2">
                <h4 class="profile-section-header">
                    <svg class="profile-section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Riwayat Pendidikan Sebelumnya
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="profile-data-row border-b-0">
                        <span class="profile-data-label">Asal Universitas/Institusi</span>
                        <span class="profile-data-value">{{ $student->origin_of_university ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row border-b-0">
                        <span class="profile-data-label">Program Studi Awal</span>
                        <span class="profile-data-value">{{ $student->initial_study_program ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row border-b-0">
                        <span class="profile-data-label">Tahun Lulus</span>
                        <span class="profile-data-value">{{ $student->graduation_year ?? '-' }}</span>
                    </div>
                    
                    <div class="profile-data-row border-b-0">
                        <span class="profile-data-label">GPA Sebelumnya</span>
                        <span class="profile-data-value font-semibold text-custom-blue">{{ $student->gpa ?? '-' }}</span>
                    </div>
                </div>
                
                @if($student->description)
                <div class="mt-6 pt-6 border-t-2 border-black/10">
                    <span class="profile-data-label block mb-3">Keterangan Tambahan</span>
                    <p class="profile-data-value leading-relaxed bg-gray-50 p-3 rounded border-l-4 border-custom-blue">{{ $student->description }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const card = document.getElementById('profile-card');
    const editButton = document.getElementById('edit-button');
    const form = document.getElementById('profile-form');
    
    // PERBAIKAN: Hanya pilih field yang BISA diedit
    const editableFields = form.querySelectorAll('.input-field:not(.input-readonly)');
    
    const formActions = document.getElementById('form-actions');
    const passwordFields = document.getElementById('password-fields');
    const passwordConfirmFields = document.getElementById('password-confirm-fields');

    // Tentukan konten tombol berdasarkan state
    const editButtonContent = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
        <span>Edit Data</span>
    `;
    const cancelButtonContent = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        <span>Cancel</span>
    `;

    // Fungsi untuk update UI berdasarkan state
    function setEditState(isEditing) {
        if (isEditing) {
            // 1. Tambah class ke parent card untuk CSS
            card.classList.add('form-editable');
            
            // 2. Ubah tombol Edit menjadi Cancel (merah)
            editButton.innerHTML = cancelButtonContent;
            editButton.classList.add('bg-red-500', 'hover:bg-red-700');
            editButton.classList.remove('bg-custom-blue', 'hover:bg-blue-700');

            // 3. Aktifkan input fields yang bisa diedit
            editableFields.forEach(input => {
                input.removeAttribute('disabled');
            });

            // 4. Tampilkan field password dan tombol Save
            passwordFields.classList.remove('hidden');
            passwordConfirmFields.classList.remove('hidden');
            formActions.classList.remove('hidden');

        } else {
            // 1. Hapus class dari parent card
            card.classList.remove('form-editable');

            // 2. Ubah tombol Cancel menjadi Edit (biru)
            editButton.innerHTML = editButtonContent;
            editButton.classList.remove('bg-red-500', 'hover:bg-red-700');
            editButton.classList.add('bg-custom-blue', 'hover:bg-blue-700');

            // 3. Nonaktifkan input fields
            editableFields.forEach(input => {
                input.setAttribute('disabled', 'true');
            });

            // 4. Sembunyikan field password dan tombol Save
            passwordFields.classList.add('hidden');
            passwordConfirmFields.classList.add('hidden');
            formActions.classList.add('hidden');
        }
    }

    // Inisialisasi: Cek apakah form sudah dalam mode edit (karena error validasi)
    let isEditing = card.classList.contains('form-editable');
    setEditState(isEditing); // Set UI awal berdasarkan state

    // Tambahkan listener utama ke tombol
    editButton.addEventListener('click', function(event) {
        event.preventDefault(); // Mencegah aksi default
        
        if (isEditing) {
            // Jika sedang meng-klik "Cancel"
            window.location.reload(); // Reload halaman untuk reset
        } else {
            // Jika sedang meng-klik "Edit Data"
            isEditing = true;
            setEditState(isEditing);
        }
    });
});
</script>

@endsection