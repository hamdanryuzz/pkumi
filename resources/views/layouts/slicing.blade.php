<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PKUMI Dashboard')</title>

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJqLz0E7V43qC3y1+0i1Lz1x5qF4zWz/gq0m9v5qW5g1z29/I1l7eX0l5y9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        :root {
            /* Variabel CSS Anda */
            --brand-bg: #E6F1FF;
            --brand-gpa-label: #7D7D7D;
            --brand-gpa-value: #1B2559;
            --brand-active-page: #717171;
            --brand-page-inactive: #C0C0C0;
            --brand-dark-header: #353535;

            /* Variabel Responsif */
            --sidebar-width: 365px;
            --sidebar-collapsed-width: 100px;
            --blue-primary: #007BFF;
        }

        /* Custom Classes */
        .bg-brand-bg { background-color: var(--brand-bg); }
        .text-brand-gpa-label { color: var(--brand-gpa-label); }
        .text-brand-gpa-value { color: var(--brand-gpa-value); }
        .bg-brand-active-page { background-color: var(--brand-active-page); }
        .text-brand-page-inactive { color: var(--brand-page-inactive); }
        .bg-brand-dark-header { background-color: var(--brand-dark-header); }
        .bg-blue-primary { background-color: var(--blue-primary); }
        .text-blue-light { color: #FFFFFF; }
        .text-gray-placeholder { color: #A0AEC0; }
        
        /* Visually Hidden for Accessibility */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        /* ---------------------------------------------------- */
        /* COLLAPSE/EXPAND CSS (Desktop Only) */
        /* ---------------------------------------------------- */
        .sidebar-desktop {
            width: var(--sidebar-width);
            transition: width 0.3s ease-in-out;
            position: sticky;
            top: 0;
            height: 100vh;
        }
        .main-content {
            width: calc(100% - var(--sidebar-width));
            transition: width 0.3s ease-in-out;
            min-height: 100vh;
        }
        .sidebar-desktop.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        .main-content.expanded {
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        .sidebar-desktop.collapsed .sidebar-text,
        .sidebar-desktop.collapsed .sidebar-info-box {
            opacity: 0;
            height: 0;
            overflow: hidden;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        .sidebar-desktop.collapsed .profile-img {
            width: 70px !important;
            height: 70px !important;
        }
        .sidebar-desktop.collapsed .sidebar-toggle-button {
            transform: rotate(180deg);
        }

        /* ---------------------------------------------------- */
        /* RESPONSIVE LAYOUT (Mobile & Tablet) */
        /* ---------------------------------------------------- */
        #sidebar-toggle-mobile {
            display: none;
        }

        @media (max-width: 1024px) {
            /* Tablet */
            .sidebar-desktop {
                width: 250px !important;
                --sidebar-width: 250px;
            }
            .main-content {
                width: calc(100% - 250px) !important;
            }
            .sidebar-desktop.collapsed {
                width: 80px !important;
                --sidebar-collapsed-width: 80px;
            }
        }

        @media (max-width: 767px) {
            /* Mobile Drawer Logic */
            .sidebar-desktop {
                position: fixed;
                top: 0;
                left: -100%;
                width: 70vw !important;
                height: 100vh;
                z-index: 50;
                box-shadow: 2px 0 5px rgba(0,0,0,0.5);
                transition: left 0.3s ease-in-out;
            }
            .sidebar-desktop.open {
                left: 0;
            }
            .main-content {
                width: 100% !important;
                padding: 0.75rem !important;
            }
            #sidebar-toggle-mobile {
                display: block; /* Menjadi 'flex' atau 'block' oleh JS */
            }
            #sidebar-toggle-desktop {
                display: none;
            }
            .bg-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 40;
                display: none;
            }
            .bg-overlay.visible {
                display: block;
            }
        }
    </style>
