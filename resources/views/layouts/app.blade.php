<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Penilaian PKUMI')</title>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        :root {
            --color-primary-start: #3c83f6;
            --color-primary-end: #0a5adb;
            --color-text-light: #ffffff;
            --color-text-medium: rgba(255, 255, 255, 0.8);
            --color-text-muted: rgba(255, 255, 255, 0.7);
            --color-text-faded: rgba(255, 255, 255, 0.6);
            --color-bg-accent: rgba(255, 255, 255, 0.2);
            --color-border-light: rgba(255, 255, 255, 0.1);
            --color-border-main: #e5e7eb;
            --sidebar-width: 256px;
            --sidebar-collapsed-width: 70px;
        }

        .sidebar-container {
            display: flex;
            flex-direction: column;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--color-primary-start) 0%, var(--color-primary-end) 100%);
            border-right: 1px solid var(--color-border-main);
            color: var(--color-text-light);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .sidebar-collapsed .sidebar-container {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-hidden .sidebar-container {
            transform: translateX(-100%);
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding: 24px 16px;
            border-bottom: 1px solid var(--color-border-light);
            gap: 12px;
            flex-shrink: 0;
            min-height: 80px;
        }

        .sidebar-footer {
            display: flex;
            align-items: center;
            padding: 18px 16px;
            border-top: 1px solid var(--color-border-light);
            gap: 12px;
            flex-shrink: 0;
            min-height: 70px;
        }

        .icon-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            background-color: var(--color-bg-accent);
            border-radius: 8px;
            flex-shrink: 0;
        }

        .icon-wrapper .fa-solid {
            font-size: 20px;
        }

        .brand-info, .user-info {
            opacity: 1;
            transition: opacity 0.2s ease;
            overflow: hidden;
        }

        .sidebar-collapsed .brand-info,
        .sidebar-collapsed .user-info,
        .sidebar-collapsed .nav-text,
        .sidebar-collapsed .nav-title {
            opacity: 0;
            pointer-events: none;
        }

        .brand-name {
            font-size: 18px;
            font-weight: 700;
            line-height: 28px;
            color: var(--color-text-light);
            margin: 0;
            white-space: nowrap;
        }

        .brand-subtitle {
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            color: var(--color-text-muted);
            margin: 0;
            white-space: nowrap;
        }

        .sidebar-content {
            flex-grow: 1;
            padding: 32px 16px 16px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-collapsed .sidebar-content {
            padding: 32px 8px 16px;
        }

        .nav-title {
            font-size: 12px;
            font-weight: 400;
            line-height: 16px;
            letter-spacing: 0.6px;
            color: var(--color-text-faded);
            margin: 0 0 24px 12px;
            text-transform: uppercase;
            transition: opacity 0.2s ease;
        }
        /* Perbaikan: Atur agar nav-title tidak terlihat saat collapsed */
        .sidebar-collapsed .nav-title {
            opacity: 0;
            height: 0;
            margin: 0;
            padding: 0;
        }


        .main-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .main-nav li {
            margin-bottom: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            color: var(--color-text-medium);
            transition: all 0.2s ease;
            position: relative;
            white-space: nowrap;
        }

        .sidebar-collapsed .nav-link {
            justify-content: center;
            padding: 12px 8px;
        }

        .nav-link:hover {
            background-color: var(--color-bg-accent);
            color: var(--color-text-light);
            transform: translateX(4px);
        }

        .sidebar-collapsed .nav-link:hover {
            transform: none;
        }

        .nav-link.active {
            background-color: var(--color-bg-accent);
            color: var(--color-text-light);
            box-shadow: 0px 2px 4px -2px rgba(0, 0, 0, 0.1), 0px 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .nav-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .nav-text {
            transition: opacity 0.2s ease;
            flex: 1;
        }
        /* Perbaikan: Atur agar nav-text tidak terlihat saat collapsed */
        .sidebar-collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }


        .nav-arrow {
            margin-left: auto;
            width: 16px;
            height: 16px;
            transition: all 0.2s ease;
            opacity: 0;
            transform: translateX(-8px);
        }

        .nav-link:hover .nav-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        .sidebar-collapsed .nav-arrow {
            display: none;
        }

        .user-name {
            font-size: 14px;
            font-weight: 500;
            line-height: 20px;
            color: var(--color-text-light);
            margin: 0;
            white-space: nowrap;
        }

        .user-role {
            font-size: 12px;
            font-weight: 400;
            line-height: 16px;
            color: var(--color-text-faded);
            margin: 0;
            white-space: nowrap;
        }

        .logout-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            transition: background-color 0.2s ease;
            flex-shrink: 0;
        }

        .logout-button:hover {
            background-color: var(--color-bg-accent);
        }

        .toggle-button {
            position: fixed;
            top: 24px;
            left: 20px;
            z-index: 1001;
            background: linear-gradient(135deg, var(--color-primary-start), var(--color-primary-end));
            color: white;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .toggle-button:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        /* Perbaikan: Atur posisi tombol agar tidak terpengaruh oleh sidebar */
        .sidebar-collapsed .toggle-button {
            left: 20px;
        }

        .sidebar-hidden .toggle-button {
            left: 20px;
        }

        .content-wrapper {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar-collapsed .content-wrapper {
            margin-left: var(--sidebar-collapsed-width);
        }

        .sidebar-hidden .content-wrapper {
            margin-left: 0;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            background-color: #fff;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 0;
            margin-left: 0;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            min-height: 80px;
        }
        
        .page-header h1 {
            font-size: 20px;
            font-weight: 700;
            line-height: 28px;
            color: #1d2025;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .page-header p {
            font-size: 14px;
            line-height: 20px;
            color: #6b7280;
            margin: 0;
        }

        .main-content-body {
            flex-grow: 1;
            padding: 24px;
        }

        /* Tooltip for collapsed sidebar */
        .nav-tooltip {
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            z-index: 1002;
            pointer-events: none;
        }

        .nav-tooltip::before {
            content: '';
            position: absolute;
            left: -4px;
            top: 50%;
            transform: translateY(-50%);
            border: 4px solid transparent;
            border-right-color: rgba(0, 0, 0, 0.8);
        }

        .sidebar-collapsed .nav-link:hover .nav-tooltip {
            opacity: 1;
            visibility: visible;
        }

        @media (max-width: 768px) {
            .sidebar-container {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }

            .sidebar-container.show {
                transform: translateX(0);
            }

            .content-wrapper {
                margin-left: 0;
            }

            .page-header p {
                display: none;
            }
        }

        @media (max-width: 640px) {
            .toggle-button {
                top: 16px;
                left: 16px;
                width: 40px;
                height: 40px;
            }

            .main-content-body {
                padding: 16px;
            }
        }

        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body x-data="{ 
    sidebarCollapsed: window.innerWidth >= 1024 ? false : false,
    sidebarHidden: window.innerWidth < 768 ? true : false,
    showOverlay: false,
    init() {
        this.handleResize();
        window.addEventListener('resize', () => this.handleResize());
    },
    handleResize() {
        if (window.innerWidth < 768) {
            this.sidebarHidden = true;
            this.sidebarCollapsed = false;
            this.showOverlay = false;
        } else {
            this.sidebarHidden = false;
            this.showOverlay = false;
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

    <button class="toggle-button" @click="toggleSidebar()">
        <i class="fas" :class="sidebarCollapsed || sidebarHidden ? 'fa-bars' : 'fa-times'"></i>
    </button>

    <nav class="sidebar-container" 
         :class="{ 'show': !sidebarHidden }"
         @click.away="window.innerWidth < 768 && closeSidebar()">
        <header class="sidebar-header">
            <div class="icon-wrapper">
                <i class="fa-solid fa-crown"></i>
            </div>
            <div class="brand-info">
                <h1 class="brand-name">PKUMI</h1>
                <p class="brand-subtitle">System</p>
            </div>
        </header>

        <div class="sidebar-content">
            <nav class="main-nav">
                <ul>
                    <li>
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-tachometer-alt nav-icon"></i>
                            <span class="nav-text">Dashboard</span>
                            <div class="nav-tooltip">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-graduation-cap nav-icon"></i>
                            <span class="nav-text">Manage Angkatan & Kelas</span>
                            <div class="nav-tooltip">Manage Angkatan & Kelas</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-users nav-icon"></i>
                            <span class="nav-text">Manage Mahasiswa</span>
                            <div class="nav-tooltip">Manage Mahasiswa</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('grades.index') }}" class="nav-link {{ request()->routeIs('grades.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-clipboard-list nav-icon"></i>
                            <span class="nav-text">Manage Nilai</span>
                            <div class="nav-tooltip">Manage Nilai</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('grade-weights.index') }}" class="nav-link {{ request()->routeIs('grade-weights.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-balance-scale-left nav-icon"></i>
                            <span class="nav-text">Manage Bobot Nilai</span>
                            <div class="nav-tooltip">Manage Bobot Nilai</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-chart-bar nav-icon"></i>
                            <span class="nav-text">Laporan</span>
                            <div class="nav-tooltip">Laporan</div>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link" @click="window.innerWidth < 768 && closeSidebar()">
                            <i class="fa-solid fa-history nav-icon"></i>
                            <span class="nav-text">Log History</span>
                            <div class="nav-tooltip">Log History</div>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <footer class="sidebar-footer">
            <div class="icon-wrapper">
                <i class="fa-solid fa-circle-user"></i>
            </div>
            <div class="user-info">
                <p class="user-name">Admin User</p>
                <p class="user-role">Administrator</p>
            </div>
            <a href="#" class="logout-button" aria-label="Logout dari akun" title="Logout">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </footer>
    </nav>

    <main class="content-wrapper">
        <header class="page-header">
            <h1>
                <i class="fa-solid fa-graduation-cap"></i>
                Sistem Penilaian Mahasiswa
            </h1>
            <p>Welcome back!</p>
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
                    <ul class="list-none m-0">
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