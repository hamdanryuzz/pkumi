<!DOCTYPE html>
<html lang="id" :class="{ 'dark': localStorage.theme === 'dark' || (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches) }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Penilaian PKUMI')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
</head>
<body x-data="{ 
    sidebarCollapsed: window.innerWidth >= 1024 ? false : true,
    sidebarHidden: window.innerWidth < 768 ? true : false,
    showOverlay: false,
    timeout: null,
    init() {
        // Dark mode persist
        if (localStorage.theme === 'dark' || (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
        this.handleResize();
        window.addEventListener('resize', () => {
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => this.handleResize(), 150);
        });
    },
    handleResize() {
        if (window.innerWidth < 768) {
            this.sidebarHidden = true;
            this.sidebarCollapsed = false;
            this.showOverlay = false;
        } else {
            this.sidebarHidden = false;
            this.showOverlay = false;
            this.sidebarCollapsed = window.innerWidth < 1024;
        }
    },
    toggleSidebar() {
        if (window.innerWidth < 768) {
            this.sidebarHidden = !this.sidebarHidden;
            this.showOverlay = !this.sidebarHidden;
        } else {
            this.sidebarCollapsed = !this.sidebarCollapsed;
        }
    },
    closeSidebar() {
        if (window.innerWidth < 768) {
            this.sidebarHidden = true;
            this.showOverlay = false;
        }
    }
}" 
:class="{ 
    'sidebar-collapsed': sidebarCollapsed && !sidebarHidden,
    'sidebar-hidden': sidebarHidden 
}">

    <div class="sidebar-overlay" 
         :class="{ 'show': showOverlay }"
         @click="closeSidebar()"></div>

    <button class="toggle-button" @click="toggleSidebar()" 
            aria-expanded="(!sidebarHidden && !sidebarCollapsed)" 
            aria-label="Toggle sidebar">
        <i class="fas" :class="sidebarCollapsed || sidebarHidden ? 'fa-bars' : 'fa-times'"></i>
    </button>

    @include('partials.sidebar')

    <main class="content-wrapper">
        <header class="page-header">
            <h1>
                <i class="fa-solid fa-graduation-cap"></i>
                Sistem Penilaian Mahasiswa
            </h1>
            <img src="{{ asset('images/logo0pkumi.png') }}" alt="Logo PKUMI" loading="lazy">
        </header>

        <div class="main-content-body">
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <ul class="list-none m-0 p-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @yield('scripts')
</body>
</html>