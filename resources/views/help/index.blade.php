<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aide & Support - e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Nunito', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
        }

        /* ===== SIDEBAR (intégrée) ===== */
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

        /* ===== TOP BAR ICONS ===== */
        .top-bar-icons {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            margin-bottom: 1rem;
            justify-content: flex-end;
            padding: 0 2.5rem;
        }

        .icon-btn {
            background: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            position: relative;
            border: 1px solid rgba(0,0,0,0.03);
        }

        .icon-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(39, 174, 96, 0.15);
            border-color: #27ae60;
        }

        .icon-btn svg {
            width: 22px;
            height: 22px;
            stroke: #475569;
            transition: all 0.3s;
        }

        .icon-btn:hover svg {
            stroke: #27ae60;
        }

        .theme-toggle svg {
            transition: transform 0.5s ease;
        }

        .theme-toggle:hover svg {
            transform: rotate(180deg);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            min-width: 20px;
            height: 20px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
        }

        /* ===== CONTENU PRINCIPAL ===== */
        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 2rem;
        }

        .main {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        /* Header */
        .help-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3rem 2.5rem;
            color: white;
            text-align: center;
        }

        .help-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .help-header p {
            font-size: 1.2rem;
            opacity: 0.95;
        }

        /* Search */
        .search-section {
            padding: 2rem 2.5rem;
            background: white;
            border-bottom: 1px solid #e8eef5;
        }

        .search-box {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 1rem 1.2rem 1rem 3.5rem;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        /* Content */
        .content {
            padding: 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Alert */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            animation: slideDown 0.3s;
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #27ae60;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Tabs */
        .help-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            background: white;
            padding: 0.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .help-tab {
            flex: 1;
            padding: 1rem;
            border: none;
            background: transparent;
            color: #64748b;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .help-tab:hover {
            background: #f8fafc;
        }

        .help-tab.active {
            background: #667eea;
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* FAQ */
        .faq-item {
            background: white;
            border-radius: 12px;
            margin-bottom: 1rem;
            border: 1px solid #e8eef5;
            overflow: hidden;
            transition: all 0.3s;
        }

        .faq-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .faq-question {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.2s;
        }

        .faq-question:hover {
            background: #f8fafc;
        }

        .faq-question h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1e3a5f;
            flex: 1;
        }

        .faq-icon {
            width: 24px;
            height: 24px;
            color: #667eea;
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
            padding: 0 1.5rem 1.5rem;
            color: #64748b;
            line-height: 1.8;
        }

        /* Guide Cards */
        .guide-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .guide-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            border: 1px solid #e8eef5;
            transition: all 0.3s;
            cursor: pointer;
        }

        .guide-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.1);
            border-color: #667eea;
        }

        .guide-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .guide-card h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e3a5f;
            margin-bottom: 0.5rem;
        }

        .guide-card p {
            color: #64748b;
            line-height: 1.6;
        }

        /* Contact Form */
        .contact-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            border: 1px solid #e8eef5;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.6rem;
            font-size: 0.95rem;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.9rem 1.1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.2s;
            font-family: 'Nunito', sans-serif;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-textarea {
            min-height: 150px;
            resize: vertical;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        /* Contact Info */
        .contact-info {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .contact-info h3 {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        .contact-item-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
                margin-bottom: 1rem;
            }
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            .help-header h1 {
                font-size: 2rem;
            }
            .guide-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Dark mode styles */
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
        body.dark-mode .search-section {
            background: #2d2d2d;
        }
        body.dark-mode .help-tabs {
            background: #2d2d2d;
        }
        body.dark-mode .faq-item {
            background: #2d2d2d;
            color: #e5e7eb;
        }
        body.dark-mode .faq-question h3 {
            color: white;
        }
        body.dark-mode .guide-card {
            background: #2d2d2d;
            color: white;
        }
        body.dark-mode .contact-card {
            background: #2d2d2d;
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
    </style>
</head>
<body>

    <!-- ===== SIDEBAR (intégrée) ===== -->
    @php
        $unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
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

    <!-- ===== CONTENU PRINCIPAL ===== -->
    <div class="main-content">
        <div class="main">
            <!-- Top Bar Icons -->
            <div class="top-bar-icons">
                <!-- Bouton Mode Sombre -->
                <button class="icon-btn theme-toggle" onclick="toggleGlobalDarkMode()" title="Changer le thème">
                    <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                <!-- Bouton Notifications -->
                <button class="icon-btn" onclick="openNotifications()" title="Voir les notifications">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @php
                        $notificationsCount = \App\Models\Perte::where('user_id', auth()->id())->where('statut', 'en_attente')->count();
                    @endphp
                    @if($notificationsCount > 0)
                        <span class="notification-badge">{{ $notificationsCount }}</span>
                    @endif
                </button>
            </div>

            <!-- Header -->
            <div class="help-header">
                <h1>❓ Centre d'aide</h1>
                <p>Comment pouvons-nous vous aider aujourd'hui ?</p>
            </div>

            <!-- Search -->
            <div class="search-section">
                <div class="search-box">
                    <svg class="search-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" class="search-input" placeholder="Rechercher dans l'aide..." id="searchInput">
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                @if(session('success'))
                    <div class="alert alert-success">
                        <span>✅</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Tabs -->
                <div class="help-tabs">
                    <button class="help-tab active" onclick="showTab('faq')">
                        ❓ FAQ
                    </button>
                    <button class="help-tab" onclick="showTab('guides')">
                        📚 Guides
                    </button>
                    <button class="help-tab" onclick="showTab('contact')">
                        📧 Contact
                    </button>
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
                                Pour créer une déclaration de perte :
                                <ol style="margin-top: 0.5rem; padding-left: 1.5rem;">
                                    <li>Cliquez sur "Nouvelle Déclaration" dans le menu</li>
                                    <li>Sélectionnez le type de pièce perdue</li>
                                    <li>Remplissez tous les champs obligatoires (date, lieu, circonstances)</li>
                                    <li>Vérifiez vos informations</li>
                                    <li>Cliquez sur "Soumettre la déclaration"</li>
                                </ol>
                                Vous recevrez un numéro de déclaration unique que vous pourrez suivre dans "Mes Déclarations".
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
                                Le délai de traitement dépend du type de pièce :
                                <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                                    <li><strong>Carte d'identité :</strong> 2-3 jours ouvrables</li>
                                    <li><strong>Permis de conduire :</strong> 3-5 jours ouvrables</li>
                                    <li><strong>Passeport :</strong> 5-7 jours ouvrables</li>
                                    <li><strong>Autres documents :</strong> 1-3 jours ouvrables</li>
                                </ul>
                                Vous recevrez une notification par email dès que votre déclaration sera traitée.
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
                                Une fois votre déclaration validée :
                                <ol style="margin-top: 0.5rem; padding-left: 1.5rem;">
                                    <li>Allez dans "Mes Déclarations"</li>
                                    <li>Trouvez votre déclaration validée</li>
                                    <li>Cliquez sur "Télécharger l'attestation"</li>
                                    <li>Sauvegardez le fichier PDF sur votre appareil</li>
                                </ol>
                                L'attestation est valable et officielle pour vos démarches administratives.
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
                                Si votre déclaration est rejetée :
                                <ol style="margin-top: 0.5rem; padding-left: 1.5rem;">
                                    <li>Consultez le motif de rejet dans "Mes Déclarations"</li>
                                    <li>Corrigez les informations selon les instructions</li>
                                    <li>Créez une nouvelle déclaration avec les bonnes informations</li>
                                    <li>Ou contactez le support pour plus d'aide</li>
                                </ol>
                                Un motif détaillé vous sera toujours fourni pour comprendre le rejet.
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
                                Pour modifier vos informations :
                                <ol style="margin-top: 0.5rem; padding-left: 1.5rem;">
                                    <li>Allez dans "Paramètres"</li>
                                    <li>Cliquez sur l'onglet "Profil"</li>
                                    <li>Modifiez les champs souhaités</li>
                                    <li>Cliquez sur "Enregistrer les modifications"</li>
                                </ol>
                                Pour changer votre email ou mot de passe, utilisez l'onglet "Sécurité".
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
                                Oui, le service e-Déclaration TG est entièrement gratuit pour tous les citoyens togolais. 
                                La création de compte, les déclarations de perte et le téléchargement des attestations 
                                ne nécessitent aucun paiement. C'est un service public mis à disposition par l'État togolais.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guides Tab -->
                <div class="tab-content" id="guides-content">
                    <div class="guide-grid">
                        <div class="guide-card" onclick="alert('Guide vidéo bientôt disponible')">
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

                        <div class="guide-card" onclick="alert('Sécurité bientôt disponible')">
                            <div class="guide-icon">🔒</div>
                            <h3>Sécurité de vos données</h3>
                            <p>Comment nous protégeons vos informations personnelles.</p>
                        </div>

                        <div class="guide-card" onclick="alert('Accessibilité bientôt disponible')">
                            <div class="guide-icon">♿</div>
                            <h3>Accessibilité</h3>
                            <p>Fonctionnalités d'accessibilité pour tous les utilisateurs.</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Tab -->
                <div class="tab-content" id="contact-content">
                    <div class="contact-info">
                        <h3>📞 Informations de contact</h3>
                        
                        <div class="contact-item">
                            <div class="contact-item-icon">📧</div>
                            <div>
                                <div style="font-weight: 600;">Email</div>
                                <div>support@e-declaration.tg</div>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-item-icon">📱</div>
                            <div>
                                <div style="font-weight: 600;">Téléphone</div>
                                <div>+228 XX XX XX XX</div>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-item-icon">🕐</div>
                            <div>
                                <div style="font-weight: 600;">Horaires</div>
                                <div>Lun - Ven : 8h00 - 17h00</div>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-item-icon">📍</div>
                            <div>
                                <div style="font-weight: 600;">Adresse</div>
                                <div>Lomé, Togo</div>
                            </div>
                        </div>
                    </div>

                    <div class="contact-card">
                        <h3 style="margin-bottom: 1.5rem; color: #1e3a5f;">Envoyez-nous un message</h3>
                        
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

                            <button type="submit" class="btn btn-primary">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Envoyer le message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ============================================
        // TAB NAVIGATION
        // ============================================
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            document.querySelectorAll('.help-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            document.getElementById(tabName + '-content').classList.add('active');
            event.target.closest('.help-tab').classList.add('active');
        }

        // ============================================
        // FAQ TOGGLE
        // ============================================
        function toggleFaq(element) {
            const faqItem = element.closest('.faq-item');
            const wasOpen = faqItem.classList.contains('open');
            
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('open');
            });
            
            if (!wasOpen) {
                faqItem.classList.add('open');
            }
        }

        // ============================================
        // SEARCH
        // ============================================
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                const faqItems = document.querySelectorAll('.faq-item');
                
                faqItems.forEach(item => {
                    const question = item.querySelector('h3').textContent.toLowerCase();
                    const answer = item.querySelector('.faq-answer-content').textContent.toLowerCase();
                    
                    if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = searchTerm ? 'none' : 'block';
                    }
                });
            });
        }

        // ============================================
        // NOTIFICATIONS
        // ============================================
        function openNotifications() {
            window.location.href = '{{ route("notifications.index") }}';
        }

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
            window.dispatchEvent(new CustomEvent('themeChanged', { detail: { darkMode: isDark } }));
        }

        function loadTheme() {
            // Récupérer le thème depuis le serveur (via l'utilisateur connecté)
            const serverTheme = '{{ $user->theme ?? 'light' }}';
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
            }).catch(error => console.log('Theme sync error:', error));
            
            showNotification('Mode ' + (isDark ? 'sombre' : 'clair') + ' activé');
        }

        window.addEventListener('storage', function(e) {
            if (e.key === 'darkMode') {
                const isDark = e.newValue === 'dark';
                applyTheme(isDark);
            }
        });

        window.addEventListener('themeChanged', function(e) {
            // Mettre à jour le toggle dans les préférences si présent
            const darkModeToggle = document.querySelector('input[name="dark_mode"]');
            if (darkModeToggle) {
                darkModeToggle.checked = e.detail.darkMode;
            }
        });

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