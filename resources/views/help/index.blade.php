<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aide & Support - e-Déclaration TG</title>
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

        /* Help Header */
        .help-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 2.5rem;
            border-radius: 20px;
            color: white;
            text-align: center;
            margin-bottom: 2rem;
        }

        .help-header h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .help-header p {
            font-size: 1rem;
            opacity: 0.95;
        }

        /* Search */
        .search-section {
            background: white;
            border-radius: 20px;
            padding: 1rem;
            margin-bottom: 2rem;
            border: 1px solid var(--gray-200);
        }

        body.dark-mode .search-section {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .search-box {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.8rem;
            border: 2px solid var(--gray-200);
            border-radius: 50px;
            font-size: 0.9rem;
            background: var(--gray-100);
            transition: all 0.2s;
        }

        body.dark-mode .search-input {
            background: #404048;
            border-color: #4b5563;
            color: #e5e7eb;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-600);
        }

        /* Tabs */
        .help-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            background: white;
            padding: 0.5rem;
            border-radius: 12px;
            border: 1px solid var(--gray-200);
        }

        body.dark-mode .help-tabs {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .help-tab {
            flex: 1;
            padding: 0.8rem;
            border: none;
            background: transparent;
            color: var(--gray-600);
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        body.dark-mode .help-tab {
            color: #9ca3af;
        }

        .help-tab:hover {
            background: rgba(16, 185, 129, 0.08);
            color: var(--primary);
        }

        .help-tab.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* Tab Content */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-out;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* FAQ */
        .faq-item {
            background: white;
            border-radius: 16px;
            margin-bottom: 1rem;
            border: 1px solid var(--gray-200);
            overflow: hidden;
            transition: all 0.2s;
        }

        body.dark-mode .faq-item {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .faq-question {
            padding: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: background 0.2s;
        }

        .faq-question:hover {
            background: var(--gray-100);
        }

        body.dark-mode .faq-question:hover {
            background: #404048;
        }

        .faq-question h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark);
        }

        body.dark-mode .faq-question h3 {
            color: #e5e7eb;
        }

        .faq-icon {
            width: 20px;
            height: 20px;
            color: var(--primary);
            transition: transform 0.3s;
        }

        .faq-item.open .faq-icon {
            transform: rotate(180deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .faq-item.open .faq-answer {
            max-height: 500px;
        }

        .faq-answer-content {
            padding: 0 1.2rem 1.2rem;
            color: var(--gray-600);
            line-height: 1.7;
            font-size: 0.85rem;
        }

        /* Guides */
        .guide-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.2rem;
        }

        .guide-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--gray-200);
            transition: all 0.2s;
            cursor: pointer;
        }

        body.dark-mode .guide-card {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .guide-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            border-color: var(--primary);
        }

        .guide-icon {
            width: 50px;
            height: 50px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }

        .guide-card h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.3rem;
        }

        body.dark-mode .guide-card h3 {
            color: #e5e7eb;
        }

        .guide-card p {
            font-size: 0.8rem;
            color: var(--gray-600);
            line-height: 1.5;
        }

        /* Contact */
        .contact-info {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 16px;
            padding: 1.5rem;
            color: white;
            margin-bottom: 1.5rem;
        }

        .contact-info h3 {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.6rem;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            margin-bottom: 0.5rem;
        }

        .contact-item-icon {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .contact-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--gray-200);
        }

        body.dark-mode .contact-card {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--gray-800);
            margin-bottom: 0.4rem;
        }

        body.dark-mode .form-label {
            color: #e5e7eb;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.7rem;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 0.85rem;
            background: white;
            transition: all 0.2s;
        }

        body.dark-mode .form-input,
        body.dark-mode .form-select,
        body.dark-mode .form-textarea {
            background: #404048;
            border-color: #4b5563;
            color: #e5e7eb;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        /* Alert */
        .alert {
            padding: 0.8rem 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            background: white;
            border-left: 4px solid var(--primary);
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        body.dark-mode .alert {
            background: #2d2d35;
            color: #e5e7eb;
        }

        .alert-success { color: #065f46; }
        body.dark-mode .alert-success { color: #a7f3d0; }

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
            .help-header {
                padding: 1.5rem;
            }
            .help-header h1 {
                font-size: 1.5rem;
            }
            .guide-grid {
                grid-template-columns: 1fr;
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
        <a href="{{ route('perte.index') }}">
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
        <a href="{{ route('help.index') }}" class="active">
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

    <div class="help-header">
        <h1>❓ Centre d'aide</h1>
        <p>Comment pouvons-nous vous aider aujourd'hui ?</p>
    </div>

    <div class="search-section">
        <div class="search-box">
            <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" class="search-input" placeholder="Rechercher dans l'aide..." id="searchInput">
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    <div class="help-tabs">
        <button class="help-tab active" onclick="showTab('faq')">❓ FAQ</button>
        <button class="help-tab" onclick="showTab('guides')">📚 Guides</button>
        <button class="help-tab" onclick="showTab('contact')">📧 Contact</button>
    </div>

    <!-- FAQ Tab -->
    <div class="tab-content active" id="faq-content">
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <h3>Comment créer une déclaration de perte ?</h3>
                <svg class="faq-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    <ol style="padding-left: 1.2rem;">
                        <li>Cliquez sur "Nouvelle Déclaration" dans le menu</li>
                        <li>Sélectionnez le type de pièce perdue</li>
                        <li>Remplissez tous les champs obligatoires (date, lieu, circonstances)</li>
                        <li>Vérifiez vos informations</li>
                        <li>Cliquez sur "Soumettre la déclaration"</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <h3>Combien de temps prend le traitement d'une déclaration ?</h3>
                <svg class="faq-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    <ul style="padding-left: 1.2rem;">
                        <li><strong>Carte d'identité :</strong> 2-3 jours ouvrables</li>
                        <li><strong>Permis de conduire :</strong> 3-5 jours ouvrables</li>
                        <li><strong>Passeport :</strong> 5-7 jours ouvrables</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <h3>Comment récupérer mon attestation de perte ?</h3>
                <svg class="faq-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    Allez dans "Mes Déclarations", trouvez la déclaration validée et cliquez sur "Télécharger l'attestation". Le PDF sera sauvegardé.
                </div>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <h3>Que faire si ma déclaration est rejetée ?</h3>
                <svg class="faq-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    Consultez le motif de rejet dans "Mes Déclarations", corrigez les informations et soumettez une nouvelle déclaration.
                </div>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <h3>Comment modifier mes informations personnelles ?</h3>
                <svg class="faq-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    Allez dans "Paramètres" → onglet "Profil". Modifiez les champs et cliquez sur "Enregistrer".
                </div>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <h3>Le service est-il gratuit ?</h3>
                <svg class="faq-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    Oui, e-Déclaration TG est entièrement gratuit pour tous les citoyens togolais. Service public.
                </div>
            </div>
        </div>
    </div>

    <!-- Guides Tab -->
    <div class="tab-content" id="guides-content">
        <div class="guide-grid">
            <div class="guide-card" onclick="alert('Tutoriel vidéo bientôt disponible')">
                <div class="guide-icon">🎥</div>
                <h3>Tutoriel vidéo</h3>
                <p>Apprenez à utiliser la plateforme en vidéo, étape par étape.</p>
            </div>
            <div class="guide-card" onclick="alert('Guide PDF bientôt disponible')">
                <div class="guide-icon">📄</div>
                <h3>Guide PDF</h3>
                <p>Téléchargez le guide complet d'utilisation au format PDF.</p>
            </div>
            <div class="guide-card" onclick="alert('Liste des pièces bientôt disponible')">
                <div class="guide-icon">📋</div>
                <h3>Pièces acceptées</h3>
                <p>Consultez la liste complète des types de pièces que vous pouvez déclarer.</p>
            </div>
            <div class="guide-card" onclick="alert('Démarches administratives bientôt disponible')">
                <div class="guide-icon">🏛️</div>
                <h3>Démarches après déclaration</h3>
                <p>Que faire après avoir obtenu votre attestation de perte.</p>
            </div>
        </div>
    </div>

    <!-- Contact Tab -->
    <div class="tab-content" id="contact-content">
        <div class="contact-info">
            <h3>📞 Informations de contact</h3>
            <div class="contact-item"><div class="contact-item-icon">📧</div><div><strong>Email</strong><br>support@e-declaration.tg</div></div>
            <div class="contact-item"><div class="contact-item-icon">📱</div><div><strong>Téléphone</strong><br>+228 90 00 00 00</div></div>
            <div class="contact-item"><div class="contact-item-icon">🕐</div><div><strong>Horaires</strong><br>Lun - Ven : 8h00 - 17h00</div></div>
            <div class="contact-item"><div class="contact-item-icon">📍</div><div><strong>Adresse</strong><br>Lomé, Togo</div></div>
        </div>

        <div class="contact-card">
            <h3 style="margin-bottom: 1rem; color: var(--dark);">Envoyez-nous un message</h3>
            <form method="POST" action="{{ route('help.send') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Catégorie</label>
                    <select class="form-select" name="category" required>
                        <option value="">Sélectionnez une catégorie</option>
                        <option value="technique">Problème technique</option>
                        <option value="declaration">Question sur une déclaration</option>
                        <option value="compte">Gestion de compte</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Sujet</label>
                    <input type="text" class="form-input" name="subject" placeholder="Résumez votre question" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea class="form-textarea" name="message" placeholder="Décrivez votre problème ou question en détail..." required></textarea>
                </div>
                <button type="submit" class="btn-primary">
                    📧 Envoyer le message
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.querySelectorAll('.help-tab').forEach(t => t.classList.remove('active'));
        document.getElementById(tabName + '-content').classList.add('active');
        event.target.closest('.help-tab').classList.add('active');
    }

    function toggleFaq(element) {
        const faqItem = element.closest('.faq-item');
        const wasOpen = faqItem.classList.contains('open');
        document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
        if (!wasOpen) faqItem.classList.add('open');
    }

    // Search
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.faq-item').forEach(item => {
                const question = item.querySelector('h3').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer-content')?.textContent.toLowerCase() || '';
                item.style.display = (question.includes(term) || answer.includes(term)) ? 'block' : (term ? 'none' : 'block');
            });
        });
    }

    // Mode sombre
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

    // Auto-hide alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
</body>
</html>