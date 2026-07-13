<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modifier la Déclaration - e-Déclaration TG</title>
    <script>
    // Anti-flash blanc - À mettre tout en haut du head
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

        /* Main Container */
        .main-container {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            border: 1px solid var(--gray-200);
            transition: background 0.3s, border-color 0.3s;
        }

        body.dark-mode .main-container {
            background: #2d2d35;
            border-color: #4b5563;
        }

        /* Header */
        .edit-header {
            background: linear-gradient(135deg, var(--warning), #d97706);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
        }

        .breadcrumb-custom {
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .breadcrumb-custom a {
            color: white;
            text-decoration: none;
            opacity: 0.8;
        }

        .breadcrumb-custom a:hover {
            opacity: 1;
        }

        .breadcrumb-custom .separator {
            margin: 0 0.5rem;
            opacity: 0.5;
        }

        .breadcrumb-custom .current {
            font-weight: 600;
        }

        .header-title {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .header-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .btn-cancel {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: white;
            color: var(--warning);
        }

        /* Alert */
        .alert-warning-custom {
            background: #fffbeb;
            border-left: 4px solid var(--warning);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #b45309;
        }

        body.dark-mode .alert-warning-custom {
            background: #422d0b;
            color: #fbbf24;
        }

        /* Form Sections */
        .form-section {
            background: var(--gray-100);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--gray-200);
            transition: all 0.2s;
        }

        body.dark-mode .form-section {
            background: #404048;
            border-color: #4b5563;
        }

        .form-section:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        body.dark-mode .section-title {
            color: #e5e7eb;
        }

        .section-title i {
            color: var(--primary);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--gray-800);
            margin-bottom: 0.4rem;
            display: block;
        }

        body.dark-mode .form-label {
            color: #e5e7eb;
        }

        .required::after {
            content: " *";
            color: var(--danger);
        }

        .form-control, .form-select {
            width: 100%;
            padding: 0.7rem;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 0.85rem;
            background: white;
            transition: all 0.2s;
        }

        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background: #4b5563;
            border-color: #6b7280;
            color: #e5e7eb;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .current-file {
            background: white;
            border-radius: 12px;
            padding: 0.8rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid var(--gray-200);
        }

        body.dark-mode .current-file {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-view-file {
            background: var(--primary);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.75rem;
        }

        .file-upload-area {
            border: 2px dashed var(--gray-200);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        body.dark-mode .file-upload-area {
            border-color: #4b5563;
        }

        .file-upload-area:hover {
            border-color: var(--primary);
            background: rgba(16, 185, 129, 0.05);
        }

        .file-upload-icon {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .file-preview {
            background: #d1fae5;
            border-radius: 12px;
            padding: 0.8rem;
            margin-top: 0.5rem;
            color: #065f46;
        }

        body.dark-mode .file-preview {
            background: #0a3b2a;
            color: #34d399;
        }

        .info-alert {
            background: #e0f2fe;
            border-left: 4px solid #0ea5e9;
            border-radius: 12px;
            padding: 0.8rem;
            margin-top: 1rem;
            color: #0369a1;
        }

        body.dark-mode .info-alert {
            background: #0c334a;
            color: #7dd3fc;
        }

        .submit-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-outline-secondary {
            background: transparent;
            border: 1px solid var(--gray-200);
            padding: 0.6rem 1.2rem;
            border-radius: 12px;
            color: var(--gray-600);
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-outline-secondary:hover {
            background: var(--gray-100);
        }

        body.dark-mode .btn-outline-secondary {
            border-color: #4b5563;
            color: #9ca3af;
        }

        body.dark-mode .btn-outline-secondary:hover {
            background: #404048;
        }

        .required-note {
            font-size: 0.75rem;
            color: var(--gray-600);
        }

        /* ===== NOUVEAU : STYLE D'ERREUR DE DATE ===== */
        .date-error {
            color: var(--danger);
            font-size: 0.75rem;
            margin-top: 0.2rem;
            display: none;
        }

        .date-error.show {
            display: block;
        }

        .form-control.error {
            border-color: var(--danger);
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
            .edit-header .d-flex {
                flex-direction: column;
                gap: 1rem;
            }
            .submit-section {
                flex-direction: column;
                align-items: stretch;
            }
            .submit-section div:last-child {
                display: flex;
                gap: 1rem;
                justify-content: center;
            }
        }

        @media (max-width: 640px) {
            .row {
                flex-direction: column;
            }
            .col-md-6, .col-md-4 {
                width: 100%;
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

    <div class="main-container">
        <!-- Header -->
        <div class="edit-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="breadcrumb-custom">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <span class="separator">›</span>
                        <a href="{{ route('perte.index') }}">Mes Déclarations</a>
                        <span class="separator">›</span>
                        <a href="{{ route('perte.show', $perte->id) }}">Détails #{{ str_pad($perte->id, 6, '0', STR_PAD_LEFT) }}</a>
                        <span class="separator">›</span>
                        <span class="current">Modifier</span>
                    </div>
                    <h1 class="header-title"><i class="bi bi-pencil-square me-2"></i>Modifier la Déclaration</h1>
                    <p class="header-subtitle">Déclaration #{{ str_pad($perte->id, 6, '0', STR_PAD_LEFT) }} - Soumise le {{ $perte->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <a href="{{ route('perte.show', $perte->id) }}" class="btn-cancel"><i class="bi bi-x-lg me-1"></i>Annuler</a>
            </div>
        </div>

        <!-- Alert -->
        <div class="alert-warning-custom">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Attention !</strong> Vous modifiez une déclaration en attente. Toute modification sera soumise à une nouvelle validation par un agent.
        </div>

        <!-- Formulaire -->
        <form action="{{ route('perte.update', $perte->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
            @csrf
            @method('PUT')

            <!-- Section 1 : Informations personnelles -->
            <div class="form-section">
                <div class="section-title"><i class="bi bi-person-badge"></i>Informations personnelles</div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="last_name" class="form-label required">Nom</label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $perte->last_name) }}" required>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="first_name" class="form-label required">Prénom(s)</label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $perte->first_name) }}" required>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="contact" class="form-label">Contact téléphonique</label>
                        <input type="tel" class="form-control @error('contact') is-invalid @enderror" id="contact" name="contact" value="{{ old('contact', $perte->contact) }}" placeholder="90 00 00 00">
                        @error('contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="email" class="form-label required">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $perte->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Section 2 : Informations sur la pièce -->
            <div class="form-section">
                <div class="section-title"><i class="bi bi-card-text"></i>Informations sur la pièce d'identité</div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="type_piece" class="form-label required">Type de pièce</label>
                        <select class="form-select @error('type_piece') is-invalid @enderror" id="type_piece" name="type_piece" required>
                            <option value="">Sélectionnez le type de pièce</option>
                            @foreach($typesPieces ?? [] as $type)
                                <option value="{{ $type->id }}" {{ old('type_piece', $perte->type_piece) == $type->id ? 'selected' : '' }}>{{ $type->nom }}</option>
                            @endforeach
                        </select>
                        @error('type_piece')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="numero_piece" class="form-label">Numéro de la pièce</label>
                        <input type="text" class="form-control @error('numero_piece') is-invalid @enderror" id="numero_piece" name="numero_piece" value="{{ old('numero_piece', $perte->numero_piece) }}">
                        @error('numero_piece')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="date_delivrance" class="form-label">Date de délivrance</label>
                        <input type="date" class="form-control @error('date_delivrance') is-invalid @enderror" id="date_delivrance" name="date_delivrance" value="{{ old('date_delivrance', $perte->date_delivrance ? $perte->date_delivrance->format('Y-m-d') : '') }}" max="{{ date('Y-m-d') }}">
                        @error('date_delivrance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="autorite_delivrance" class="form-label">Autorité de délivrance</label>
                        <input type="text" class="form-control @error('autorite_delivrance') is-invalid @enderror" id="autorite_delivrance" name="autorite_delivrance" value="{{ old('autorite_delivrance', $perte->autorite_delivrance) }}">
                        @error('autorite_delivrance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Section 3 : Circonstances de la perte -->
            <div class="form-section">
                <div class="section-title"><i class="bi bi-exclamation-triangle"></i>Circonstances de la perte</div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="date_perte" class="form-label required">Date de la perte</label>
                        <input type="date" class="form-control @error('date_perte') is-invalid @enderror" id="date_perte" name="date_perte" value="{{ old('date_perte', $perte->date_perte->format('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                        <div class="date-error" id="dateError">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            La date de perte doit être postérieure ou égale à la date de délivrance.
                        </div>
                        @error('date_perte')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="lieu_perte" class="form-label required">Lieu de la perte</label>
                        <input type="text" class="form-control @error('lieu_perte') is-invalid @enderror" id="lieu_perte" name="lieu_perte" value="{{ old('lieu_perte', $perte->lieu_perte) }}" required>
                        @error('lieu_perte')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 form-group">
                        <label for="circonstances" class="form-label">Circonstances détaillées</label>
                        <textarea class="form-control @error('circonstances') is-invalid @enderror" id="circonstances" name="circonstances" rows="4">{{ old('circonstances', $perte->circonstances) }}</textarea>
                        @error('circonstances')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Section 4 : Documents justificatifs -->
            <div class="form-section">
                <div class="section-title"><i class="bi bi-paperclip"></i>Documents justificatifs</div>
                <div class="row">
                    <!-- Copie de la pièce -->
                    <div class="col-md-4 form-group">
                        <label class="form-label">Copie de la pièce d'identité</label>
                        @if($perte->document_path)
                            <div class="current-file">
                                <div class="file-info"><i class="bi bi-file-pdf-fill fs-4 text-danger"></i><span>Document actuel</span></div>
                                <a href="{{ asset('storage/' . $perte->document_path) }}" target="_blank" class="btn-view-file"><i class="bi bi-eye"></i> Voir</a>
                            </div>
                        @endif
                        <div class="file-upload-area" onclick="document.getElementById('document_path').click()">
                            <input type="file" id="document_path" name="document_path" style="display:none" accept=".pdf,.jpg,.jpeg,.png" onchange="previewFile(this,'preview1')">
                            <div class="file-upload-icon"><i class="bi bi-cloud-upload"></i></div>
                            <div class="file-upload-text">Cliquez pour télécharger</div>
                        </div>
                        <div id="preview1" class="file-preview" style="display:none"></div>
                        @error('document_path')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <!-- Déclaration de vol -->
                    <div class="col-md-4 form-group">
                        <label class="form-label">Déclaration de vol/perte</label>
                        @if($perte->declaration_vol)
                            <div class="current-file">
                                <div class="file-info"><i class="bi bi-file-text-fill fs-4 text-info"></i><span>Document actuel</span></div>
                                <a href="{{ asset('storage/' . $perte->declaration_vol) }}" target="_blank" class="btn-view-file"><i class="bi bi-eye"></i> Voir</a>
                            </div>
                        @endif
                        <div class="file-upload-area" onclick="document.getElementById('declaration_vol').click()">
                            <input type="file" id="declaration_vol" name="declaration_vol" style="display:none" accept=".pdf,.jpg,.jpeg,.png" onchange="previewFile(this,'preview2')">
                            <div class="file-upload-icon"><i class="bi bi-cloud-upload"></i></div>
                            <div class="file-upload-text">Cliquez pour télécharger</div>
                        </div>
                        <div id="preview2" class="file-preview" style="display:none"></div>
                        @error('declaration_vol')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <!-- Document complémentaire -->
                    <div class="col-md-4 form-group">
                        <label class="form-label">Document complémentaire</label>
                        @if($perte->document_complementaire)
                            <div class="current-file">
                                <div class="file-info"><i class="bi bi-file-earmark-fill fs-4 text-secondary"></i><span>Document actuel</span></div>
                                <a href="{{ asset('storage/' . $perte->document_complementaire) }}" target="_blank" class="btn-view-file"><i class="bi bi-eye"></i> Voir</a>
                            </div>
                        @endif
                        <div class="file-upload-area" onclick="document.getElementById('document_complementaire').click()">
                            <input type="file" id="document_complementaire" name="document_complementaire" style="display:none" accept=".pdf,.jpg,.jpeg,.png" onchange="previewFile(this,'preview3')">
                            <div class="file-upload-icon"><i class="bi bi-cloud-upload"></i></div>
                            <div class="file-upload-text">Cliquez pour télécharger</div>
                        </div>
                        <div id="preview3" class="file-preview" style="display:none"></div>
                        @error('document_complementaire')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="info-alert"><i class="bi bi-info-circle me-2"></i>Laissez vide pour conserver les documents actuels. Un nouveau document remplacera l'ancien.</div>
            </div>

            <!-- Submit -->
            <div class="submit-section">
                <div class="required-note"><i class="bi bi-asterisk text-danger me-1"></i>Les champs marqués d'un astérisque sont obligatoires</div>
                <div>
                    <button type="submit" class="btn-submit" id="submitBtn"><i class="bi bi-check-circle me-2"></i>Mettre à jour la déclaration</button>
                    <a href="{{ route('perte.show', $perte->id) }}" class="btn-outline-secondary ms-2"><i class="bi bi-x-lg me-1"></i>Annuler</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewFile(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const file = input.files[0];
            preview.style.display = 'block';
            preview.innerHTML = `<div><i class="bi bi-check-circle-fill me-2 text-success"></i>${file.name} (${(file.size/1024).toFixed(2)} Ko)</div>`;
        }
    }

    // ============================================================
    // VALIDATION DES DATES
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        const dateDelivrance = document.getElementById('date_delivrance');
        const datePerte = document.getElementById('date_perte');
        const dateError = document.getElementById('dateError');
        const form = document.getElementById('editForm');
        const submitBtn = document.getElementById('submitBtn');

        function validateDates() {
            const delivrance = dateDelivrance.value;
            const perte = datePerte.value;

            if (delivrance && perte) {
                if (perte < delivrance) {
                    dateError.classList.add('show');
                    datePerte.classList.add('error');
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.5';
                    submitBtn.style.cursor = 'not-allowed';
                    return false;
                } else {
                    dateError.classList.remove('show');
                    datePerte.classList.remove('error');
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                    return true;
                }
            }
            return true;
        }

        // Événements pour valider en temps réel
        dateDelivrance.addEventListener('change', function() {
            // Mettre à jour le min de la date de perte
            if (this.value) {
                datePerte.min = this.value;
            }
            validateDates();
        });

        datePerte.addEventListener('change', validateDates);
        datePerte.addEventListener('input', validateDates);

        // Validation avant soumission
        form.addEventListener('submit', function(e) {
            if (!validateDates()) {
                e.preventDefault();
                dateError.classList.add('show');
                datePerte.scrollIntoView({ behavior: 'smooth', block: 'center' });
                datePerte.focus();
            }
        });
    });

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