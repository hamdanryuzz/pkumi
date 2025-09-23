<nav class="sidebar-container" 
     :class="{ 'show': !sidebarHidden }"
     @click.away="window.innerWidth < 768 && closeSidebar()"
     role="navigation" aria-label="Navigasi utama">
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
        <p class="nav-title">Menu Utama</p>
        <nav class="main-nav">
            <ul>
                <li>
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                        <i class="fa-solid fa-tachometer-alt nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                        <i class="fa-solid fa-chevron-right nav-arrow"></i>
                        <div class="nav-tooltip">Dashboard</div>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" @click="window.innerWidth < 768 && closeSidebar()">
                        <i class="fa-solid fa-graduation-cap nav-icon"></i>
                        <span class="nav-text">Manage Angkatan & Kelas</span>
                        <i class="fa-solid fa-chevron-right nav-arrow"></i>
                        <div class="nav-tooltip">Manage Angkatan & Kelas</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                        <i class="fa-solid fa-users nav-icon"></i>
                        <span class="nav-text">Manage Mahasiswa</span>
                        <i class="fa-solid fa-chevron-right nav-arrow"></i>
                        <div class="nav-tooltip">Manage Mahasiswa</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('grades.index') }}" class="nav-link {{ request()->routeIs('grades.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                        <i class="fa-solid fa-clipboard-list nav-icon"></i>
                        <span class="nav-text">Manage Nilai</span>
                        <i class="fa-solid fa-chevron-right nav-arrow"></i>
                        <div class="nav-tooltip">Manage Nilai</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('grade-weights.index') }}" class="nav-link {{ request()->routeIs('grade-weights.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                        <i class="fa-solid fa-balance-scale-left nav-icon"></i>
                        <span class="nav-text">Manage Bobot Nilai</span>
                        <i class="fa-solid fa-chevron-right nav-arrow"></i>
                        <div class="nav-tooltip">Manage Bobot Nilai</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" @click="window.innerWidth < 768 && closeSidebar()">
                        <i class="fa-solid fa-chart-bar nav-icon"></i>
                        <span class="nav-text">Laporan</span>
                        <i class="fa-solid fa-chevron-right nav-arrow"></i>
                        <div class="nav-tooltip">Laporan</div>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link" @click="window.innerWidth < 768 && closeSidebar()">
                        <i class="fa-solid fa-history nav-icon"></i>
                        <span class="nav-text">Log History</span>
                        <i class="fa-solid fa-chevron-right nav-arrow"></i>
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
            <p class="user-name">{{ auth()->user()->name ?? 'Admin User' }}</p>
            <p class="user-role">Administrator</p>
        </div>
        <button class="ml-auto p-2 rounded hover:bg-[var(--color-bg-accent)] transition-colors" onclick="document.documentElement.classList.toggle('dark'); localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';" aria-label="Toggle dark mode">
            <i class="fa-solid fa-moon"></i>
        </button>
        <a href="#" class="logout-button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" aria-label="Logout dari akun" title="Logout">
            <i class="fa-solid fa-right-from-bracket"></i>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </footer>
</nav>