<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PKUMI Dashboard')</title>

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJqLz0E7V43qC3y1+0i1Lz1x5qF4zWz/gq0m9v5qW5g1z29/I1l7eX0l5y9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
        
        /* FIX INLINE STYLES: Classes untuk tabel sidebar */
        .w-col-jenis { width: 129px; }
        .w-col-verif { width: 101px; }

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
            /* Mobile */
            .flex {
                flex-direction: column;
            }
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
                display: block;
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

            /* == COMPLETE HEADER LAYOUT FIX == */
            #section-header {
                padding-bottom: 0.75rem;
            }

            /* Row 1: Hamburger + Search */
            #section-header > .flex.justify-between.items-center {
                flex-direction: row;
                gap: 0.5rem;
                padding: 0;
                margin-bottom: 0.75rem;
                width: 100%;
            }

            #sidebar-toggle-mobile {
                min-width: 40px;
                width: 40px;
                height: 40px;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 0;
                flex-shrink: 0;
            }

            #sidebar-toggle-mobile svg {
                width: 24px;
                height: 24px;
            }

            /* Search Bar - ambil semua space tersedia */
            #section-header .relative.w-\[438px\] {
                flex: 1;
                margin-right: 0;
                width: auto;
                min-width: 0;
            }

            #section-header input[type="text"] {
                height: 40px;
                font-size: 0.875rem;
                padding-left: 2.5rem;
                padding-right: 0.75rem;
                border-radius: 0.5rem;
            }

            #section-header .absolute.inset-y-0.left-0.pl-4 {
                padding-left: 0.5rem;
            }

            #section-header .absolute.inset-y-0.left-0.pl-4 svg {
                width: 20px;
                height: 20px;
            }

            /* Row 2: Notification + Options (Horizontal) */
            #section-header .flex.items-center.space-x-5 {
                display: flex;
                flex-direction: row;
                gap: 0.5rem;
                flex-shrink: 0;
                margin-right: 0;
                padding: 0;
                margin-top: 0;
            }

            #section-header .flex.items-center.space-x-5 > div {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #section-header .flex.items-center.space-x-5 button {
                padding: 0.375rem;
                display: flex;
                align-items: center;
                justify-content: center;
                min-width: 40px;
                min-height: 40px;
                width: 40px;
                height: 40px;
            }

            #section-header .flex.items-center.space-x-5 svg {
                width: 24px;
                height: 24px;
            }

            /* Hide profile picture di mobile */
            #section-header .w-\[50px\] {
                display: none !important;
            }

            /* Notification badge tetap relatif */
            #section-header .absolute.-top-1.-right-1 {
                width: 12px;
                height: 12px;
            }

            /* Dropdown menus responsif */
            #notifications-menu {
                width: calc(100vw - 1.5rem) !important;
                max-width: 320px;
                right: 0;
                left: auto;
            }

            #options-menu {
                width: 180px;
                right: 0;
                left: auto;
            }
            
            /* == FIX INLINE STYLES FOR TABLE IN DRAWER == */
            .sidebar-desktop .w-col-jenis { width: 40% !important; }
            .sidebar-desktop .w-col-verif { width: 30% !important; }
            .sidebar-desktop .bg-white.rounded-2xl {
                font-size: 0.75rem; 
            }
            .sidebar-desktop .bg-brand-dark-header {
                font-size: 0.7rem; 
            }
            .sidebar-desktop .py-2\.5, .sidebar-desktop .py-3\.5 {
                padding-top: 0.4rem !important;
                padding-bottom: 0.4rem !important;
            }
            .sidebar-desktop .flex-1.py-2\.5.px-2\.5.text-sm {
                font-size: 0.65rem;
                line-height: 1;
                padding-top: 0.4rem !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100">

    @php
        $student = Auth::guard('student')->user();
        if ($student) { $student->load(['studentClass', 'grades.course']); }
        $ipk = $student ? number_format($student->ipk, 2, ',', '.') : '0,00';
    @endphp

    <div class="flex min-h-screen">

        <aside id="sidebar" class="sidebar-desktop flex-shrink-0">
            <button id="sidebar-toggle-desktop" aria-label="Toggle Sidebar" class="sidebar-toggle-button hidden lg:flex items-center justify-center w-8 h-8 rounded-full absolute top-1/2 -right-4 transform -translate-y-1/2 bg-blue-primary text-white shadow-md z-20 transition-transform duration-300">
                <i class="fas fa-chevron-left text-sm"></i>
            </button>

            <div class="w-full h-full bg-brand-bg flex flex-col items-center gap-8 pt-[54px] pb-[23px] px-[26px] overflow-y-auto">
                <div class="flex flex-col items-center text-center gap-[23px] sidebar-info-box">
                    <img src="{{ asset('images/xaviera.jpeg') }}" alt="Profile Picture" class="profile-img w-[120px] h-[120px] rounded-full object-cover">
                    <div class="flex flex-col sidebar-text">
                        <h1 class="font-poppins font-semibold text-[25px] leading-tight text-black">{{ $student->name ?? 'Pengguna' }}</h1>
                        <div class="flex flex-col mt-2">
                            <p class="font-poppins text-base text-black/65">NIM : {{ $student->nim ?? 'N/A' }}</p>
                            <p class="font-poppins text-base text-black/65">Program Studi : {{ $student->studentClass->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-black/40 flex flex-col gap-5 rounded-lg shadow-sm w-full sidebar-info-box">
                    <div class="pl-[29px] pr-[26px] pt-[22px] pb-[6px]">
                        <div class="flex flex-col gap-[7px]">
                            <p class="font-inter font-medium text-[14px] text-brand-gpa-label sidebar-text">Indeks Prestasi Kumulatif</p>
                            <div class="flex items-center gap-[19px]">
                                <span class="gpa-value font-inter font-semibold text-[32.5px] leading-none text-brand-gpa-value tracking-[-1.5px]">{{ $ipk }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-7 px-7 pb-6 -mt-3 gpa-pagination sidebar-text">
                        <a href="#" aria-label="Page 1" class="bg-brand-active-page text-white font-inter text-[13px] rounded-md flex items-center justify-center w-[45px] h-[35px]">1</a>
                        <a href="#" aria-label="Page 2" class="text-brand-page-inactive font-inter text-[13px]">2</a>
                        <a href="#" aria-label="Page 3" class="text-brand-page-inactive font-inter text-[13px]">3</a>
                        <a href="#" aria-label="Next Page" class="ml-auto">
                            <svg class="w-6 h-6 text-brand-page-inactive" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl overflow-hidden w-full shadow-sm sidebar-info-box">
                    <div class="flex bg-brand-dark-header text-white font-poppins font-semibold text-base text-center">
                        <div class="py-2.5 px-2.5 w-col-jenis">Jenis</div>
                        <div class="py-2.5 px-2.5 w-col-verif">Terverifikasi</div>
                        <div class="flex-1 py-2.5 px-2.5 text-sm">Belum di<br>Verifikasi</div>
                    </div>
                    <div class="font-poppins font-semibold text-base text-black text-center">
                        <div class="flex border-b border-black/40">
                            <div class="w-col-jenis py-3.5 px-2.5 border-r border-black/40 flex items-center justify-center">Khazanah</div>
                            <div class="w-col-verif py-3.5 px-2.5 border-r border-black/40 flex items-center justify-center">12</div>
                            <div class="flex-1 py-3.5 px-2.5 flex items-center justify-center">7</div>
                        </div>
                        <div class="flex">
                             <div class="w-col-jenis py-3.5 px-2.5 border-r border-black/40 rounded-bl-2xl flex items-center justify-center">Rubik</div>
                             <div class="w-col-verif py-3.5 px-2.5 border-r border-black/40 flex items-center justify-center">5</div>
                            <div class="flex-1 py-3.5 px-2.5 rounded-br-2xl flex items-center justify-center">3</div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        
        <div id="sidebar-overlay" class="bg-overlay"></div>

        <main id="main-content" class="main-content flex-grow p-6">
            
            <header id="section-header" class="pb-6">
                <div class="flex justify-between items-center">
                    <button id="sidebar-toggle-mobile" aria-label="Open Sidebar Menu" class="focus:outline-none mr-4">
                        <svg class="h-8 w-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                    </button>
                    
                    <div class="relative w-[438px] flex-grow md:mr-4">
                        <form action="{{ route('mahasiswa.dashboard') }}" method="GET">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Search Class, Documents, Activities..." 
                                class="w-full h-[50px] pl-12 pr-4 py-2 border border-blue-primary rounded-lg text-base font-semibold font-sans placeholder-gray-placeholder focus:outline-none focus:ring-2 focus:ring-blue-primary">
                            <input type="hidden" name="period_id" value="{{ request('period_id') }}">
                            <input type="hidden" name="semester_id" value="{{ request('semester_id') }}">
                        </form>
                    </div>

                    <div class="flex items-center space-x-5 flex-shrink-0">
                        <div class="relative">
                            <button id="notifications-menu-button" aria-label="Show notifications" class="focus:outline-none p-1 rounded-full">
                                <div class="relative">
                                    <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-sans font-bold text-[6px]">1</span>
                                    </div>
                                </div>
                            </button>
                            <div id="notifications-menu" class="hidden absolute right-0 mt-2 w-72 md:w-80 bg-white rounded-md shadow-lg py-1 z-20 ring-1 ring-black ring-opacity-5 dropdown-menu">
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

                        <div class="relative">
                            <button id="options-menu-button" aria-label="Show options menu" class="focus:outline-none p-1 rounded-full hover:bg-gray-200 transition-colors">
                                <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" /></svg>
                            </button>
                            <div id="options-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20 ring-1 ring-black ring-opacity-5 dropdown-menu">
                                <a href="{{ route('mahasiswa.profile') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    <span>Profile</span>
                                </a >
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
                            <img src="{{ asset('images/xaviera.jpeg') }}" alt="Profile Picture" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </header>

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
                    if (isOpen) {
                        overlay.classList.add('visible');
                    } else {
                        overlay.classList.remove('visible');
                    }
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
            
            // Menutup semua dropdown jika klik di luar
            window.addEventListener('click', function (event) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    const button = document.getElementById(menu.getAttribute('aria-labelledby'));
                    if (!menu.classList.contains('hidden') && (!button || !button.contains(event.target)) && !menu.contains(event.target)) {
                        menu.classList.add('hidden');
                    }
                });
            });

            // Setup untuk dropdown notifikasi dan options
            setupDropdown('notifications-menu-button', 'notifications-menu');
            setupDropdown('options-menu-button', 'options-menu');
            
            // Mengatasi kasus saat transisi dari mobile ke desktop dan sebaliknya
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('visible'); 
                } else {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('expanded');
                }
            });

            // Logic untuk form submit saat search bar di-enter
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
</body>
</html>