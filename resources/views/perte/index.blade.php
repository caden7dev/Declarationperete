<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mes Déclarations de Perte - e-Déclaration TG</title>
    <script>
    (function() {
        const isDark = localStorage.getItem('darkMode') === 'true';
        if (isDark) {
            document.documentElement.style.backgroundColor = '#0f172a';
            document.body.style.backgroundColor = '#0f172a';
        }
    })();
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* ===== STYLES COMPLETS ===== */
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

        /* Top Bar Icons */
        .top-bar-icons {
            display: flex;
            align-items: center;
            gap: 1rem;
            justify-content: flex-end;
            margin-bottom: 1rem;
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

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.2rem;
        }

        .page-header p {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .btn-create {
            background: white;
            color: var(--primary);
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        body.dark-mode .btn-create {
            background: #2d2d35;
            color: white;
        }

        /* Alert */
        .alert-modern {
            padding: 1rem 1.2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
            border-left: 4px solid var(--primary);
        }

        body.dark-mode .alert-modern {
            background: #2d2d35;
            color: #e5e7eb;
        }

        .alert-success { color: #065f46; }
        body.dark-mode .alert-success { color: #a7f3d0; }
        .alert-danger { color: #991b1b; border-left-color: var(--danger); }

        /* Filter Card */
        .filter-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--gray-200);
        }

        body.dark-mode .filter-card {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .filter-btn-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            background: var(--gray-100);
            color: var(--gray-600);
        }

        body.dark-mode .filter-btn {
            background: #404048;
            color: #9ca3af;
        }

        .filter-btn.active {
            background: var(--primary);
            color: white;
        }

        .filter-btn:hover:not(.active) {
            background: var(--gray-200);
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-600);
        }

        .search-box input {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            border: 2px solid var(--gray-200);
            border-radius: 50px;
            font-size: 0.85rem;
            background: var(--gray-100);
        }

        body.dark-mode .search-box input {
            background: #404048;
            border-color: #4b5563;
            color: #e5e7eb;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
        }

        /* Stats */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1rem;
            border: 1px solid var(--gray-200);
            transition: all 0.2s;
            cursor: pointer;
        }

        body.dark-mode .stat-card {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .stat-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark);
        }

        body.dark-mode .stat-value {
            color: #f1f5f9;
        }

        .stat-label {
            font-size: 0.65rem;
            color: var(--gray-600);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        body.dark-mode .table-card {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .table-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .table-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
        }

        body.dark-mode .table-header h3 {
            color: #e5e7eb;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 0.8rem 1.2rem;
            background: var(--gray-100);
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--gray-200);
        }

        body.dark-mode th {
            background: #404048;
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

        .badge-modern {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.2rem 0.7rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-info    { background: #dbeafe; color: #1e40af; }
        .badge-primary { background: #c7d2fe; color: #3730a3; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-secondary { background: #e5e7eb; color: #374151; }
        .badge-danger { background: #fee2e2; color: #991b1b; }

        body.dark-mode .badge-warning { background: #422d0b; color: #fbbf24; }
        body.dark-mode .badge-info    { background: #1e3a5f; color: #60a5fa; }
        body.dark-mode .badge-primary { background: #2e2b5c; color: #a78bfa; }
        body.dark-mode .badge-success { background: #0a3b2a; color: #34d399; }
        body.dark-mode .badge-secondary { background: #1f2937; color: #9ca3af; }
        body.dark-mode .badge-danger { background: #3f1e1e; color: #f87171; }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-action {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-view { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .btn-edit { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        .btn-delete { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
        .btn-download { background: rgba(16, 185, 129, 0.1); color: #10b981; }
        .btn-retry { background: rgba(16, 185, 129, 0.1); color: #10b981; }
        .btn-suivi { background: rgba(16, 185, 129, 0.1); color: #10b981; }

        .btn-action:hover {
            transform: translateY(-2px);
            filter: brightness(0.9);
        }

        .pagination-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--gray-200);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .pagination-links {
            display: flex;
            gap: 0.3rem;
        }

        .pagination-links a,
        .pagination-links span {
            padding: 0.3rem 0.7rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.8rem;
            background: var(--gray-100);
            color: var(--gray-600);
        }

        body.dark-mode .pagination-links a,
        body.dark-mode .pagination-links span {
            background: #404048;
            color: #9ca3af;
        }

        .pagination-links .active span,
        .pagination-links a.active {
            background: var(--primary);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .empty-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
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
                padding: 1rem;
            }
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            .page-header {
                flex-direction: column;
                text-align: center;
            }
            .filter-btn-group {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

@php
    $user = auth()->user();

    // ============================================================
    // ⚠️ COMPTEUR CORRIGÉ : Exclusion des messages (agent_message)
    // et des notifications expirées
    // ============================================================
    $unreadNotificationsCount = \App\Models\Notification::where('user_id', $user->id)
        ->where('type', '!=', 'agent_message')
        ->notExpired()
        ->where('is_read', false)
        ->count();

    // Statistiques enrichies
    $totalDeclarations = \App\Models\Perte::where('user_id', $user->id)->count();
    $enAttenteCount = \App\Models\Perte::where('user_id', $user->id)->where('statut', 'en_attente')->count();
    $enCoursCount = \App\Models\Perte::where('user_id', $user->id)->where('statut', 'en_cours')->count();
    $correspondanceCount = \App\Models\Perte::where('user_id', $user->id)->where('statut', 'correspondance_trouvee')->count();
    $restitueCount = \App\Models\Perte::where('user_id', $user->id)->where('statut', 'restitue')->count();
    $nonRetrouveCount = \App\Models\Perte::where('user_id', $user->id)->where('statut', 'non_retrouve')->count();
    $rejeteeCount = \App\Models\Perte::where('user_id', $user->id)->where('statut', 'rejetee')->count();
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
        <a href="{{ route('dashboard') }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Tableau de bord
        </a>
        <a href="{{ route('perte.index') }}" class="active">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Mes Déclarations
        </a>
        <a href="{{ route('perte.create') }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
            Nouvelle Déclaration
        </a>
        <a href="{{ route('citoyen.messages') }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            Messages
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
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Voulez-vous vraiment vous déconnecter ?')">
            @csrf
            <button type="submit" class="logout-link">
                Déconnecter
            </button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="main">
    <div class="top-bar-icons">
        <button class="icon-btn theme-toggle" onclick="toggleGlobalDarkMode()" title="Changer le thème">
            <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1><i class="bi bi-card-checklist me-2"></i>Mes Déclarations</h1>
            <p>Gérez vos déclarations de perte de pièces d'identité</p>
        </div>
        <a href="{{ route('perte.create') }}" class="btn-create">
            <i class="bi bi-plus-circle me-2"></i>Nouvelle Déclaration
        </a>
    </div>

    <!-- Messages flash -->
    @if(session('success'))
        <div class="alert-modern alert-success">
            <span>✅ {{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert-modern alert-danger">
            <span>❌ {{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-files"></i></div>
            <div class="stat-value">{{ $totalDeclarations }}</div>
            <div class="stat-label">Total</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-clock"></i></div>
            <div class="stat-value">{{ $enAttenteCount }}</div>
            <div class="stat-label">En attente</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-arrow-repeat"></i></div>
            <div class="stat-value">{{ $enCoursCount }}</div>
            <div class="stat-label">En cours</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-value">{{ $correspondanceCount }}</div>
            <div class="stat-label">Trouvé</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
            <div class="stat-value">{{ $restitueCount }}</div>
            <div class="stat-label">Restitué</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-emoji-frown"></i></div>
            <div class="stat-value">{{ $nonRetrouveCount }}</div>
            <div class="stat-label">Non retrouvé</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
            <div class="stat-value">{{ $rejeteeCount }}</div>
            <div class="stat-label">Rejeté</div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <div class="row g-3 align-items-center">
            <div class="col-lg-8">
                <div class="filter-btn-group">
                    <a href="{{ request()->fullUrlWithQuery(['statut' => '']) }}" 
                       class="filter-btn {{ !request('statut') ? 'active' : '' }}">
                        Tous ({{ $totalDeclarations }})
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['statut' => 'en_attente']) }}" 
                       class="filter-btn {{ request('statut') == 'en_attente' ? 'active' : '' }}">
                        En attente ({{ $enAttenteCount }})
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['statut' => 'en_cours']) }}" 
                       class="filter-btn {{ request('statut') == 'en_cours' ? 'active' : '' }}">
                        En cours ({{ $enCoursCount }})
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['statut' => 'correspondance_trouvee']) }}" 
                       class="filter-btn {{ request('statut') == 'correspondance_trouvee' ? 'active' : '' }}">
                        Trouvé ({{ $correspondanceCount }})
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['statut' => 'restitue']) }}" 
                       class="filter-btn {{ request('statut') == 'restitue' ? 'active' : '' }}">
                        Restitué ({{ $restitueCount }})
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['statut' => 'non_retrouve']) }}" 
                       class="filter-btn {{ request('statut') == 'non_retrouve' ? 'active' : '' }}">
                        Non retrouvé ({{ $nonRetrouveCount }})
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['statut' => 'rejetee']) }}" 
                       class="filter-btn {{ request('statut') == 'rejetee' ? 'active' : '' }}">
                        Rejeté ({{ $rejeteeCount }})
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

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-header">
            <h3><i class="bi bi-list-ul me-2" style="color: var(--primary);"></i>Liste des déclarations</h3>
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
                            @php
                                // Configuration des statuts
                                $statusConfig = [
                                    'en_attente' => ['class' => 'badge-warning', 'icon' => 'bi-clock', 'label' => 'En attente'],
                                    'en_cours' => ['class' => 'badge-info', 'icon' => 'bi-arrow-repeat', 'label' => 'En cours'],
                                    'correspondance_trouvee' => ['class' => 'badge-primary', 'icon' => 'bi-check-circle', 'label' => 'Trouvé'],
                                    'restitue' => ['class' => 'badge-success', 'icon' => 'bi-check2-circle', 'label' => 'Restitué'],
                                    'non_retrouve' => ['class' => 'badge-secondary', 'icon' => 'bi-emoji-frown', 'label' => 'Non retrouvé'],
                                    'rejetee' => ['class' => 'badge-danger', 'icon' => 'bi-x-circle', 'label' => 'Rejetée'],
                                ];
                                $cfg = $statusConfig[$perte->statut] ?? ['class' => 'badge-secondary', 'icon' => 'bi-question-circle', 'label' => ucfirst($perte->statut)];
                            @endphp
                            <tr>
                                <td>
                                    <strong>#{{ str_pad($perte->id, 6, '0', STR_PAD_LEFT) }}</strong><br>
                                    <small class="text-muted">{{ $perte->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>{{ $perte->typePiece->nom ?? $perte->type_piece ?? 'N/A' }}</td>
                                <td><code>{{ $perte->numero_piece ?? 'N/A' }}</code></td>
                                <td>
                                    {{ $perte->date_perte->format('d/m/Y') }}<br>
                                    <small class="text-muted">{{ $perte->date_perte->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="badge-modern {{ $cfg['class'] }}">
                                        <i class="bi {{ $cfg['icon'] }}"></i>
                                        {{ $cfg['label'] }}
                                    </span>
                                    @if($perte->statut == 'rejetee' && $perte->motif_rejet)
                                        <i class="bi bi-info-circle text-danger ms-1" data-bs-toggle="tooltip" title="{{ $perte->motif_rejet }}"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <!-- Bouton Suivre -->
                                        <a href="{{ route('perte.suivi', $perte->id) }}" class="btn-action btn-suivi" data-bs-toggle="tooltip" title="Suivre"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('perte.show', $perte->id) }}" class="btn-action btn-view" data-bs-toggle="tooltip" title="Voir"><i class="bi bi-eye"></i></a>
                                        @if($perte->statut == 'en_attente')
                                            <a href="{{ route('perte.edit', $perte->id) }}" class="btn-action btn-edit" data-bs-toggle="tooltip" title="Modifier"><i class="bi bi-pencil"></i></a>
                                            <form action="{{ route('perte.destroy', $perte->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette déclaration ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" data-bs-toggle="tooltip" title="Supprimer"><i class="bi bi-trash"></i></button>
                                            </form>
                                        @endif
                                        @if($perte->statut == 'non_retrouve')
                                            <a href="{{ route('perte.create', ['copy_from' => $perte->id]) }}" class="btn-action btn-retry" data-bs-toggle="tooltip" title="Refaire une déclaration"><i class="bi bi-arrow-repeat"></i></a>
                                        @endif
                                        @if($perte->document_path)
                                            <a href="{{ asset('storage/' . $perte->document_path) }}" target="_blank" class="btn-action btn-download" data-bs-toggle="tooltip" title="Télécharger"><i class="bi bi-download"></i></a>
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
                <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                <h3 class="empty-title">Aucune déclaration trouvée</h3>
                <p class="empty-text">
                    @if(request('search') || request('statut'))
                        Aucune déclaration ne correspond à vos critères.<br>
                        <a href="{{ route('perte.index') }}" class="text-primary">Voir toutes</a>
                    @else
                        Vous n'avez pas encore déclaré de perte.
                    @endif
                </p>
                @if(!request('search') && !request('statut'))
                    <a href="{{ route('perte.create') }}" class="btn-create">+ Faire une première déclaration</a>
                @endif
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
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

    // Dark mode
    function applyTheme(isDark) {
        if (isDark) {
            document.body.classList.add('dark-mode');
            const themeIcon = document.querySelector('#themeIcon');
            if (themeIcon) themeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
        } else {
            document.body.classList.remove('dark-mode');
            const themeIcon = document.querySelector('#themeIcon');
            if (themeIcon) themeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
        }
        localStorage.setItem('darkMode', isDark ? 'dark' : 'light');
    }

    function loadTheme() {
        const serverTheme = '{{ $user->theme ?? "light" }}';
        const localTheme = localStorage.getItem('darkMode');
        const theme = serverTheme || localTheme || 'light';
        applyTheme(theme === 'dark');
    }

    function toggleGlobalDarkMode() {
        const isDark = !document.body.classList.contains('dark-mode');
        applyTheme(isDark);
        fetch('{{ route("profile.toggle-dark-mode") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' },
            body: JSON.stringify({ dark_mode: isDark })
        }).catch(console.error);
    }

    document.addEventListener('DOMContentLoaded', loadTheme);
</script>
</body>
</html>