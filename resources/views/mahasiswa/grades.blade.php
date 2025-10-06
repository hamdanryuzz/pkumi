{{-- Menggunakan layout slicing dengan sidebar --}}
@extends('layouts.slicing')

{{-- Mengatur judul untuk halaman ini --}}
@section('title', 'Rincian Nilai Akademik')

{{-- Mendefinisikan blok konten --}}
@section('content')

{{-- Menambahkan warna custom yang dibutuhkan untuk halaman ini --}}
<style>
    .border-blue-primary { border-color: #007BFF; }
    .bg-blue-primary { background-color: #007BFF; }
    .text-blue-primary { color: #007BFF; }
    .focus\:ring-blue-primary:focus { --tw-ring-color: #007BFF; }
    .bg-blue-light { background-color: #E6F1FF; }
    .text-blue-light { color: #FFFFFF; }
    .text-gray-placeholder { color: #A0AEC0; }
    .text-gray-subtext { color: #718096; }
    .bg-green-grade { background-color: #28A745; }
    .bg-yellow-grade { background-color: #FFC107; }
    .bg-red-grade { background-color: #DC3545; }
</style>

@php
    // Ambil data Mahasiswa yang sedang login
    $student = Auth::guard('student')->user();
    // Ambil variabel filter dan data yang dikirim dari StudentPageController
    $selectedPeriodId = request('period_id');
    $selectedSemesterId = request('semester_id');
@endphp

<!-- Header Section - KODE DI SINI DIKEMBALIKAN KE STRUKTUR LENGKAP -->
<header id="section-header" class="pb-6">
    <div class="flex justify-between items-center">
        <!-- Search Bar -->
        <div class="relative w-[438px]">
            {{-- Menggunakan form GET untuk Search Mata Kuliah (jika Anda implementasikan logic search di Controller) --}}
            <form action="{{ route('mahasiswa.dashboard') }}" method="GET">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                {{-- Input search diaktifkan, pastikan ini sesuai dengan logic di StudentPageController --}}
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search Class, Documents, Activities..." 
                       class="w-full h-[50px] pl-12 pr-4 py-2 border border-blue-primary rounded-lg text-base font-semibold font-sans placeholder-gray-placeholder focus:outline-none focus:ring-2 focus:ring-blue-primary">
                {{-- Input filter tersembunyi agar tidak hilang saat search --}}
                <input type="hidden" name="period_id" value="{{ $selectedPeriodId }}">
                <input type="hidden" name="semester_id" value="{{ $selectedSemesterId }}">
            </form>
        </div>

        <!-- Profile, Settings, and Notifications -->
        <div class="flex items-center space-x-5">
            <!-- Notifications Dropdown -->
            <div class="relative">
                <button id="notifications-menu-button" class="focus:outline-none p-1 rounded-full">
                    <div class="relative">
                        <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-sans font-bold text-[6px]">1</span>
                        </div>
                    </div>
                </button>
                <div id="notifications-menu" class="hidden absolute right-0 mt-2 w-72 md:w-80 bg-white rounded-md shadow-lg py-1 z-20 ring-1 ring-black ring-opacity-5">
                    <div class="px-4 py-2 text-sm text-gray-700 font-bold border-b">Notifications</div>
                    <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 border-b">
                        <p class="font-semibold">Nilai baru ditambahkan</p>
                        <p class="text-xs text-gray-500">Nilai untuk mata kuliah Algoritma Pemrograman telah diupdate.</p>
                    </a>
                    <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 border-b">
                        <p class="font-semibold">Jadwal Perkuliahan</p>
                        <p class="text-xs text-gray-500">Perubahan jadwal untuk mata kuliah Struktur Data.</p>
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-center text-blue-primary hover:underline">Lihat semua notifikasi</a>
                </div>
            </div>

            <!-- Options Dropdown (Mengandung link Profile dan Logout) -->
            <div class="relative">
                <button id="options-menu-button" class="focus:outline-none p-1 rounded-full hover:bg-gray-200 transition-colors">
                    <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" /></svg>
                </button>
                <div id="options-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 ring-1 ring-black ring-opacity-5">
                    {{-- Mengarahkan ke halaman profil Mahasiswa --}}
                    <a href="{{ route('mahasiswa.profile') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        <span>Profile</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Profile Picture -->
            <div class="w-[50px] h-[50px] rounded-lg border border-blue-primary overflow-hidden">
                <img src="{{ asset('images/xaviera.jpeg') }}" alt="Profile Picture" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</header>
<!-- END Header Section -->

<section id="section-welcome" class="mt-2 mb-8">
    <div class="bg-blue-primary rounded-xl flex items-center justify-between text-white p-10 relative overflow-hidden h-[175px]">
        <div class="z-10">
            <h1 class="font-sans font-bold text-[35px] leading-tight -tracking-[1.05px] mb-2">Welcome back, {{ $student ? explode(' ', $student->name)[0] : 'Mahasiswa' }}</h1>
            <p class="font-sans text-[15px] leading-[26px] tracking-[0.3px] max-w-lg">Lihat rincian nilai mata kuliah Anda di sini.</p>
        </div>
        <div class="absolute right-0 bottom-0 h-full">
            <img src="{{ asset('images/bg-guru.png') }}" alt="Teacher and students illustration" class="h-full object-cover">
        </div>
    </div>
</section>

<section id="section-grades" class="py-4">
    <div class="flex justify-between items-center mb-8">
        <h2 class="font-poppins font-semibold text-4xl tracking-wider">Rincian Nilai Akademik</h2>
        {{-- Link unduh transkrip - arahkan ke ReportController --}}
        <a href="{{ route('reports.exportPdf') }}" class="bg-blue-primary text-blue-light font-poppins font-medium text-xl px-8 py-3 rounded-2xl text-center">Unduh transkrip</a>
    </div>

    {{-- FILTERS DINAMIS --}}
    <form id="filter-form" action="{{ route('mahasiswa.dashboard') }}" method="GET" class="flex flex-col md:flex-row md:items-center md:space-x-5 space-y-4 md:space-y-0 mb-8">
        <!-- Year/Period Filters Dropdown -->
        <div class="relative">
            <select name="period_id" id="period-filter" onchange="document.getElementById('filter-form').submit()"
                class="bg-blue-primary appearance-none text-blue-light font-poppins font-medium text-xl w-full md:w-auto px-8 py-3 rounded-2xl text-center flex items-center justify-center gap-2 transition-colors duration-300">
                <option value="">Semua Tahun Ajaran</option>
                @foreach($periods as $period)
                    <option value="{{ $period->id }}" {{ $selectedPeriodId == $period->id ? 'selected' : '' }}>
                        {{ $period->name }}
                    </option>
                @endforeach
            </select>
            {{-- Dropdown Icon --}}
            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-blue-light"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg></div>
        </div>
        
        <!-- Semester Filters Dropdown -->
        <div class="relative">
             <select name="semester_id" id="semester-filter" onchange="document.getElementById('filter-form').submit()"
                class="bg-blue-primary appearance-none text-blue-light font-poppins font-medium text-xl w-full md:w-auto px-8 py-3 rounded-2xl text-center flex items-center justify-center gap-2 transition-colors duration-300">
                <option value="">Semua Semester</option>
                @foreach($semesters as $semester)
                    <option value="{{ $semester->id }}" {{ $selectedSemesterId == $semester->id ? 'selected' : '' }}>
                        {{ $semester->name }}
                    </option>
                @endforeach
            </select>
            {{-- Dropdown Icon --}}
            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-blue-light"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg></div>
        </div>
        
        {{-- Input tersembunyi untuk mempertahankan nilai search saat filter diubah --}}
        <input type="hidden" name="search" value="{{ request('search') }}">
    </form>
    {{-- END FILTERS --}}

    <div class="flex flex-col space-y-5">
        @forelse($courses as $item)
            @php
                $gradeLetter = $item['letter_grade'];
                $bgColor = 'bg-red-grade';
                if ($gradeLetter == 'A+' || $gradeLetter == 'A' || $gradeLetter == 'A-') {
                    $bgColor = 'bg-green-grade';
                } elseif (str_contains($gradeLetter, 'B')) {
                    $bgColor = 'bg-yellow-grade';
                } elseif ($gradeLetter == 'C') {
                    $bgColor = 'bg-red-grade'; // Jika C masih lulus
                } else {
                    $bgColor = 'bg-gray-500'; // Default jika nilai belum masuk
                }
            @endphp
            
            <div class="bg-blue-light border border-[rgba(0,0,0,0.4)] rounded-2xl p-5 flex justify-between items-center transition duration-300 hover:shadow-lg hover:border-blue-primary">
                <div>
                    <h3 class="font-poppins font-semibold text-3xl mb-1">{{ $item['course_name'] }} ({{ $item['course_code'] }})</h3>
                    <p class="font-poppins text-xl text-gray-subtext">Semester: {{ $item['semester'] }} ({{ $item['period_name'] }})</p>
                    {{-- Dosen Name - Dihapus karena tidak ada data dosen di StudentPageController --}}
                    {{-- <p class="font-poppins text-xl text-gray-subtext">Dosen : [Nama Dosen]</p> --}}
                </div>
                <div class="flex flex-col items-center space-y-2">
                    <div class="{{ $bgColor }} w-[90px] h-[90px] rounded-full flex items-center justify-center shadow-md">
                        <span class="font-poppins font-bold text-3xl text-blue-light">{{ $gradeLetter }}</span>
                    </div>
                    <p class="font-poppins text-base text-gray-subtext">{{ $item['sks'] }} SKS</p>
                </div>
            </div>
        @empty
            <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed">
                <p class="text-xl font-medium text-gray-500">Tidak ada mata kuliah terdaftar atau nilai yang ditemukan untuk filter ini.</p>
                <p class="text-base text-gray-400 mt-2">Coba ganti Tahun Ajaran atau Semester.</p>
            </div>
        @endforelse
    </div>
</section>

{{-- Script yang mengurus dropdown header (Notifications dan Options) --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Fungsi Helper untuk Menangani Semua Dropdown ---
        function setupDropdown(buttonId, menuId) {
            const button = document.getElementById(buttonId);
            const menu = document.getElementById(menuId);
            if (!button || !menu) return;

            button.addEventListener('click', function (event) {
                event.stopPropagation();
                // Sembunyikan dropdown lain yang mungkin terbuka
                document.querySelectorAll('.dropdown-menu').forEach(otherMenu => {
                    if (otherMenu.id !== menuId) {
                        otherMenu.classList.add('hidden');
                    }
                });
                menu.classList.toggle('hidden');
            });
        }
        
        // Menutup semua dropdown jika klik di luar
        window.addEventListener('click', function (event) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                const button = document.getElementById(menu.getAttribute('aria-labelledby'));
                // Cek apakah klik berada di luar menu dan tombol
                if (!menu.classList.contains('hidden') && (!button || !button.contains(event.target)) && !menu.contains(event.target)) {
                    menu.classList.add('hidden');
                }
            });
        });

        // Menambahkan atribut dan class helper ke menu
        document.querySelectorAll('[id$="-menu"]').forEach(menu => {
            const buttonId = menu.id.replace('-menu', '-button');
            menu.classList.add('dropdown-menu');
            menu.setAttribute('aria-labelledby', buttonId);
        });

        // Setup untuk dropdown notifikasi dan options
        setupDropdown('notifications-menu-button', 'notifications-menu');
        setupDropdown('options-menu-button', 'options-menu');

        // Logic untuk form submit saat search bar di-enter (karena disabled, tidak perlu)
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    searchInput.closest('form').submit();
                }
            });
        }
    });
</script>

@endsection
