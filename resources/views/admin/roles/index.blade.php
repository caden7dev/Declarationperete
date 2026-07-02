<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestion des Rôles et Droits - Administration</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Bootstrap CSS pour les modales -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- ⚡ ANTI-FLASH BLANC -->
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
                    document.documentElement.style.backgroundColor = '#f5f7fa';
                    document.body.style.backgroundColor = '#f5f7fa';
                }
            } catch(e) {}
        })();
    </script>
    
    <style>
        /* ===== COULEURS TOGO AVEC DOMINANTE VERTE POUR L'ADMIN ===== */
        :root {
            --primary: #1a7a3a;
            --primary-dark: #0f5c2a;
            --primary-light: #4caf50;
            --accent: #f39c12;
            --accent-dark: #e67e22;
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
            --red-togo: #d21034;
        }

        /* ===== STYLES GLOBAUX ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f5f7fa;
            transition: background 0.2s ease;
        }

        body.dark-mode {
            background: #0f172a;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 280px;
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            border-right: 1px solid rgba(26,122,58,0.15);
            box-shadow: 2px 0 20px rgba(0,0,0,0.05);
            transition: background 0.2s, border-color 0.2s;
            border-top: 4px solid var(--red-togo);
        }

        body.dark-mode .sidebar {
            background: rgba(20,20,30,0.98);
            border-right-color: rgba(26,122,58,0.3);
        }

        .sidebar-header {
            padding: 2rem 1.5rem 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        body.dark-mode .sidebar-header {
            border-bottom-color: #334155;
        }

        .sidebar-header h2 {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        body.dark-mode .sidebar-header h2 {
            color: #e5e7eb;
        }

        .flag-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 28px;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }

        .flag-icon svg {
            width: 100%;
            height: 100%;
        }

        .republic-text {
            font-size: 0.65rem;
            color: var(--gray-600);
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-top: 0.3rem;
            margin-left: 0.5rem;
        }

        body.dark-mode .republic-text {
            color: #94a3b8;
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            margin-top: 0.5rem;
            text-transform: uppercase;
            border: 1px solid var(--accent);
        }

        .nav-section {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray-600);
            padding: 1rem 1.5rem 0.5rem 1.5rem;
        }

        body.dark-mode .nav-section {
            color: #64748b;
        }

        .sidebar-nav {
            flex: 1;
            padding: 0.5rem 0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .sidebar-nav a {
            text-decoration: none;
            color: var(--gray-600);
            font-weight: 500;
            padding: 0.7rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.2s;
            font-size: 0.9rem;
            border-radius: 0 12px 12px 0;
            position: relative;
        }

        body.dark-mode .sidebar-nav a {
            color: #9ca3af;
        }

        .sidebar-nav a i {
            width: 20px;
            font-size: 1.1rem;
        }

        .sidebar-nav a:hover {
            background: rgba(26,122,58,0.08);
            color: var(--primary);
        }

        body.dark-mode .sidebar-nav a:hover {
            background: rgba(26,122,58,0.2);
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, rgba(26,122,58,0.12), rgba(76,175,80,0.08));
            color: var(--primary);
            font-weight: 600;
            border-right: 3px solid var(--primary);
        }

        .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            min-width: 22px;
            height: 22px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
        }

        .nav-badge.orange {
            background: var(--accent);
        }
        .nav-badge.green {
            background: var(--success);
        }
        .nav-badge.blue {
            background: var(--info);
        }

        .sidebar-footer {
            padding: 0.8rem 1rem;
            border-top: 1px solid var(--gray-200);
        }

        body.dark-mode .sidebar-footer {
            border-top-color: #334155;
        }

        .logout-link {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            color: var(--danger);
            font-weight: 950;
            background: none;
            border: none;
            width: 100%;
            cursor: pointer;
            padding: 0.4rem 0;
        }

        .logout-link svg {
            width: 16px;
            height: 16px;
        }

        .logout-link:hover {
            opacity: 0.8;
            transform: translateX(3px);
        }

        /* ===== MAIN CONTENT ===== */
        .main {
            margin-left: 280px;
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .top-bar {
            background: white;
            border-radius: 20px;
            padding: 1.2rem 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            transition: background 0.2s, border-color 0.2s;
            border-left: 6px solid var(--red-togo);
        }

        body.dark-mode .top-bar {
            background: #1e293b;
            border-color: #334155;
        }

        .top-bar-left h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.2rem;
        }

        body.dark-mode .top-bar-left h1 {
            color: #f1f5f9;
        }

        .top-bar-left p {
            color: var(--gray-600);
            font-size: 0.85rem;
        }

        body.dark-mode .top-bar-left p {
            color: #94a3b8;
        }

        .top-bar-right {
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
            background: rgba(26,122,58,0.08);
        }

        .icon-btn:hover svg {
            stroke: var(--primary);
        }

        /* ===== PROFIL DROPDOWN ===== */
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

        body.dark-mode .profile-trigger:hover {
            background: #475569;
        }

        .profile-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
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
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
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
            background: none;
            border: none;
            width: 100%;
            cursor: pointer;
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

        body.dark-mode .dropdown-item.text-danger:hover {
            background: #3f1e1e;
        }

        /* ===== TABLEAU ===== */
        .table-card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 16px;
            overflow: hidden;
            transition: background 0.2s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        body.dark-mode .table-card {
            background: #1e293b;
            border-color: #334155;
        }

        .table {
            margin-bottom: 0;
        }

        body.dark-mode .table {
            color: #e5e7eb;
        }

        .table thead th {
            border-top: none;
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--primary-light);
        }

        body.dark-mode .table thead th {
            background: #1e293b;
            color: #94a3b8;
            border-bottom-color: #4caf50;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        body.dark-mode .table tbody tr:hover {
            background: #334155;
        }

        /* ===== BADGES RÔLES ===== */
        .badge-admin {
            background: var(--primary);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        .badge-agent {
            background: var(--accent);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        .badge-user {
            background: var(--info);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        /* ===== BOUTONS ===== */
        .btn-sm {
            padding: 0.3rem 0.8rem;
            font-size: 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26,122,58,0.3);
        }
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .form-select-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            border-radius: 8px;
            border: 2px solid var(--gray-200);
            background-color: white;
            color: var(--dark);
            transition: all 0.2s;
        }

        body.dark-mode .form-select-sm {
            background-color: #334155;
            border-color: #4b5563;
            color: #e5e7eb;
        }

        .form-select-sm:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,122,58,0.1);
        }

        /* ===== ALERTES ===== */
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--success);
        }

        body.dark-mode .alert-success {
            background: #0a3b2a;
            color: #34d399;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--danger);
        }

        body.dark-mode .alert-danger {
            background: #3f1e1e;
            color: #f87171;
        }

        /* ===== MODALES ===== */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        body.dark-mode .modal-content {
            background: #1e293b;
            color: #e5e7eb;
        }
        .modal-header {
            border-radius: 16px 16px 0 0;
        }
        body.dark-mode .modal-header {
            border-bottom-color: #334155;
        }
        .modal-footer {
            border-radius: 0 0 16px 16px;
        }
        body.dark-mode .modal-footer {
            border-top-color: #334155;
        }
        .btn-close-white {
            filter: invert(1);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width:1024px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }
            .main {
                margin-left: 0;
            }
        }

        @media (max-width:640px) {
            .top-bar {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }
            .top-bar-right {
                justify-content: center;
            }
            .table-responsive {
                font-size: 0.8rem;
            }
            .form-select-sm {
                width: 100% !important;
            }
            .d-flex.gap-2 {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>

@php
    $user = auth()->user();
    $statsPertes = \App\Models\Perte::count();
    $statsDocuments = \App\Models\DocumentTrouve::count();
    $totalUsers = \App\Models\User::count();
    $totalTypes = \App\Models\TypePiece::count();
@endphp

<!-- ===== SIDEBAR ===== -->
<div class="sidebar">
    <div class="sidebar-header">
        <h2>
            <div class="flag-icon">
                <svg viewBox="0 0 5 4" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                    <rect width="5" height=".8" y="0"   fill="#006A36"/>
                    <rect width="5" height=".8" y=".8"  fill="#FFCB00"/>
                    <rect width="5" height=".8" y="1.6" fill="#006A36"/>
                    <rect width="5" height=".8" y="2.4" fill="#FFCB00"/>
                    <rect width="5" height=".8" y="3.2" fill="#006A36"/>
                    <rect width="1.9" height="2.4" fill="#D21034"/>
                    <polygon points="0.95,0.38 1.07,0.76 1.47,0.76 1.16,0.99 1.28,1.37 0.95,1.14 0.62,1.37 0.74,0.99 0.43,0.76 0.83,0.76" fill="#FFFFFF"/>
                </svg>
            </div>
            e-Déclaration TG
        </h2>
        <div class="republic-text">RÉPUBLIQUE TOGOLAISE</div>
        <div class="admin-badge">
            <i class="bi bi-shield-lock"></i> ADMIN
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">PRINCIPAL</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Utilisateurs
            <span class="nav-badge">{{ $totalUsers }}</span>
        </a>
        <a href="{{ route('admin.types-pieces.index') }}" class="{{ request()->routeIs('admin.types-pieces.*') ? 'active' : '' }}">
            <i class="bi bi-upc-scan"></i> Types de pièces
            <span class="nav-badge">{{ $totalTypes }}</span>
        </a>
        <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <i class="bi bi-shield-check"></i> Rôles & droits
        </a>

        <div class="nav-section">DÉCLARATIONS</div>
        <a href="{{ route('admin.dashboard') }}">
            <i class="bi bi-files"></i> Toutes les pertes
            <span class="nav-badge">{{ $statsPertes }}</span>
        </a>
        <a href="#">
            <i class="bi bi-search-heart"></i> Documents trouvés
            <span class="nav-badge orange">{{ $statsDocuments }}</span>
        </a>

        <div class="nav-section">ANALYTIQUES</div>
        <a href="#">
            <i class="bi bi-graph-up"></i> Statistiques
        </a>
        <a href="#">
            <i class="bi bi-file-text"></i> Rapports
        </a>

        <div class="nav-section">PARAMÈTRES</div>
        <a href="{{ route('admin.profile') }}">
            <i class="bi bi-person-gear"></i> Mon profil
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Voulez-vous vraiment vous déconnecter ?')">
            @csrf
            <button type="submit" class="logout-link">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Déconnecter
            </button>
        </form>
    </div>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="main">
    <!-- TOP BAR -->
    <div class="top-bar">
        <div class="top-bar-left">
            <h1><i class="bi bi-shield-check me-2" style="color: var(--primary);"></i>Gestion des Rôles et Droits</h1>
            <p>Attribuez ou modifiez les rôles des utilisateurs</p>
        </div>
        <div class="top-bar-right">
            <div class="date-time" id="currentDateTime">
                {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY - HH:mm') }}
            </div>
            <button class="icon-btn theme-toggle" id="themeToggleBtn" title="Changer le thème">
                <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>

            <!-- PROFIL DROPDOWN -->
            <div class="profile-dropdown" id="profileDropdown">
                <div class="profile-trigger" onclick="toggleDropdown()">
                    <div class="profile-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="profile-info">
                        <div class="profile-name">{{ $user->name }}</div>
                        <div class="profile-role">Administrateur</div>
                    </div>
                    <svg class="dropdown-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>

                <div class="dropdown-menu-custom">
                    <div class="dropdown-header">
                        <div class="avatar-large">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="fw-bold">{{ $user->name }}</div>
                        <div class="user-email">{{ $user->email }}</div>
                    </div>

                    <div class="dropdown-divider"></div>

                    <a href="{{ route('admin.profile') }}" class="dropdown-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Mon profil
                    </a>

                    <div class="dropdown-divider"></div>

                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Voulez-vous vraiment vous déconnecter ?')" style="margin:0;">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger" style="width:100%; text-align:left; background:none; border:none;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ALERTES -->
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-danger">{{ session('error') }}</div>
    @endif

    <!-- TABLEAU -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">#ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle actuel</th>
                        <th>Modifier le rôle</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                    <tr>
                        <td class="ps-4 fw-bold">#{{ $u->id }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @if($u->role === 'admin')
                                <span class="badge-admin"><i class="bi bi-shield-lock"></i> Administrateur</span>
                            @elseif($u->role === 'agent')
                                <span class="badge-agent"><i class="bi bi-shield-check"></i> Agent</span>
                            @else
                                <span class="badge-user"><i class="bi bi-person"></i> Citoyen</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.roles.update', $u->id) }}" class="d-flex gap-2 align-items-center">
                                @csrf @method('PUT')
                                <select name="role" class="form-select form-select-sm" style="width:auto;">
                                    <option value="user" {{ $u->role=='user'?'selected':'' }}>Citoyen</option>
                                    <option value="agent" {{ $u->role=='agent'?'selected':'' }}>Agent</option>
                                    <option value="admin" {{ $u->role=='admin'?'selected':'' }}>Admin</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-check-lg"></i> Appliquer</button>
                            </form>
                        </td>
                        <td class="text-end pe-4">
                            @if($u->id !== auth()->id())
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delUser{{ $u->id }}">
                                    <i class="bi bi-trash3"></i> Supprimer
                                </button>
                            @else
                                <span class="text-muted"><i class="bi bi-person-check"></i> Vous-même</span>
                            @endif
                        </td>
                    </tr>

                    <!-- MODALE SUPPRESSION -->
                    <div class="modal fade" id="delUser{{ $u->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}">
                                    @csrf @method('DELETE')
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirmer la suppression</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Êtes-vous sûr de vouloir supprimer <strong>{{ $u->name }}</strong> ?</p>
                                        <p class="text-danger"><small><i class="bi bi-exclamation-circle"></i> Cette action est irréversible !</small></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-danger"><i class="bi bi-trash3"></i> Supprimer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===== SCRIPTS ===== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Horloge
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        const formatted = now.toLocaleDateString('fr-FR', options).replace(',', ' -');
        const el = document.getElementById('currentDateTime');
        if (el) el.innerHTML = formatted;
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
        const saved = localStorage.getItem('darkMode');
        applyTheme(saved === 'dark');
    }

    function toggleTheme() {
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
        const btn = document.getElementById('themeToggleBtn');
        if (btn) btn.addEventListener('click', toggleTheme);
    });

    // Dropdown profil
    function toggleDropdown() {
        document.getElementById('profileDropdown').classList.toggle('active');
    }
    document.addEventListener('click', function(e) {
        const d = document.getElementById('profileDropdown');
        if (d && !d.contains(e.target)) {
            d.classList.remove('active');
        }
    });
</script>
</body>
</html>