@extends('layouts.slicing')

@section('title', 'My Profile Settings')

@section('content')

{{-- Menambahkan warna custom khusus untuk halaman ini (jika ada) --}}
<style>
    .bg-custom-blue { background-color: #4A90E2; }
    .focus\:ring-custom-blue:focus { --tw-ring-color: #4A90E2; }
    .bg-custom-blue\/10 { background-color: rgba(74, 144, 226, 0.1); }
    .hover\:bg-custom-blue\/20:hover { background-color: rgba(74, 144, 226, 0.2); }
    .text-custom-blue { color: #4A90E2; }
</style>

@php
    // Gunakan guard 'student' untuk Mahasiswa
    $student = Auth::guard('student')->user();
@endphp

<div class="w-full max-w-5xl mb-4">
    {{-- TOMBOL KEMBALI DITAMBAHKAN DI SINI --}}
    <a href="{{ route('mahasiswa.dashboard') }}" class="inline-flex items-center text-black/70 hover:text-black transition-colors text-lg font-medium mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Dashboard
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm w-full max-w-5xl p-8 md:p-10 lg:p-12">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
        <div class="flex items-center gap-6">
            <img src="{{ asset('images/xaviera.jpeg') }}" alt="User Avatar" class="w-24 h-24 rounded-full object-cover flex-shrink-0">
            <div>
                {{-- Data Mahasiswa --}}
                <h2 class="text-xl font-medium text-black leading-tight">{{ $student->name ?? 'Nama Mahasiswa' }}</h2>
                <p class="text-base font-normal text-black/50 leading-normal">{{ $student->nim ?? 'NIM Tidak Diketahui' }}</p>
            </div>
        </div>
        <button class="bg-custom-blue text-white text-base font-normal px-8 py-2.5 rounded-lg hover:bg-blue-700 transition-colors w-full sm:w-auto flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
            <span>Edit</span>
        </button>
    </div>

    {{-- Form hanya menampilkan data Mahasiswa, field lain di-disable karena tidak ada di Model Student --}}
    <form class="mt-12" method="POST" action="#">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-y-6 gap-x-8 xl:gap-x-24">
            <div class="flex flex-col gap-2">
                <label for="full-name" class="text-base font-normal text-black/80">Full Name</label>
                {{-- Mengisi value dari data Student --}}
                <input type="text" id="full-name" name="name" value="{{ old('name', $student->name ?? '') }}" disabled placeholder="Your Full Name" class="bg-gray-50 border border-black/40 rounded-lg px-5 py-[14px] text-base text-black/80 placeholder:text-black/40 focus:outline-none focus:ring-2 focus:ring-custom-blue focus:border-transparent">
            </div>
            <div class="flex flex-col gap-2">
                <label for="username" class="text-base font-normal text-black/80">Username</label>
                {{-- Mengisi value dari data Student --}}
                <input type="text" id="username" name="username" value="{{ old('username', $student->username ?? '') }}" disabled placeholder="Your Username" class="bg-gray-50 border border-black/40 rounded-lg px-5 py-[14px] text-base text-black/80 placeholder:text-black/40 focus:outline-none focus:ring-2 focus:ring-custom-blue focus:border-transparent">
            </div>
            <div class="flex flex-col gap-2">
                <label for="phone" class="text-base font-normal text-black/80">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $student->phone ?? '') }}" disabled placeholder="Your Phone Number" class="bg-gray-50 border border-black/40 rounded-lg px-5 py-[14px] text-base text-black/80 placeholder:text-black/40 focus:outline-none focus:ring-2 focus:ring-custom-blue focus:border-transparent">
            </div>
            <div class="flex flex-col gap-2">
                <label for="address" class="text-base font-normal text-black/80">Address</label>
                <input type="text" id="address" name="address" value="{{ old('address', $student->address ?? '') }}" disabled placeholder="Your Address" class="w-full bg-gray-50 border border-black/40 rounded-lg px-5 py-[14px] text-base text-black/80 focus:outline-none focus:ring-2 focus:ring-custom-blue focus:border-transparent">
            </div>
        </div>
    </form>

    <div class="mt-12">
        <h3 class="text-lg font-medium text-black">My email Address</h3>
        <div class="flex items-center gap-4 mt-6">
            <div class="relative flex-shrink-0 w-12 h-12 flex items-center justify-center bg-custom-blue/10 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-custom-blue" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
            </div>
            <div>
                <p class="text-base font-normal text-black">{{ $student->email ?? 'Email tidak ditemukan' }}</p>
                {{-- Created_at adalah objek Carbon, jadi diffForHumans() bisa dipakai --}}
                <p class="text-base font-normal text-black/50">Joined {{ $student->created_at->diffForHumans() ?? 'Tanggal tidak tersedia' }}</p>
            </div>
        </div>
        <button class="mt-8 bg-custom-blue/10 text-custom-blue text-base font-normal px-6 py-2.5 rounded-lg hover:bg-custom-blue/20 transition-colors flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            <span>Add Email Address</span>
        </button>
    </div>

</div>

@endsection