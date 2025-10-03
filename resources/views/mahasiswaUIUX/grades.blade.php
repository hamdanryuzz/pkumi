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
                <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" placeholder="Search Class, Documents, Activities..." class="w-full h-[50px] pl-12 pr-4 py-2 border border-blue-primary rounded-lg text-base font-semibold font-sans placeholder-gray-placeholder focus:outline-none focus:ring-2 focus:ring-blue-primary">
        </div>
        <!-- Profile, Settings, and Notifications -->
        <div class="flex items-center space-x-5">
            <div class="relative">
                <button class="focus:outline-none">
                    <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                </button>
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-sans font-bold text-[6px]">1</span>
                </div>
            </div>
            <!-- Temporary logout button -->
            <form action="{{ route('logout') }}" method="POST" class="ml-auto">
                @csrf
                <button class="focus:outline-none">
                    <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                    </svg>
                </button>
            </form>
            <div class="w-[50px] h-[50px] rounded-lg border border-blue-primary overflow-hidden">
                <img src="{{ asset('images/xaviera.jpeg') }}" alt="Profile Picture" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</header>

<!-- Welcome Section -->
<section id="section-welcome" class="mt-2 mb-8">
    <div class="bg-blue-primary rounded-xl flex items-center justify-between text-white p-10 relative overflow-hidden h-[175px]">
        <div class="z-10">
            <h1 class="font-sans font-bold text-[35px] leading-tight -tracking-[1.05px] mb-2">Welcome back, {{ explode(' ', Auth::user()->name)[0] }}</h1>
            <p class="font-sans text-[15px] leading-[26px] tracking-[0.3px] max-w-lg">You have 27 new student added to your domain. Please reach out to the Head Teacher if you want them excluded from your domain.</p>
        </div>
        <div class="absolute right-0 bottom-0 h-full">
            <img src="{{ asset('images/bg-guru.png') }}" alt="Teacher and students illustration" class="h-full object-cover">
        </div>
    </div>
</section>

<!-- Academic Grades Section -->
<section id="section-grades" class="py-4">
    <div class="flex justify-between items-center mb-8">
        <h2 class="font-poppins font-semibold text-4xl tracking-wider">Rincian Nilai Akademik</h2>
        <a href="#" class="bg-blue-primary text-blue-light font-poppins font-medium text-xl px-8 py-3 rounded-2xl text-center">Unduh transkrip</a>
    </div>

    <!-- Filters -->
    <div class="flex flex-col space-y-8 mb-8">
        <!-- Year Filters -->
        <div class="flex items-center space-x-5">
            <a href="#" class="year-filter-btn bg-blue-primary text-blue-light font-poppins font-medium text-xl px-8 py-3 rounded-2xl text-center transition-colors duration-300">2021</a>
            <a href="#" class="year-filter-btn border border-blue-primary text-black font-poppins font-medium text-xl px-8 py-3 rounded-2xl text-center transition-colors duration-300">2022</a>
            <a href="#" class="year-filter-btn border border-blue-primary text-black font-poppins font-medium text-xl px-8 py-3 rounded-2xl text-center transition-colors duration-300">2023</a>
            <a href="#" class="year-filter-btn border border-blue-primary text-black font-poppins font-medium text-xl px-8 py-3 rounded-2xl text-center transition-colors duration-300">2024</a>
            <a href="#" class="year-filter-btn border border-blue-primary text-black font-poppins font-medium text-xl px-8 py-3 rounded-2xl text-center transition-colors duration-300">2025</a>
        </div>
        <!-- Semester Filters -->
        <div class="flex items-center space-x-4 flex-wrap gap-y-3">
            <a href="#" class="semester-filter-btn bg-blue-primary text-blue-light font-poppins text-[15px] px-6 py-2.5 rounded-2xl transition-colors duration-300">Semester 1</a>
            <a href="#" class="semester-filter-btn border border-blue-primary text-blue-primary font-poppins text-[15px] px-6 py-2.5 rounded-2xl transition-colors duration-300">Semester 2</a>
            <a href="#" class="semester-filter-btn border border-blue-primary text-blue-primary font-poppins text-[15px] px-6 py-2.5 rounded-2xl transition-colors duration-300">Semester 3</a>
            <a href="#" class="semester-filter-btn border border-blue-primary text-blue-primary font-poppins text-[15px] px-6 py-2.5 rounded-2xl transition-colors duration-300">Semester 4</a>
            <a href="#" class="semester-filter-btn border border-blue-primary text-blue-primary font-poppins text-[15px] px-6 py-2.5 rounded-2xl transition-colors duration-300">Semester 5</a>
            <a href="#" class="semester-filter-btn border border-blue-primary text-blue-primary font-poppins text-[15px] px-6 py-2.5 rounded-2xl transition-colors duration-300">Semester 6</a>
            <a href="#" class="semester-filter-btn border border-blue-primary text-blue-primary font-poppins text-[15px] px-6 py-2.5 rounded-2xl transition-colors duration-300">Semester 7</a>
            <a href="#" class="semester-filter-btn border border-blue-primary text-blue-primary font-poppins text-[15px] px-6 py-2.5 rounded-2xl transition-colors duration-300">Semester 8</a>
        </div>
    </div>

    <!-- Course List -->
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
        // --- Logic for Year Buttons ---
        const yearButtons = document.querySelectorAll('.year-filter-btn');
        const yearActiveClasses = ['bg-blue-primary', 'text-blue-light'];
        const yearInactiveClasses = ['border', 'border-blue-primary', 'text-black', 'bg-transparent'];

        yearButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                yearButtons.forEach(btn => {
                    btn.classList.remove(...yearActiveClasses);
                    btn.classList.add(...yearInactiveClasses);
                });
                this.classList.remove(...yearInactiveClasses);
                this.classList.add(...yearActiveClasses);
                console.log('Tahun yang dipilih:', this.textContent.trim());
            });
        });

        // --- Logic for Semester Buttons ---
        const semesterButtons = document.querySelectorAll('.semester-filter-btn');
        const semesterActiveClasses = ['bg-blue-primary', 'text-blue-light'];
        const semesterInactiveClasses = ['border', 'border-blue-primary', 'text-blue-primary', 'bg-transparent'];

        semesterButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                semesterButtons.forEach(btn => {
                    btn.classList.remove(...semesterActiveClasses);
                    btn.classList.add(...semesterInactiveClasses);
                });
                this.classList.remove(...semesterInactiveClasses);
                this.classList.add(...semesterActiveClasses);
                console.log('Semester yang dipilih:', this.textContent.trim());
            });
        });
    });
</script>

@endsection