</head>
<body class="bg-gray-100">

    @php
        $student = Auth::guard('student')->user();
        if ($student) { $student->load(['studentClass', 'grades.course']); }
        $ipk = $student ? number_format($student->ipk, 2, ',', '.') : '0,00';
        
        // Asumsi kamu mengirim data ini dari ViewServiceProvider atau Controller
        $khazanahVerifiedCount = $khazanahVerifiedCount ?? 0;
        $khazanahUnverifiedCount = $khazanahUnverifiedCount ?? 0;
        $rubrikVerifiedCount = $rubrikVerifiedCount ?? 0;
        $rubrikUnverifiedCount = $rubrikUnverifiedCount ?? 0;
    @endphp

    <div class="flex min-h-screen">

        <aside id="sidebar" class="sidebar-desktop flex-shrink-0">
            <button id="sidebar-toggle-desktop" aria-label="Toggle Sidebar" class="sidebar-toggle-button hidden lg:flex items-center justify-center w-8 h-8 rounded-full absolute top-1/2 -right-4 transform -translate-y-1/2 bg-blue-primary text-white shadow-md z-20 transition-transform duration-300">
                <i class="fas fa-chevron-left text-sm"></i>
            </button>

            <div class="w-full h-full bg-brand-bg flex flex-col items-center gap-8 pt-[54px] pb-[23px] px-[26px] overflow-y-auto">
                
                {{-- Info Profil Mahasiswa --}}
                <div class="flex flex-col items-center text-center gap-[23px] sidebar-info-box">
                    @if($student->image)
                        <img src="{{ asset('storage/students/' . $student->image) }}" 
                             alt="Profile Picture" 
                             class="profile-img w-[120px] h-[120px] rounded-full object-cover">
                    @else
                        <div class="profile-img w-[120px] h-[120px] bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-3xl font-bold">
                                {{ strtoupper(substr($student->name, 0, 2)) }}
                            </span>
                        </div>
                    @endif

                    <div class="flex flex-col sidebar-text">
                        <h1 class="font-poppins font-semibold text-[25px] leading-tight text-black">{{ $student->name ?? 'Pengguna' }}</h1>
                        <div class="flex flex-col mt-2">
                            <p class="font-poppins text-base text-black/65">NIM : {{ $student->nim ?? 'N/A' }}</p>
                            <p class="font-poppins text-base text-black/65">Program Studi : {{ $student->studentClass->name ?? 'N/A' }}</p>
                            <p class="font-poppins text-base text-black/65">Semester : {{ $student->unique_semesters_count ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Navigasi <nav> yang salah tadi SUDAH DIHAPUS --}}

                {{-- Tabel Info Verifikasi (SEKARANG BISA DIKLIK) --}}
                <div class="bg-white rounded-2xl overflow-hidden w-full shadow-sm sidebar-info-box">
                    <div class="flex bg-brand-dark-header text-white font-poppins font-semibold text-center">
                        <div class="py-2.5 px-2.5 w-[40%] lg:w-[129px] text-sm md:text-base">Jenis</div>
                        <div class="py-2.5 px-2.5 w-[30%] lg:w-[101px] text-sm md:text-base">Terverifikasi</div>
                        <div class="flex-1 py-2.5 px-2.5 text-xs md:text-sm">Belum di<br>Verifikasi</div>
                    </div>
                    
                    <div class="font-poppins font-semibold text-black text-center text-sm md:text-base">
                        
                        {{-- Baris Khazanah (Link) --}}
                        <a href="{{ route('mahasiswa.khazanah.create') }}" 
                           class="flex border-b border-black/40 transition-colors duration-200 hover:bg-blue-50 hover:text-blue-primary">
                            
                            <div class="w-[40%] lg:w-[129px] py-3.5 px-2.5 border-r border-black/40 flex items-center justify-center">
                                Khazanah
                            </div>
                            <div class="w-[30%] lg:w-[101px] py-3.5 px-2.5 border-r border-black/40 flex items-center justify-center">
                                {{ $khazanahVerifiedCount }}
                            </div>
                            <div class="flex-1 py-3.5 px-2.5 flex items-center justify-center">
                                {{ $khazanahUnverifiedCount }}
                            </div>
                        </a>
                        
                        {{-- Baris Rubrik (Link) --}}
                        <a href="{{ route('mahasiswa.rubrik-opini.create') }}" 
                           class="flex transition-colors duration-200 hover:bg-blue-50 hover:text-blue-primary">
                            
                             <div class="w-[40%] lg:w-[129px] py-3.5 px-2.5 border-r border-black/40 rounded-bl-2xl flex items-center justify-center">
                                 Rubrik {{-- Typo diperbaiki --}}
                             </div>
                             <div class="w-[30%] lg:w-[101px] py-3.5 px-2.5 border-r border-black/40 flex items-center justify-center">
                                 {{ $rubrikVerifiedCount }}
                             </div>
                            <div class="flex-1 py-3.5 px-2.5 rounded-br-2xl flex items-center justify-center">
                                 {{ $rubrikUnverifiedCount }}
                            </div>
                        </a>

                    </div>
                </div>
            </div>
        </aside>
        
        <div id="sidebar-overlay" class="bg-overlay"></div>

        <main id="main-content" class="main-content flex-grow p-3 md:p-6">
            
            {{-- HEADER: Search, Notif, Profile --}}
            <header id="section-header" class="pb-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
                    
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <button id="sidebar-toggle-mobile" aria-label="Open Sidebar Menu" class="flex-shrink-0 focus:outline-none p-2 rounded-lg text-gray-600 hover:bg-gray-200 md:hidden">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                        </button>
                        
                        <div class="relative flex-grow">
                            <form action="{{ route('mahasiswa.dashboard') }}" method="GET">
                                <div class="absolute inset-y-0 left-0 pl-3 md:pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 md:h-6 md:w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Search..." 
                                       class="w-full h-[44px] md:h-[50px] pl-10 md:pl-12 pr-4 py-2 border border-blue-primary rounded-lg text-base font-semibold font-sans placeholder-gray-placeholder focus:outline-none focus:ring-2 focus:ring-blue-primary md:w-[438px]">
                                <input type="hidden" name="period_id" value="{{ request('period_id') }}">
                                <input type="hidden" name="semester_id" value="{{ request('semester_id') }}">
                            </form>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 md:space-x-5 flex-shrink-0 justify-end">
                        
                        <div class="relative">
                            <button id="options-menu-button" aria-label="Show options menu" class="focus:outline-none p-2 rounded-full hover:bg-gray-200 transition-colors">
                                <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" /></svg>
                            </button>
                            <div id="options-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 ring-1 ring-black ring-opacity-5 dropdown-menu" role="menu" aria-labelledby="options-menu-button">
                                <a href="{{ route('mahasiswa.profile') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    <span>Profile</span>
                                </a>
                                <hr class="my-1 border-gray-200">
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
                        
                        <div class="w-[50px] h-[50px] rounded-lg border border-blue-primary overflow-hidden hidden md:block">
                            <a href="{{ route('mahasiswa.profile') }}">
                                @if($student->image)
                                    <img src="{{ asset('storage/students/' . $student->image) }}" 
                                         alt="Profile Picture" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                        <span class="text-white text-xl font-bold">
                                            {{ strtoupper(substr($student->name, 0, 2)) }}
                                        </span>
                                    </div>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            @yield('content')
        </main>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const desktopToggle = document.getElementById('sidebar-toggle-desktop');
            const mobileToggle = document.getElementById('sidebar-toggle-mobile');
            const overlay = document.getElementById('sidebar-overlay');
            
            // --- Sidebar Toggle Logic (Desktop & Mobile) ---
            window.toggleSidebar = function() {
                if (window.innerWidth < 768) {
                    const isOpen = sidebar.classList.toggle('open');
                    overlay.classList.toggle('visible', isOpen);
                } else {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                }
            };

            // Setup Event Listeners
            if (desktopToggle) {
                desktopToggle.addEventListener('click', window.toggleSidebar);
            }
            if (mobileToggle) {
                mobileToggle.addEventListener('click', window.toggleSidebar);
            }
            if (overlay) {
                 overlay.addEventListener('click', window.toggleSidebar); 
            }

            // --- Dropdown Logic ---
            function setupDropdown(buttonId, menuId) {
                const button = document.getElementById(buttonId);
                const menu = document.getElementById(menuId);
                if (!button || !menu) return;

                button.addEventListener('click', function (event) {
                    event.stopPropagation();
                    document.querySelectorAll('.dropdown-menu').forEach(otherMenu => {
                        if (otherMenu.id !== menuId) {
                            otherMenu.classList.add('hidden');
                        }
                    });
                    menu.classList.toggle('hidden');
                });
            }
            
            window.addEventListener('click', function (event) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    const button = document.getElementById(menu.getAttribute('aria-labelledby'));
                    if (!menu.classList.contains('hidden') && (!button || !button.contains(event.target)) && !menu.contains(event.target)) {
                        menu.classList.add('hidden');
                    }
                });
            });

            setupDropdown('options-menu-button', 'options-menu');
            
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('visible'); 
                } else {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('expanded');
                }
            });

            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        searchInput.closest('form').submit();
                    }
                });
            }

            window.addEventListener('scroll', function() {
                if (window.innerWidth <= 767) {
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        if (!menu.classList.contains('hidden')) {
                            menu.classList.add('hidden');
                        }
                    });
                }
            }, { passive: true });
        });
    </script>
</body>
</html>

