<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration - e-Déclaration TG')</title>

    {{-- Anti‑flash blanc – exécuté immédiatement --}}
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('darkMode');
                const isDark = savedTheme === 'dark';
                if (isDark) {
                    document.documentElement.style.backgroundColor = '#0f172a';
                    document.body.style.backgroundColor = '#0f172a';
                    document.documentElement.classList.add('dark-mode');
                } else {
                    document.documentElement.style.backgroundColor = '#f8f9fa';
                    document.body.style.backgroundColor = '#f8f9fa';
                }
            } catch(e) {}
        })();
    </script>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Styles globaux pour l’espace admin -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --primary-light: #a3bffa;
            --secondary: #3498db;
            --success: #27ae60;
            --danger: #e74c3c;
            --warning: #f39c12;
            --info: #3b82f6;
            --dark: #0f172a;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-600: #64748b;
            --gray-800: #1e293b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            transition: background 0.2s ease;
        }

        body.dark-mode {
            background: #0f172a;
            color: #e5e7eb;
        }

        /* Sidebar fixe admin */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            z-index: 1000;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            overflow-y: hidden;
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
            transition: background 0.2s;
        }

        body.dark-mode .admin-sidebar {
            background: linear-gradient(135deg, #1e1b4b 0%, #4c1d95 100%);
        }

        .admin-sidebar .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .admin-sidebar .logo h4 {
            font-size: 1.3rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .admin-sidebar .republic-text {
            font-size: 0.65rem;
            opacity: 0.8;
            text-align: center;
            margin-top: 0.2rem;
        }

        .nav-link {
            color: rgba(255,255,255,0.9);
            transition: all 0.3s;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.3rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.15);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
            font-weight: 600;
        }

        .logout-container {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.2);
        }

        .btn-logout-sidebar {
            background: rgba(231, 76, 60, 0.8);
            border: none;
            color: white;
            width: 100%;
            text-align: left;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-logout-sidebar:hover {
            background: #e74c3c;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
        }

        /* Main content */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
            background: #f8f9fa;
            transition: background 0.2s;
        }

        body.dark-mode .main-content {
            background: #0f172a;
        }

        /* Header */
        .admin-header {
            background: white;
            border-radius: 16px;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            transition: background 0.2s;
        }

        body.dark-mode .admin-header {
            background: #1e293b;
        }

        .welcome-text h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        body.dark-mode .welcome-text h2 {
            color: #f1f5f9;
        }

        .welcome-text p {
            color: #6c757d;
            margin: 0.25rem 0 0;
            font-size: 0.9rem;
        }

        body.dark-mode .welcome-text p {
            color: #94a3b8;
        }

        /* Horloge et thème */
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .date-time {
            font-size: 0.85rem;
            color: var(--gray-600);
            background: var(--gray-100);
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-weight: 500;
        }

        body.dark-mode .date-time {
            background: #334155;
            color: #94a3b8;
        }

        .icon-btn {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            padding: 0.45rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            width: 38px;
            height: 38px;
        }

        body.dark-mode .icon-btn {
            background: #1e293b;
            border-color: #4b5563;
        }

        .icon-btn svg {
            width: 18px;
            height: 18px;
            stroke: var(--gray-600);
        }

        body.dark-mode .icon-btn svg {
            stroke: #9ca3af;
        }

        .icon-btn:hover {
            border-color: var(--primary);
            background: rgba(102, 126, 234, 0.08);
        }

        /* Profil dropdown */
        .profile-dropdown {
            position: relative;
            cursor: pointer;
        }

        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem 1rem;
            background: #f8f9fa;
            border-radius: 50px;
            transition: all 0.3s;
            border: 1px solid #e9ecef;
        }

        body.dark-mode .profile-trigger {
            background: #334155;
            border-color: #4b5563;
        }

        .profile-trigger:hover {
            background: #f1f5f9;
            border-color: var(--primary);
        }

        .profile-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .profile-info {
            text-align: right;
        }

        .profile-name {
            font-weight: 700;
            color: #2c3e50;
            font-size: 0.95rem;
        }

        body.dark-mode .profile-name {
            color: #e5e7eb;
        }

        .profile-role {
            font-size: 0.75rem;
            color: #6c757d;
        }

        body.dark-mode .profile-role {
            color: #94a3b8;
        }

        .dropdown-icon {
            color: #6c757d;
            transition: transform 0.3s;
        }

        .profile-dropdown.active .dropdown-icon {
            transform: rotate(180deg);
        }

        .dropdown-menu-custom {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 280px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
            z-index: 1000;
        }

        body.dark-mode .dropdown-menu-custom {
            background: #1e293b;
        }

        .profile-dropdown.active .dropdown-menu-custom {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px 16px 0 0;
            color: white;
            text-align: center;
        }

        .dropdown-header .avatar-large {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .dropdown-header .user-email {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .dropdown-divider {
            height: 1px;
            background: #e9ecef;
            margin: 0.5rem 0;
        }

        body.dark-mode .dropdown-divider {
            background: #334155;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: #2c3e50;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        body.dark-mode .dropdown-item {
            color: #e5e7eb;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
        }

        body.dark-mode .dropdown-item:hover {
            background: #334155;
        }

        .dropdown-item.text-danger:hover {
            background: #fee2e2;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .admin-sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }
            .main-content {
                margin-left: 0;
            }
            .admin-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .header-right {
                align-self: flex-end;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Sidebar admin -->
<div class="admin-sidebar">
    <div class="logo">
        <h4>
            <span>🇹🇬</span> e-Déclaration TG
        </h4>
        <div class="republic-text">RÉPUBLIQUE TOGOLAISE</div>
    </div>
    <nav class="nav flex-column">
        @yield('sidebar-nav')
        <!-- Exemple de contenu par défaut (peut être surchargé par les vues) -->
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            📊 Tableau de bord
        </a>
        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
            👤 Utilisateurs
        </a>
        <a class="nav-link {{ request()->routeIs('admin.types-pieces.*') ? 'active' : '' }}" href="{{ route('admin.types-pieces.index') }}">
            🪪 Types de pièces
        </a>
        <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
            🔐 Rôles & droits
        </a>
        <a class="nav-link" href="#">
            📈 Statistiques
        </a>
    </nav>

    <div class="logout-container">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout-sidebar">
                🚪 Se déconnecter
            </button>
        </form>
    </div>
</div>

<!-- Main content -->
<div class="main-content">
    <!-- Header -->
    <div class="admin-header">
        <div class="welcome-text">
            <h2>@yield('header-title', 'Tableau de bord')</h2>
            <p>@yield('header-subtitle', 'Administration générale')</p>
        </div>
        <div class="header-right">
            <div class="date-time" id="currentDateTime">
                {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY - HH:mm') }}
            </div>
            <button class="icon-btn theme-toggle" id="themeToggleBtn" title="Changer le thème">
                <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>
            <!-- Profil dropdown -->
            <div class="profile-dropdown" id="profileDropdown">
                <div class="profile-trigger" onclick="toggleDropdown()">
                    <div class="profile-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="profile-info">
                        <div class="profile-name">{{ Auth::user()->name }}</div>
                        <div class="profile-role">{{ ucfirst(Auth::user()->role ?? 'admin') }}</div>
                    </div>
                    <svg class="dropdown-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                <div class="dropdown-menu-custom">
                    <div class="dropdown-header">
                        <div class="avatar-large">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="fw-bold">{{ Auth::user()->name }}</div>
                        <div class="user-email">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.profile') }}" class="dropdown-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Mon profil
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger" style="width: 100%; text-align: left; background: none; border: none;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    @yield('content')
</div>

<!-- Scripts globaux -->
<script>
    // Horloge temps réel
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        const formatted = now.toLocaleDateString('fr-FR', options).replace(',', ' -');
        const dateTimeEl = document.getElementById('currentDateTime');
        if (dateTimeEl) dateTimeEl.innerHTML = formatted;
    }
    updateDateTime();
    setInterval(updateDateTime, 60000);

    // Mode sombre
    function applyTheme(isDark) {
        if (isDark) {
            document.body.classList.add('dark-mode');
            const icon = document.querySelector('#themeIcon');
            if (icon) icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
        } else {
            document.body.classList.remove('dark-mode');
            const icon = document.querySelector('#themeIcon');
            if (icon) icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
        }
        localStorage.setItem('darkMode', isDark ? 'dark' : 'light');
    }

    function loadTheme() {
        const savedTheme = localStorage.getItem('darkMode');
        const isDark = savedTheme === 'dark';
        applyTheme(isDark);
    }

    function toggleGlobalDarkMode() {
        const isDark = !document.body.classList.contains('dark-mode');
        applyTheme(isDark);
        fetch('{{ route("profile.toggle-dark-mode") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ dark_mode: isDark })
        }).catch(console.error);
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadTheme();
        const themeBtn = document.getElementById('themeToggleBtn');
        if (themeBtn) themeBtn.addEventListener('click', toggleGlobalDarkMode);
    });

    // Dropdown profil
    function toggleDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        if (dropdown) dropdown.classList.toggle('active');
    }
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        if (dropdown && !dropdown.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });
</script>

@stack('scripts')
</body>
</html>