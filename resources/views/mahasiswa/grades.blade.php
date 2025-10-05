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
</style>

<!-- Header Section -->
<header id="section-header" class="pb-6">
    <div class="flex justify-between items-center">
        <!-- Search Bar -->
        <div class="relative w-[438px]">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input type="text" placeholder="Search Class, Documents, Activities..." class="w-full h-[50px] pl-12 pr-4 py-2 border border-blue-primary rounded-lg text-base font-semibold font-sans placeholder-gray-placeholder focus:outline-none focus:ring-2 focus:ring-blue-primary">
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

            <!-- Options Dropdown -->
            <div class="relative">
                <button id="options-menu-button" class="focus:outline-none p-1 rounded-full hover:bg-gray-200 transition-colors">
                    <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" /></svg>
                </button>
                <div id="options-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 ring-1 ring-black ring-opacity-5">
                    <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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

<section id="section-welcome" class="mt-2 mb-8">
    <div class="bg-blue-primary rounded-xl flex items-center justify-between text-white p-10 relative overflow-hidden h-[175px]">
        <div class="z-10">
            @php($student = Auth::guard('student')->user())
            <h1 class="font-sans font-bold text-[35px] leading-tight -tracking-[1.05px] mb-2">Welcome back, {{ $student ? explode(' ', $student->name)[0] : 'Mahasiswa' }}</h1>
            <p class="font-sans text-[15px] leading-[26px] tracking-[0.3px] max-w-lg">You have 27 new student added to your domain. Please reach out to the Head Teacher if you want them excluded from your domain.</p>
        </div>
        <div class="absolute right-0 bottom-0 h-full">
            <img src="{{ asset('images/bg-guru.png') }}" alt="Teacher and students illustration" class="h-full object-cover">
        </div>
    </div>
</section>

<section id="section-grades" class="py-4">
    <div class="flex justify-between items-center mb-8">
        <h2 class="font-poppins font-semibold text-4xl tracking-wider">Rincian Nilai Akademik</h2>
        <a href="#" class="bg-blue-primary text-blue-light font-poppins font-medium text-xl px-8 py-3 rounded-2xl text-center">Unduh transkrip</a>
    </div>

    <!-- Filters -->
    <div class="flex flex-col md:flex-row md:items-center md:space-x-5 space-y-4 md:space-y-0 mb-8">
        <!-- Year Filters Dropdown -->
        <div class="relative">
            <button id="year-dropdown-button" class="bg-blue-primary text-blue-light font-poppins font-medium text-xl w-full md:w-auto px-8 py-3 rounded-2xl text-center flex items-center justify-center gap-2 transition-colors duration-300">
                <span id="selected-year">2021</span>
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
            </button>
            <div id="year-dropdown-menu" class="hidden absolute left-0 mt-2 w-full bg-white rounded-md shadow-lg py-1 z-10 ring-1 ring-black ring-opacity-5">
                <a href="#" class="year-dropdown-item block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100">2021</a>
                <a href="#" class="year-dropdown-item block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100">2022</a>
                <a href="#" class="year-dropdown-item block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100">2023</a>
                <a href="#" class="year-dropdown-item block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100">2024</a>
                <a href="#" class="year-dropdown-item block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100">2025</a>
            </div>
        </div>
        
        <!-- Semester Filters Dropdown -->
        <div class="relative">
             <button id="semester-dropdown-button" class="bg-blue-primary text-blue-light font-poppins font-medium text-xl w-full md:w-auto px-8 py-3 rounded-2xl text-center flex items-center justify-center gap-2 transition-colors duration-300">
                <span id="selected-semester">Semester 1</span>
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
            </button>
            <div id="semester-dropdown-menu" class="hidden absolute left-0 mt-2 w-full bg-white rounded-md shadow-lg py-1 z-10 ring-1 ring-black ring-opacity-5">
                <a href="#" class="semester-dropdown-item block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100">Semester 1</a>
                <a href="#" class="semester-dropdown-item block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100">Semester 2</a>
                <a href="#" class="semester-dropdown-item block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100">Semester 3</a>
                <a href="#" class="semester-dropdown-item block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100">Semester 4</a>
                <a href="#" class="semester-dropdown-item block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100">Semester 5</a>
                <a href="#" class="semester-dropdown-item block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100">Semester 6</a>
                <a href="#" class="semester-dropdown-item block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100">Semester 7</a>
                <a href="#" class="semester-dropdown-item block px-4 py-2 text-lg text-gray-700 hover:bg-gray-100">Semester 8</a>
            </div>
        </div>
    </div>

    <div class="flex flex-col space-y-5">
        <div class="bg-blue-light border border-[rgba(0,0,0,0.4)] rounded-2xl p-5 flex justify-between items-center">
            <div>
                <h3 class="font-poppins font-semibold text-3xl mb-1">Algoritma Pemograman</h3>
                <p class="font-poppins text-xl text-gray-subtext">Dosen : Pak Nurul S.kom</p>
                <p class="font-poppins text-xl text-gray-subtext">Pak Nurul S.kom</p>
            </div>
            <div class="flex flex-col items-center space-y-2">
                <div class="bg-green-grade w-[90px] h-[90px] rounded-full flex items-center justify-center">
                    <span class="font-poppins font-bold text-xl text-blue-light">A</span>
                </div>
                <p class="font-poppins text-base">3 SKS</p>
            </div>
        </div>
        <div class="bg-blue-light border border-[rgba(0,0,0,0.4)] rounded-2xl p-5 flex justify-between items-center">
            <div>
                <h3 class="font-poppins font-semibold text-3xl mb-1">Struktur Data</h3>
                <p class="font-poppins text-xl text-gray-subtext">Dosen : Bu Retno S.kom</p>
            </div>
            <div class="flex flex-col items-center space-y-2">
                <div class="bg-green-grade w-[90px] h-[90px] rounded-full flex items-center justify-center">
                    <span class="font-poppins font-bold text-xl text-blue-light">A</span>
                </div>
                <p class="font-poppins text-base">3 SKS</p>
            </div>
        </div>
        <div class="bg-blue-light border border-[rgba(0,0,0,0.4)] rounded-2xl p-5 flex justify-between items-center">
            <div>
                <h3 class="font-poppins font-semibold text-3xl mb-1">Basis Data</h3>
                <p class="font-poppins text-xl text-gray-subtext">Dosen : Pak Agus S.kom</p>
            </div>
            <div class="flex flex-col items-center space-y-2">
                <div class="bg-green-grade w-[90px] h-[90px] rounded-full flex items-center justify-center">
                    <span class="font-poppins font-bold text-xl text-blue-light">A</span>
                </div>
                <p class="font-poppins text-base">3 SKS</p>
            </div>
        </div>
    </div>
