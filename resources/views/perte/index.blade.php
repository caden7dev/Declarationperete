<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mes Déclarations de Perte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Tous les styles existants (tels que dans ton fichier) */
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 30px;
            margin: 20px auto;
            max-width: 1400px;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== SIDEBAR ===== (intégrée directement) */
        .sidebar {
            width: 280px;
            background: white;
            box-shadow: 2px 0 15px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 10;
            border-right: 1px solid rgba(16, 185, 129, 0.1);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid #e8eef5;
            background: linear-gradient(135deg, #27ae60, #219653);
        }

        .sidebar-header h2 { 
            font-size: 1.3rem;
            font-weight: 800;
            display: flex; 
            align-items: center; 
            gap: 0.8rem;
            color: white;
        }

        .sidebar-header span { 
            font-size: 1.8rem;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            overflow-y: auto;
        }

        .sidebar-nav a {
            text-decoration: none;
            color: #64748b;
            font-weight: 600;
            padding: 0.9rem 1.2rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .sidebar-nav a:hover {
            background: #f1f5f9;
            color: #27ae60;
        }

        .sidebar-nav a.active {
            background: #e8f5e9;
            color: #27ae60;
            font-weight: 700;
        }

        .sidebar-nav a svg {
            width: 20px;
            height: 20px;
        }

        .sidebar-footer {
            padding: 1.5rem 1rem;
            border-top: 1px solid #e8eef5;
        }

        .btn-logout {
            width: 100%;
            background: #fff1f0;
            color: #e74c3c;
            padding: 0.9rem;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-logout:hover {
            background: #ffe8e6;
        }

        .badge-notification {
            background: #ef4444;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            min-width: 20px;
            height: 20px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            margin-left: auto;
        }

        /* Main content */
        .main {
            margin-left: 280px;
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        /* Header Styles */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .page-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .btn-create {
            background: white;
            color: #667eea;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            color: #667eea;
        }

        /* Alert Styles */
        .alert-modern {
            border: none;
            border-radius: 15px;
            padding: 20px 25px;
            margin-bottom: 25px;
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            color: #1a4731;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: #721c24;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Filter Card */
        .filter-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(102, 126, 234, 0.1);
            transition: all 0.3s;
        }

        .filter-card:hover {
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.1);
        }

        .filter-btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .filter-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .filter-btn:not(.active) {
            background: #f8f9fa;
            color: #6c757d;
        }

        .filter-btn:hover:not(.active) {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 1.1rem;
        }

        .search-box input {
            padding-left: 45px;
            border-radius: 50px;
            border: 2px solid #e9ecef;
            height: 50px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .search-box input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(102, 126, 234, 0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }

        .stat-card.pending::before { background: linear-gradient(90deg, #f39c12, #f1c40f); }
        .stat-card.approved::before { background: linear-gradient(90deg, #27ae60, #2ecc71); }
        .stat-card.rejected::before { background: linear-gradient(90deg, #e74c3c, #c0392b); }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #7f8c8d;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .table-header {
            padding: 25px;
            border-bottom: 2px solid #f8f9fa;
        }

        .table-header h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: #f8fafc;
            color: #64748b;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 20px 25px;
            border-bottom: 2px solid #e9ecef;
        }

        .table tbody td {
            padding: 20px 25px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tbody tr {
            transition: all 0.3s;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        /* Status Badges */
        .badge-modern {
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
            color: #856404;
        }

        .badge-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .badge-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .btn-action {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            border: none;
            color: white;
            font-size: 1rem;
        }

        .btn-view {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-edit {
            background: linear-gradient(135deg, #f39c12 0%, #f1c40f 100%);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.3);
        }

        .btn-delete {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        .btn-download {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-action:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        /* Empty State */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
        }

        .empty-icon {
            font-size: 5rem;
            color: #dee2e6;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .empty-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .empty-text {
            color: #7f8c8d;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }

        /* Pagination */
        .pagination-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 25px;
            background: #f8fafc;
            border-top: 2px solid #e9ecef;
        }

        .pagination-info {
            color: #64748b;
            font-weight: 500;
        }

        .pagination-links {
            display: flex;
            gap: 8px;
        }

        .pagination-links a,
        .pagination-links span {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .pagination-links a {
            background: white;
            color: #667eea;
            border: 2px solid #e9ecef;
        }

        .pagination-links a:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .pagination-links .active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        /* Dark mode */
        body.dark-mode .sidebar {
            background: #2d2d2d;
            border-color: #404040;
        }
        body.dark-mode .sidebar-nav a {
            color: #9ca3af;
        }
        body.dark-mode .sidebar-nav a:hover {
            background: #404040;
        }
        body.dark-mode .page-header {
            background: linear-gradient(135deg, #4a4e69, #22223b);
        }
        body.dark-mode .filter-card,
        body.dark-mode .table-card,
        body.dark-mode .stat-card,
        body.dark-mode .alert-modern {
            background: #2d2d2d;
            color: #e5e7eb;
            border-color: #404040;
        }
        body.dark-mode .filter-btn:not(.active) {
            background: #404040;
            color: #9ca3af;
        }
        body.dark-mode .table thead th {
            background: #404040;
            color: #9ca3af;
        }
        body.dark-mode .table tbody tr:hover {
            background: #404040;
        }
        body.dark-mode .btn-create {
            background: #404040;
            color: white;
        }
        body.dark-mode .icon-btn {
            background: #2d2d2d;
            border-color: #404040;
        }
        body.dark-mode .icon-btn svg {
            stroke: #9ca3af;
        }
        body.dark-mode .icon-btn:hover {
            border-color: #27ae60;
        }
        body.dark-mode .icon-btn:hover svg {
            stroke: #27ae60;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }

            .main {
                margin-left: 0;
                padding: 1.5rem;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .filter-btn-group {
                justify-content: center;
            }

            .search-box {
                margin-top: 1rem;
            }
        }
    </style>
</head>
<body>

    <!-- ===== SIDEBAR (intégrée) ===== -->
    @php
        $unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();
    @endphp

    <div class="sidebar">
        <div class="sidebar-header">
            <h2>
                <span>🇹🇬</span> 
                e-Déclaration TG
            </h2>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Tableau de bord
            </a>
            
            <a href="{{ route('perte.index') }}" class="{{ request()->routeIs('perte.*') && !request()->routeIs('perte.create') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Mes Déclarations
            </a>

            <a href="{{ route('perte.create') }}" class="{{ request()->routeIs('perte.create') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle Déclaration
            </a>

            <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Notifications
                @if($unreadNotifications > 0)
                    <span class="badge-notification">{{ $unreadNotifications }}</span>
                @endif
            </a>

            <a href="{{ route('profile.index') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Paramètres
            </a>

            <a href="{{ route('help.index') }}" class="{{ request()->routeIs('help.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Aide
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main">
        <!-- Top Bar Icons (Thème + Notifications) -->
        <div class="top-bar-icons" style="display: flex; align-items: center; gap: 1.2rem; margin-bottom: 2rem; justify-content: flex-end;">
            <!-- Bouton Mode Sombre -->
            <button class="icon-btn theme-toggle" onclick="toggleGlobalDarkMode()" title="Changer le thème" style="background: white; border: none; width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.03);">
                <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:22px;height:22px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>

            <!-- Bouton Notifications -->
            <button class="icon-btn" onclick="openNotifications()" title="Voir les notifications" style="background: white; border: none; width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.03); position: relative;">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:22px;height:22px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @php
                    $notificationsCount = \App\Models\Perte::where('user_id', auth()->id())
                        ->where('statut', 'en_attente')
                        ->count();
                @endphp
                @if($notificationsCount > 0)
                    <span class="notification-badge" style="position: absolute; top: -5px; right: -5px; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; font-size: 0.7rem; font-weight: 700; min-width: 20px; height: 20px; border-radius: 20px; display: flex; align-items: center; justify-content: center; padding: 0 5px;">{{ $notificationsCount }}</span>
                @endif
            </button>
        </div>

        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i class="bi bi-card-checklist me-2"></i>
                    Mes Déclarations
                </h1>
                <p>Gérez vos déclarations de perte de pièces d'identité</p>
            </div>
            <a href="{{ route('perte.create') }}" class="btn-create">
                <i class="bi bi-plus-circle me-2"></i>
                Nouvelle Déclaration
            </a>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="alert-modern alert-success">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                    <div>
                        <strong>Succès !</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-modern alert-danger">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                    <div>
                        <strong>Erreur !</strong> {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));">
                    <i class="bi bi-files" style="color: #667eea;"></i>
                </div>
                <div class="stat-value">{{ $totalDeclarations }}</div>
                <div class="stat-label">Total déclarations</div>
            </div>

            <div class="stat-card pending">
                <div class="stat-icon" style="background: rgba(243, 156, 18, 0.1);">
                    <i class="bi bi-clock" style="color: #f39c12;"></i>
                </div>
                <div class="stat-value">{{ $enAttenteCount }}</div>
                <div class="stat-label">En attente</div>
            </div>

            <div class="stat-card approved">
                <div class="stat-icon" style="background: rgba(39, 174, 96, 0.1);">
                    <i class="bi bi-check-circle" style="color: #27ae60;"></i>
                </div>
                <div class="stat-value">{{ $valideeCount }}</div>
                <div class="stat-label">Validées</div>
            </div>

            <div class="stat-card rejected">
                <div class="stat-icon" style="background: rgba(231, 76, 60, 0.1);">
                    <i class="bi bi-x-circle" style="color: #e74c3c;"></i>
                </div>
                <div class="stat-value">{{ $rejeteeCount }}</div>
                <div class="stat-label">Rejetées</div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="filter-card">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="filter-btn-group">
                        <a href="{{ request()->fullUrlWithQuery(['statut' => '']) }}" 
                           class="filter-btn {{ !request('statut') ? 'active' : '' }}">
                            <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                            Tous ({{ $totalDeclarations }})
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['statut' => 'en_attente']) }}" 
                           class="filter-btn {{ request('statut') == 'en_attente' ? 'active' : '' }}">
                            <i class="bi bi-clock me-2"></i>
                            En attente ({{ $enAttenteCount }})
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['statut' => 'validee']) }}" 
                           class="filter-btn {{ request('statut') == 'validee' ? 'active' : '' }}">
                            <i class="bi bi-check-circle me-2"></i>
                            Validées ({{ $valideeCount }})
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['statut' => 'rejetee']) }}" 
                           class="filter-btn {{ request('statut') == 'rejetee' ? 'active' : '' }}">
                            <i class="bi bi-x-circle me-2"></i>
                            Rejetées ({{ $rejeteeCount }})
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <form method="GET" class="search-box">
                        @if(request('statut'))
                            <input type="hidden" name="statut" value="{{ request('statut') }}">
                        @endif
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Rechercher une déclaration..." 
                               value="{{ request('search') }}">
                    </form>
                </div>
            </div>
        </div>

        <!-- Tableau -->
        <div class="table-card">
            <div class="table-header">
                <h3>
                    <i class="bi bi-list-ul me-2" style="color: #667eea;"></i>
                    Liste des déclarations
                </h3>
            </div>

            @if($pertes->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>N° Déclaration</th>
                                <th>Type de pièce</th>
                                <th>N° Pièce</th>
                                <th>Date perte</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pertes as $perte)
                                <tr>
                                    <td>
                                        <strong>#{{ str_pad($perte->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $perte->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <span style="font-weight: 600;">{{ $perte->typePiece->nom ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <code>{{ $perte->numero_piece ?? 'N/A' }}</code>
                                    </td>
                                    <td>
                                        {{ $perte->date_perte->format('d/m/Y') }}
                                        <br>
                                        <small class="text-muted">{{ $perte->date_perte->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = [
                                                'en_attente' => 'badge-warning',
                                                'validee' => 'badge-success',
                                                'rejetee' => 'badge-danger'
                                            ][$perte->statut] ?? 'badge-secondary';
                                            
                                            $statutLabels = [
                                                'en_attente' => 'En attente',
                                                'validee' => 'Validée',
                                                'rejetee' => 'Rejetée'
                                            ];
                                        @endphp
                                        <span class="badge-modern {{ $badgeClass }}">
                                            <i class="bi 
                                                {{ $perte->statut == 'validee' ? 'bi-check-circle' : '' }}
                                                {{ $perte->statut == 'rejetee' ? 'bi-x-circle' : '' }}
                                                {{ $perte->statut == 'en_attente' ? 'bi-clock' : '' }}
                                            "></i>
                                            {{ $statutLabels[$perte->statut] }}
                                        </span>
                                        @if($perte->statut == 'rejetee' && $perte->motif_rejet)
                                            <i class="bi bi-info-circle text-danger ms-1" 
                                               data-bs-toggle="tooltip" 
                                               title="{{ $perte->motif_rejet }}"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('perte.show', $perte->id) }}" 
                                               class="btn-action btn-view" 
                                               data-bs-toggle="tooltip" 
                                               title="Voir les détails">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            @if($perte->statut == 'en_attente')
                                                <a href="{{ route('perte.edit', $perte->id) }}" 
                                                   class="btn-action btn-edit" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                
                                                <form action="{{ route('perte.destroy', $perte->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette déclaration ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn-action btn-delete" 
                                                            data-bs-toggle="tooltip" 
                                                            title="Supprimer">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($perte->document_path)
                                                <a href="{{ asset('storage/' . $perte->document_path) }}" 
                                                   target="_blank" 
                                                   class="btn-action btn-download" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Télécharger">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($pertes->hasPages())
                    <div class="pagination-modern">
                        <div class="pagination-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Affichage de {{ $pertes->firstItem() }} à {{ $pertes->lastItem() }} sur {{ $pertes->total() }} déclarations
                        </div>
                        <div class="pagination-links">
                            {{ $pertes->withQueryString()->links() }}
                        </div>
                    </div>
                @endif

            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h3 class="empty-title">Aucune déclaration trouvée</h3>
                    <p class="empty-text">
                        @if(request('search') || request('statut'))
                            Aucune déclaration ne correspond à vos critères de recherche.
                            <br>
                            <a href="{{ route('perte.index') }}" class="text-primary">Voir toutes les déclarations</a>
                        @else
                            Vous n'avez pas encore déclaré de perte de pièce d'identité.
                        @endif
                    </p>
                    @if(!request('search') && !request('statut'))
                        <a href="{{ route('perte.create') }}" class="btn-create" style="display: inline-block;">
                            <i class="bi bi-plus-circle me-2"></i>
                            Faire une première déclaration
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialisation des tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Auto-dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert-modern').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // ============================================
        // MODE SOMBRE GLOBAL - avec persistance serveur
        // ============================================
        function applyTheme(isDark) {
            if (isDark) {
                document.body.classList.add('dark-mode');
                const themeIcon = document.querySelector('#themeIcon');
                if (themeIcon) {
                    themeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
                }
            } else {
                document.body.classList.remove('dark-mode');
                const themeIcon = document.querySelector('#themeIcon');
                if (themeIcon) {
                    themeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
                }
            }
            localStorage.setItem('darkMode', isDark ? 'dark' : 'light');
        }

        function loadTheme() {
            // Récupérer le thème depuis le serveur (via l'utilisateur connecté)
            const serverTheme = '{{ auth()->user()->theme ?? 'light' }}';
            const localTheme = localStorage.getItem('darkMode');
            const theme = serverTheme || localTheme || 'light';
            applyTheme(theme === 'dark');
        }

        function toggleGlobalDarkMode() {
            const isDark = !document.body.classList.contains('dark-mode');
            applyTheme(isDark);
            fetch('{{ route("profile.toggle-dark-mode") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ dark_mode: isDark })
            }).catch(error => console.log('Erreur synchronisation thème:', error));
        }

        function openNotifications() {
            window.location.href = '{{ route("notifications.index") }}';
        }

        document.addEventListener('DOMContentLoaded', loadTheme);
    </script>
</body>
</html>