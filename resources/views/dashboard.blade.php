<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tableau de bord - e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #34d399;
            --secondary: #3b82f6;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-600: #64748b;
            --gray-800: #1e293b;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            position: relative;
            background: #f5f7fa;
            transition: background 0.3s, color 0.3s;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url('{{ asset("images/image3.jpeg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            z-index: -2;
            filter: brightness(0.85);
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.88);
            z-index: -1;
            transition: background 0.3s;
        }

        body.dark-mode::after {
            background: rgba(0, 0, 0, 0.75);
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            border-right: 1px solid rgba(16, 185, 129, 0.15);
            box-shadow: 2px 0 20px rgba(0,0,0,0.05);
            transition: background 0.3s, border-color 0.3s;
        }

        body.dark-mode .sidebar {
            background: rgba(20, 20, 30, 0.98);
            border-right-color: rgba(16, 185, 129, 0.3);
        }

        .sidebar-header {
            padding: 2rem 1.5rem 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        body.dark-mode .sidebar-header {
            border-bottom-color: #334155;
        }

        .sidebar-header h2 {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        body.dark-mode .sidebar-header h2 {
            color: #e5e7eb;
        }

         .sidebar-header .flag-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 35px;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }

        .sidebar-header .flag-icon svg {
            width: 100%;
            height: 100%;
        }

        .sidebar-header .republic {
            font-size: 0.7rem;
            color: var(--gray-600);
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        body.dark-mode .sidebar-header .republic {
            color: #94a3b8;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar-nav a {
            text-decoration: none;
            color: var(--gray-600);
            font-weight: 500;
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.2s;
            font-size: 0.9rem;
            border-radius: 0 12px 12px 0;
        }

        body.dark-mode .sidebar-nav a {
            color: #9ca3af;
        }

        .sidebar-nav a svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
        }

        .sidebar-nav a:hover {
            background: rgba(16, 185, 129, 0.08);
            color: var(--primary);
        }

        body.dark-mode .sidebar-nav a:hover {
            background: rgba(16, 185, 129, 0.2);
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.12), rgba(34, 197, 94, 0.08));
            color: var(--primary);
            font-weight: 600;
            border-right: 3px solid var(--primary);
        }

        body.dark-mode .sidebar-nav a.active {
            background: rgba(16, 185, 129, 0.25);
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
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.2s;
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

        body.dark-mode .main {
            background: transparent;
        }

        /* Alertes */
        .alert {
            padding: 1rem 1.2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            background: white;
            border-left: 4px solid var(--primary);
            transition: background 0.3s, color 0.3s;
        }

        body.dark-mode .alert {
            background: #2d2d35;
            color: #e5e7eb;
        }
        body.dark-mode .alert-success { color: #a7f3d0; }
        body.dark-mode .alert-error { color: #fecaca; background: #3f1e1e; }

        /* En-tête avec titre + bienvenue à gauche, boutons à droite */
        .dashboard-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .dashboard-left {
            flex: 1;
        }

        .dashboard-title h1 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        body.dark-mode .dashboard-title h1 {
            color: #f1f5f9;
        }

        .welcome-message {
            font-size: 1rem;
            color: var(--gray-600);
            margin-top: 0.2rem;
        }

        body.dark-mode .welcome-message {
            color: #cbd5e1;
        }

        .welcome-message strong {
            color: var(--primary);
            font-weight: 700;
        }

        .preview-text {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 0.4rem;
            font-size: 0.9rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        body.dark-mode .preview-text {
            color: #94a3b8;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .date-time {
            font-size: 0.85rem;
            color: var(--gray-600);
            background: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: background 0.3s, color 0.3s;
        }

        body.dark-mode .date-time {
            background: #2d2d35;
            color: #9ca3af;
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
            width: 34px;
            height: 34px;
        }

        body.dark-mode .icon-btn {
            background: #2d2d35;
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
            background: rgba(16, 185, 129, 0.08);
        }

        .icon-btn:hover svg {
            stroke: var(--primary);
        }

        .notification-btn {
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: var(--danger);
            color: white;
            font-size: 0.6rem;
            font-weight: 700;
            min-width: 16px;
            height: 16px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.2rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 1.2rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid rgba(16, 185, 129, 0.1);
            transition: all 0.25s;
            cursor: default;
        }

        body.dark-mode .stat-card {
            background: #2d2d35;
            border-color: rgba(16, 185, 129, 0.2);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px rgba(16, 185, 129, 0.15);
            border-color: var(--primary-light);
        }

        .stat-card .stat-value {
            font-size: 2.4rem;
            font-weight: 800;
            color: var(--dark);
            line-height: 1;
        }

        body.dark-mode .stat-card .stat-value {
            color: #f1f5f9;
        }

        .stat-card .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray-600);
            margin-top: 0.5rem;
        }

        body.dark-mode .stat-card .stat-label {
            color: #94a3b8;
        }

        .stat-card .stat-trend {
            font-size: 0.75rem;
            margin-top: 0.3rem;
            color: var(--primary);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .action-btn {
            flex: 1;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 16px;
            padding: 1.2rem;
            text-align: left;
            transition: all 0.2s;
            text-decoration: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        }

        body.dark-mode .action-btn {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(16, 185, 129, 0.2);
            border-color: var(--primary);
        }

        .action-left h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.2rem;
        }

        body.dark-mode .action-left h3 {
            color: #e5e7eb;
        }

        .action-left p {
            font-size: 0.75rem;
            color: var(--gray-600);
        }

        body.dark-mode .action-left p {
            color: #94a3b8;
        }

        .action-right {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        /* Tableau */
        .table-section {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--gray-200);
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            transition: background 0.3s, border-color 0.3s;
        }

        body.dark-mode .table-section {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .table-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            background: rgba(16, 185, 129, 0.02);
        }

        body.dark-mode .table-header {
            border-bottom-color: #4b5563;
            background: rgba(16, 185, 129, 0.05);
        }

        .table-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
        }

        body.dark-mode .table-header h3 {
            color: #e5e7eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 0.8rem 1.2rem;
            background: var(--gray-100);
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--gray-200);
        }

        body.dark-mode th {
            background: #1e1e24;
            color: #94a3b8;
            border-bottom-color: #4b5563;
        }

        td {
            padding: 0.9rem 1.2rem;
            border-bottom: 1px solid var(--gray-100);
            font-size: 0.85rem;
            color: var(--gray-800);
        }

        body.dark-mode td {
            border-bottom-color: #404040;
            color: #cbd5e1;
        }

        tr:last-child td { border-bottom: none; }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.2rem 0.7rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-pending { background: #fef3c7; color: #b45309; }
        .status-validated { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }

        body.dark-mode .status-pending { background: #422d0b; color: #fbbf24; }
        body.dark-mode .status-validated { background: #0a3b2a; color: #34d399; }
        body.dark-mode .status-rejected { background: #3f1e1e; color: #f87171; }

        .btn-view {
            background: linear-gradient(135deg, var(--secondary), #60a5fa);
            color: white;
            padding: 0.2rem 0.8rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-block;
        }

        .btn-view:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--gray-600);
        }

        body.dark-mode .empty-state {
            color: #94a3b8;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s; }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 640px) {
            .stats-grid { grid-template-columns: 1fr; }
            .action-buttons { flex-direction: column; }
            .dashboard-top { flex-direction: column; align-items: flex-start; }
            .header-right { margin-top: 0.5rem; }
        }
    </style>
</head>
<body>

@php
    use App\Models\Perte;
    use App\Models\Notification;
    
    $totalDeclarations = $totalDeclarations ?? Perte::where('user_id', auth()->id())->count();
    $enAttente = $enAttente ?? Perte::where('user_id', auth()->id())->where('statut', 'en_attente')->count();
    $validees = $validees ?? Perte::where('user_id', auth()->id())->where('statut', 'validee')->count();
    $rejetees = Perte::where('user_id', auth()->id())->where('statut', 'rejetee')->count();
    $dernieresDeclarations = $dernieresDeclarations ?? Perte::where('user_id', auth()->id())->orderBy('created_at', 'desc')->take(5)->get();
    $user = auth()->user();
    $unreadNotificationsCount = Notification::where('user_id', auth()->id())->where('is_read', false)->count();
@endphp

<!-- Sidebar -->
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
        <div class="republic">RÉPUBLIQUE TOGOLAISE</div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="active">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Tableau de bord
        </a>
        <a href="{{ route('perte.index') }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Mes Déclarations
        </a>
        <a href="{{ route('perte.create') }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
            Nouvelle Déclaration
        </a>
        <a href="{{ route('notifications.index') }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            Notifications
            @if($unreadNotificationsCount > 0)
                <span style="background: #ef4444; color: white; font-size: 0.65rem; border-radius: 20px; padding: 0 0.4rem; margin-left: auto;">{{ $unreadNotificationsCount }}</span>
            @endif
        </a>
        <a href="{{ route('profile.index') }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Paramètres
        </a>
        <a href="{{ route('help.index') }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Aide
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-link">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Déconnecter
            </button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="main">
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif

    <div class="dashboard-top">
        <div class="dashboard-left">
            <div class="dashboard-title">
                <h1>Tableau de bord</h1>
            </div>
            <div class="welcome-message">
                Bienvenue, <strong>{{ $user->name }}</strong>
            </div>
            <div class="preview-text">
                👁️ Voir l'aperçu de vos activités en temps réel 
            </div>
        </div>
        <div class="header-right">
            <div class="date-time">
                {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY - HH:mm') }}
            </div>
            <button class="icon-btn theme-toggle" onclick="toggleDarkMode()" title="Changer le thème">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>
            <button class="icon-btn notification-btn" onclick="window.location.href='{{ route('notifications.index') }}'" title="Voir les notifications">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if($unreadNotificationsCount > 0)
                    <span class="notification-badge">{{ $unreadNotificationsCount }}</span>
                @endif
            </button>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $totalDeclarations }}</div>
            <div class="stat-label">TOTAL</div>
            <div class="stat-trend">↑ Ce mois</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $enAttente }}</div>
            <div class="stat-label">EN ATTENTE</div>
            <div class="stat-trend">{{ $enAttente }} dossiers actifs</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $validees }}</div>
            <div class="stat-label">VALIDÉES</div>
            <div class="stat-trend">+1 cette semaine</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $rejetees }}</div>
            <div class="stat-label">REJETÉES</div>
            <div class="stat-trend">Aucune</div>
        </div>
    </div>

    <div class="action-buttons">
        <a href="{{ route('perte.create') }}" class="action-btn">
            <div class="action-left">
                <h3>Nouvelle déclaration</h3>
                <p>Déclarez une perte en ligne</p>
            </div>
            <div class="action-right">+ Commencer</div>
        </a>
        <a href="{{ route('documents-trouves.create') }}" class="action-btn">
            <div class="action-left">
                <h3>Document trouvé</h3>
                <p>Aidez un citoyen togolais</p>
            </div>
            <div class="action-right">Déclarer</div>
        </a>
    </div>

    <div class="table-section">
        <div class="table-header">
            <h3>Activités récentes</h3>
        </div>
        @if($dernieresDeclarations->count())
            <table>
                <thead>
                    <tr>
                        <th>Type de pièce</th>
                        <th>Date</th>
                        <th>Lieu</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dernieresDeclarations as $declaration)
                    <tr>
                        <td><strong>{{ $declaration->type_piece }}</strong></td>
                        <td>{{ $declaration->created_at->format('d/m/Y') }}</td>
                        <td>{{ $declaration->lieu_perte }}</td>
                        <td>
                            @if($declaration->statut === 'validee')
                                <span class="status-badge status-validated">✅ Validée</span>
                            @elseif($declaration->statut === 'rejetee')
                                <span class="status-badge status-rejected">❌ Rejetée</span>
                            @else
                                <span class="status-badge status-pending">⏳ En attente</span>
                            @endif
                        </td>
                        <td><a href="{{ route('perte.show', $declaration->id) }}" class="btn-view">👁️ Voir</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">📭 Aucune déclaration récente.</div>
        @endif
    </div>
</div>

<script>
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        const isDark = document.body.classList.contains('dark-mode');
        localStorage.setItem('darkMode', isDark);
        fetch('{{ route("profile.toggle-dark-mode") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ dark_mode: isDark })
        }).catch(console.error);
    }

    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }
</script>
</body>
</html>