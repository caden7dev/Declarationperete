<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Nouvelle Déclaration - e-Déclaration TG</title>
    <script>
        // Anti-flash blanc - exécuté immédiatement
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

        /* En-tête (date & icônes) */
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

        /* Formulaire */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border-radius: 24px;
            padding: 2rem 2rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            border: 1px solid var(--gray-200);
        }

        body.dark-mode .container {
            background: #2d2d35;
            border-color: #404048;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-header h2 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark);
        }

        body.dark-mode .form-header h2 {
            color: #f1f5f9;
        }

        .form-header p {
            color: var(--gray-600);
            font-size: 0.9rem;
            margin-top: 0.3rem;
        }

        .copy-info {
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        body.dark-mode .copy-info {
            background: #1e3a5f;
            color: #e5e7eb;
        }

        .section {
            background: var(--gray-100);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--gray-200);
        }

        body.dark-mode .section {
            background: #404048;
            border-color: #4b5563;
        }

        .section-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1.2rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .section-number {
            background: var(--primary);
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group.full {
            grid-column: span 2;
        }

        label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--gray-800);
        }

        label .required {
            color: var(--danger);
        }

        label .auto-filled {
            background: #d1fae5;
            color: #065f46;
            padding: 0.1rem 0.5rem;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        input, select, textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            background: white;
        }

        body.dark-mode input,
        body.dark-mode select,
        body.dark-mode textarea {
            background: #4b5563;
            border-color: #6b7280;
            color: #e5e7eb;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        input[readonly] {
            background: var(--gray-100);
            color: var(--gray-600);
            cursor: not-allowed;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .helper-text {
            font-size: 0.7rem;
            color: var(--gray-600);
            margin-top: 0.2rem;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 1rem;
            margin: 1.5rem 0;
        }

        body.dark-mode .checkbox-group {
            background: #332d1a;
            border-color: #a16207;
        }

        .checkbox-group input {
            width: 18px;
            height: 18px;
            margin: 0;
            cursor: pointer;
        }

        .checkbox-group label {
            margin: 0;
            cursor: pointer;
            font-size: 0.85rem;
            color: #b45309;
        }

        body.dark-mode .checkbox-group label {
            color: #fbbf24;
        }

        .submit-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 0.9rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .text-danger {
            color: var(--danger);
            font-size: 0.75rem;
            margin-top: 0.2rem;
        }

        .error-box {
            background: #fef2f2;
            border-left: 4px solid var(--danger);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .error-box ul {
            margin: 0;
            padding-left: 1.2rem;
            color: #991b1b;
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
            .grid {
                grid-template-columns: 1fr;
            }
            .form-group.full {
                grid-column: span 1;
            }
            .dashboard-top {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

@php
    use App\Models\Notification;
    $user = auth()->user();

    // ============================================================
    // ⚠️ COMPTEUR CORRIGÉ : Exclusion des messages (agent_message)
    // et des notifications expirées
    // ============================================================
    $unreadNotificationsCount = Notification::where('user_id', $user->id)
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
        <a href="{{ route('perte.index') }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Mes Déclarations
        </a>
        <a href="{{ route('perte.create') }}" class="active">
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
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif

    <div class="dashboard-top">
        <div class="dashboard-left">
            <div class="dashboard-title">
                <h1>Nouvelle déclaration</h1>
            </div>
        </div>
        <div class="header-right">
            <div class="date-time">
                {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY - HH:mm') }}
            </div>
            <button class="icon-btn theme-toggle" id="themeToggleBtn" title="Changer le thème">
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
    </div>

    <div class="container">
        <div class="form-header">
            <h2>📝 Nouvelle déclaration de perte</h2>
            <p>Veuillez remplir tous les champs requis avec attention</p>
        </div>

        {{-- Message d'information en cas de copie depuis une déclaration non retrouvée --}}
        @if(isset($oldPerte) && $oldPerte)
            <div class="copy-info">
                <i class="bi bi-info-circle-fill fs-4"></i>
                <div>
                    <strong>Copie depuis votre déclaration non retrouvée #{{ $oldPerte->numero_declaration ?? $oldPerte->id }}</strong><br>
                    Les champs ci‑dessous ont été pré‑remplis avec vos informations précédentes. Vous pouvez les modifier avant de soumettre.
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('perte.store') }}" enctype="multipart/form-data" id="declarationForm">
            @csrf

            <!-- Section 1 : Informations du déclarant (auto-remplies) -->
            <div class="section">
                <div class="section-title">
                    <span class="section-number">1</span> Informations du déclarant
                </div>
                <div class="grid">
                    <div class="form-group">
                        <label>Nom <span class="required">*</span> <span class="auto-filled">✓ Auto-rempli</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name ?? $user->name) }}" readonly required>
                    </div>
                    <div class="form-group">
                        <label>Prénom(s) <span class="required">*</span> <span class="auto-filled">✓ Auto-rempli</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name ?? $user->name) }}" readonly required>
                    </div>
                    <div class="form-group">
                        <label>Numéro de téléphone <span class="required">*</span> <span class="auto-filled">✓ Auto-rempli</span></label>
                        <input type="text" name="contact" value="{{ old('contact', $user->contact ?? $user->phone) }}" readonly required>
                    </div>
                    <div class="form-group">
                        <label>Adresse e-mail <span class="required">*</span> <span class="auto-filled">✓ Auto-rempli</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" readonly required>
                    </div>
                </div>
            </div>

            <!-- Section 2 : Informations sur la pièce perdue -->
            <div class="section">
                <div class="section-title">
                    <span class="section-number">2</span> Informations sur la pièce perdue
                </div>
                <div class="grid">
                    <div class="form-group">
                        <label>Type de pièce <span class="required">*</span></label>
                        <select name="type_piece" required class="@error('type_piece') is-invalid @enderror" id="type_piece">
                            <option value="">-- Sélectionner le type de pièce --</option>
                            @foreach($typesPieces ?? [] as $type)
                                @php
                                    $selected = old('type_piece', $oldPerte->type_piece ?? '') == $type->nom;
                                @endphp
                                <option value="{{ $type->nom }}" {{ $selected ? 'selected' : '' }}>{{ $type->nom }}</option>
                            @endforeach
                        </select>
                        @error('type_piece') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>Numéro de la pièce</label>
                        <input type="text" name="numero_piece" value="{{ old('numero_piece', $oldPerte->numero_piece ?? '') }}" placeholder="Ex: 123456789">
                        @error('numero_piece') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>Date de délivrance</label>
                        <input type="date" name="date_delivrance" id="date_delivrance" 
                               value="{{ old('date_delivrance', $oldPerte->date_delivrance ?? '') }}" 
                               max="{{ date('Y-m-d') }}">
                        @error('date_delivrance') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>Autorité de délivrance</label>
                        <input type="text" name="autorite_delivrance" value="{{ old('autorite_delivrance', $oldPerte->autorite_delivrance ?? '') }}" placeholder="Ex: Préfecture de Lomé">
                        @error('autorite_delivrance') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3 : Détails de la perte -->
            <div class="section">
                <div class="section-title">
                    <span class="section-number">3</span> Détails de la perte
                </div>
                <div class="grid">
                    <div class="form-group">
                        <label>Date de la perte <span class="required">*</span></label>
                        <input type="date" name="date_perte" id="date_perte" 
                               value="{{ old('date_perte', $oldPerte ? $oldPerte->date_perte->format('Y-m-d') : '') }}" 
                               max="{{ date('Y-m-d') }}" required>
                        <div class="date-error" id="dateError">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            La date de perte doit être postérieure ou égale à la date de délivrance.
                        </div>
                        @error('date_perte') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>Lieu de la perte <span class="required">*</span></label>
                        <input type="text" name="lieu_perte" value="{{ old('lieu_perte', $oldPerte->lieu_perte ?? '') }}" placeholder="Ex: Marché de Lomé" required>
                        @error('lieu_perte') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group full">
                        <label>Circonstances de la perte</label>
                        <textarea name="circonstances" rows="4" placeholder="Décrivez les circonstances de la perte...">{{ old('circonstances', $oldPerte->circonstances ?? '') }}</textarea>
                        @error('circonstances') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 4 : Justificatifs (optionnels) -->
            <div class="section">
                <div class="section-title">
                    <span class="section-number">4</span> Justificatifs (optionnels)
                </div>
                <div class="grid">
                    <div class="form-group">
                        <label>Copie de la pièce (si existante)</label>
                        <input type="file" name="copie_piece" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="helper-text">PDF, JPG ou PNG • Max 2 Mo</div>
                        @error('copie_piece') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>Déclaration de vol (police)</label>
                        <input type="file" name="declaration_vol" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="helper-text">Si vol, joindre le PV de police</div>
                        @error('declaration_vol') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group full">
                        <label>Document complémentaire</label>
                        <input type="file" name="document_complementaire" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="helper-text">Tout autre document utile</div>
                        @error('document_complementaire') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Case à cocher de certification -->
            <div class="checkbox-group">
                <input type="checkbox" id="certify" required>
                <label for="certify">
                    Je certifie sur l'honneur l'exactitude des informations fournies et comprends que toute fausse déclaration peut entraîner des poursuites.
                </label>
            </div>

            <div style="text-align: center;">
                <button type="submit" class="submit-btn" id="submitBtn">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:20px;height:20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Soumettre la déclaration
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ============================================================
    // VALIDATION DES DATES
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        const dateDelivrance = document.getElementById('date_delivrance');
        const datePerte = document.getElementById('date_perte');
        const dateError = document.getElementById('dateError');
        const form = document.getElementById('declarationForm');
        const submitBtn = document.getElementById('submitBtn');

        function validateDates() {
            const delivrance = dateDelivrance.value;
            const perte = datePerte.value;

            if (delivrance && perte) {
                if (perte < delivrance) {
                    dateError.classList.add('show');
                    datePerte.style.borderColor = '#ef4444';
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.5';
                    submitBtn.style.cursor = 'not-allowed';
                    return false;
                } else {
                    dateError.classList.remove('show');
                    datePerte.style.borderColor = '';
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

    // ============================================================
    // THÈME
    // ============================================================
    function applyTheme(isDark) {
        if (isDark) {
            document.body.classList.add('dark-mode');
            const icon = document.querySelector('#themeIcon');
            if (icon) {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
            }
        } else {
            document.body.classList.remove('dark-mode');
            const icon = document.querySelector('#themeIcon');
            if (icon) {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
            }
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
</script>
</body>
</html>