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