</section>

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
                if (!menu.classList.contains('hidden') && (!button || !button.contains(event.target))) {
                    menu.classList.add('hidden');
                }
            });
        });

        // Menambahkan atribut dan class helper ke menu untuk fungsi di atas
        document.querySelectorAll('[id$="-menu"]').forEach(menu => {
            const buttonId = menu.id.replace('-menu', '-button');
            menu.classList.add('dropdown-menu');
            menu.setAttribute('aria-labelledby', buttonId);
        });

        // Setup untuk setiap dropdown yang ada di halaman
        setupDropdown('notifications-menu-button', 'notifications-menu');
        setupDropdown('options-menu-button', 'options-menu');
        setupDropdown('year-dropdown-button', 'year-dropdown-menu');
        setupDropdown('semester-dropdown-button', 'semester-dropdown-menu');


        // --- Logic khusus untuk Year Dropdown Items ---
        const yearDropdownItems = document.querySelectorAll('.year-dropdown-item');
        const selectedYearSpan = document.getElementById('selected-year');
        const yearDropdownMenu = document.getElementById('year-dropdown-menu');

        if (yearDropdownItems.length > 0 && selectedYearSpan && yearDropdownMenu) {
            yearDropdownItems.forEach(item => {
                item.addEventListener('click', function(event) {
                    event.preventDefault();
                    selectedYearSpan.textContent = this.textContent;
                    yearDropdownMenu.classList.add('hidden');
                });
            });
        }
        
        // --- Logic khusus untuk Semester Dropdown Items ---
        const semesterDropdownItems = document.querySelectorAll('.semester-dropdown-item');
        const selectedSemesterSpan = document.getElementById('selected-semester');
        const semesterDropdownMenu = document.getElementById('semester-dropdown-menu');

        if (semesterDropdownItems.length > 0 && selectedSemesterSpan && semesterDropdownMenu) {
            semesterDropdownItems.forEach(item => {
                item.addEventListener('click', function(event) {
                    event.preventDefault();
                    selectedSemesterSpan.textContent = this.textContent.trim();
                    semesterDropdownMenu.classList.add('hidden');
                });
            });
        }
    });
</script>

@endsection

