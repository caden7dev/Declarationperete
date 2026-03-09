<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Paramètres - e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Tous les styles existants (inchangés) */
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            font-family: 'Nunito', sans-serif;
        }

        :root {
            --primary: #27ae60;
            --primary-dark: #219653;
            --bg-color: #f5f7fa;
            --text-color: #1e3a5f;
            --card-bg: #ffffff;
            --sidebar-bg: #ffffff;
            --border-color: #e8eef5;
            --hover-color: #f1f5f9;
            --input-bg: #f8fafc;
            --input-border: #e2e8f0;
        }

        body { 
            display: flex; 
            min-height: 100vh; 
            background: var(--bg-color);
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        /* Dark mode */
        body.dark-mode {
            --bg-color: #1a1a1a;
            --text-color: #ffffff;
            --card-bg: #2d2d2d;
            --sidebar-bg: #2d2d2d;
            --border-color: #404040;
            --hover-color: #404040;
            --input-bg: #404040;
            --input-border: #4b5563;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            box-shadow: 2px 0 15px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 10;
            border-right: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
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

        body.dark-mode .sidebar-nav a {
            color: #9ca3af;
        }

        .sidebar-nav a:hover {
            background: var(--hover-color);
            color: var(--primary);
        }

        body.dark-mode .sidebar-nav a:hover {
            background: #404040;
        }

        .sidebar-nav a.active {
            background: rgba(39, 174, 96, 0.1);
            color: var(--primary);
            font-weight: 700;
        }

        .sidebar-nav a svg {
            width: 20px;
            height: 20px;
        }

        .sidebar-footer {
            padding: 1.5rem 1rem;
            border-top: 1px solid var(--border-color);
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

        body.dark-mode .btn-logout {
            background: #404040;
            color: #ef4444;
        }

        .btn-logout:hover {
            background: #ffe8e6;
        }

        body.dark-mode .btn-logout:hover {
            background: #4b5563;
        }

        /* Main content */
        .main {
            margin-left: 280px;
            flex: 1;
            padding: 0;
            background: var(--bg-color);
            transition: all 0.3s ease;
        }

        /* Top Bar */
        .top-bar {
            background: var(--card-bg);
            padding: 1.5rem 2.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 5;
            border-bottom: 1px solid var(--border-color);
        }

        .top-bar h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-color);
            margin-bottom: 0.3rem;
        }

        .top-bar p {
            color: #64748b;
            font-size: 0.95rem;
        }

        body.dark-mode .top-bar p {
            color: #9ca3af;
        }

        /* Top Bar Icons */
        .top-bar-icons {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            margin-bottom: 1rem;
            justify-content: flex-end;
            padding: 0 2.5rem;
        }

        .icon-btn {
            background: var(--card-bg);
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
            border: 1px solid var(--border-color);
        }

        .icon-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(39, 174, 96, 0.15);
            border-color: var(--primary);
        }

        .icon-btn svg {
            width: 22px;
            height: 22px;
            stroke: #64748b;
            transition: all 0.3s;
        }

        body.dark-mode .icon-btn svg {
            stroke: #9ca3af;
        }

        .icon-btn:hover svg {
            stroke: var(--primary);
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

        /* Content */
        .content {
            padding: 2.5rem;
            max-width: 1200px;
        }

        /* Alert */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            animation: slideDown 0.3s ease-out;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            background: var(--card-bg);
            border-left: 4px solid var(--primary);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            color: var(--primary);
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #e74c3c;
        }

        body.dark-mode .alert-error {
            background: #404040;
            color: #ef4444;
        }

        /* Profile Header Card */
        .profile-header-card {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            padding: 2.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(39, 174, 96, 0.2);
            display: flex;
            align-items: center;
            gap: 2rem;
            color: white;
        }

        .profile-avatar-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 800;
            border: 4px solid rgba(255,255,255,0.3);
            flex-shrink: 0;
        }

        .profile-header-info h2 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .profile-header-info p {
            opacity: 0.9;
            font-size: 1.05rem;
        }

        .profile-badges {
            display: flex;
            gap: 0.8rem;
            margin-top: 1rem;
        }

        .badge {
            background: rgba(255,255,255,0.2);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            background: var(--card-bg);
            padding: 0.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
        }

        .tab {
            flex: 1;
            padding: 1rem 1.5rem;
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

        body.dark-mode .tab {
            color: #9ca3af;
        }

        .tab:hover {
            background: var(--hover-color);
            color: var(--primary);
        }

        .tab.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
        }

        .tab svg {
            width: 18px;
            height: 18px;
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

        /* Settings Card */
        .settings-card {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            transition: all 0.3s;
        }

        .settings-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .settings-card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .settings-card-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .settings-card-title {
            flex: 1;
        }

        .settings-card-title h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 0.3rem;
        }

        .settings-card-title p {
            font-size: 0.9rem;
            color: #64748b;
        }

        body.dark-mode .settings-card-title p {
            color: #9ca3af;
        }

        /* Form Elements */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.6rem;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 0.85rem 1.1rem;
            border: 2px solid var(--input-border);
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: var(--input-bg);
            color: var(--text-color);
            font-family: 'Nunito', sans-serif;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            background: var(--card-bg);
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        }

        .form-input:disabled {
            background: var(--hover-color);
            color: #94a3b8;
            cursor: not-allowed;
        }

        /* Buttons */
        .btn {
            padding: 0.9rem 1.8rem;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
        }

        .btn svg {
            width: 18px;
            height: 18px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(39, 174, 96, 0.35);
        }

        .btn-secondary {
            background: var(--hover-color);
            color: #64748b;
        }

        body.dark-mode .btn-secondary {
            color: #9ca3af;
        }

        .btn-secondary:hover {
            background: var(--border-color);
            color: var(--text-color);
        }

        .btn-danger {
            background: #fff1f0;
            color: #e74c3c;
        }

        body.dark-mode .btn-danger {
            background: #404040;
            color: #ef4444;
        }

        .btn-danger:hover {
            background: #ffe8e6;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        /* Setting Item */
        .setting-item {
            padding: 1.5rem;
            background: var(--hover-color);
            border-radius: 12px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .setting-item:hover {
            background: var(--card-bg);
            border-color: var(--border-color);
        }

        .setting-item-info {
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }

        .setting-icon {
            width: 50px;
            height: 50px;
            background: var(--card-bg);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .setting-text h4 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 0.3rem;
        }

        .setting-text p {
            font-size: 0.85rem;
            color: #64748b;
        }

        body.dark-mode .setting-text p {
            color: #9ca3af;
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            width: 52px;
            height: 28px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: 0.3s;
            border-radius: 28px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: var(--primary);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(24px);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .stat-mini {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            border: 2px solid var(--border-color);
            transition: all 0.2s;
        }

        .stat-mini:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(39, 174, 96, 0.15);
        }

        .stat-mini-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .stat-mini-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-color);
            margin-bottom: 0.3rem;
        }

        .stat-mini-label {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 600;
        }

        body.dark-mode .stat-mini-label {
            color: #9ca3af;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .modal.active {
            display: flex;
            animation: fadeIn 0.2s ease-out;
        }

        .modal-content {
            background: var(--card-bg);
            padding: 2.5rem;
            border-radius: 20px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s ease-out;
            border: 1px solid var(--border-color);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .modal-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-color);
        }

        .modal-close {
            background: var(--hover-color);
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            font-size: 1.4rem;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: var(--border-color);
            color: var(--text-color);
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
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .tabs {
                flex-wrap: wrap;
            }

            .tab {
                flex: 1 1 auto;
                min-width: 150px;
            }

            .top-bar-icons {
                justify-content: center;
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        * {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
    </style>
    @include('layouts.partials.theme-styles')
</head>
<body>



    @include('partials.sidebar')

    <!-- Main Content -->
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
                    $notificationsCount = \App\Models\Perte::where('user_id', auth()->id())
                        ->where('statut', 'en_attente')
                        ->count();
                @endphp
                @if($notificationsCount > 0)
                    <span class="notification-badge">{{ $notificationsCount }}</span>
                @endif
            </button>
        </div>

        <!-- Top Bar -->
        <div class="top-bar">
            <h1>⚙️ Paramètres du compte</h1>
            <p>Gérez vos informations personnelles et vos préférences</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <span>❌</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Profile Header -->
            <div class="profile-header-card">
                <div class="profile-avatar-large">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="profile-header-info">
                    <h2>{{ $user->name }}</h2>
                    <p>{{ $user->email }}</p>
                    <div class="profile-badges">
                        <div class="badge">✓ Compte vérifié</div>
                        <div class="badge">🇹🇬 Citoyen togolais</div>
                        <div class="badge">📅 Membre depuis {{ $user->created_at->format('M Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab active" onclick="showTab('profile')">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil
                </button>
                <button class="tab" onclick="showTab('security')">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Sécurité
                </button>
                <button class="tab" onclick="showTab('stats')">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Statistiques
                </button>
                <button class="tab" onclick="showTab('preferences')">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    Préférences
                </button>
            </div>

            <!-- Tab Content: Profile -->
            <div class="tab-content active" id="profile-content">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <div class="settings-card-icon">👤</div>
                        <div class="settings-card-title">
                            <h3>Informations personnelles</h3>
                            <p>Mettez à jour vos informations de profil</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                        @csrf
                        @method('PUT')

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Nom complet *</label>
                                <input type="text" class="form-input" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-input" value="{{ $user->email }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Téléphone</label>
                                <input type="tel" class="form-input" name="contact" value="{{ old('contact', $user->contact ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Date de naissance</label>
                                <input type="date" class="form-input" name="birth_date" value="{{ old('birth_date', $user->birth_date ?? '') }}">
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Adresse complète</label>
                                <input type="text" class="form-input" name="address" value="{{ old('address', $user->address ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nationalité</label>
                                <input type="text" class="form-input" name="nationality" value="{{ old('nationality', $user->nationality ?? 'Togolaise') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Genre</label>
                                <select class="form-input" name="gender">
                                    <option value="M" {{ old('gender', $user->gender) == 'M' ? 'selected' : '' }}>Masculin</option>
                                    <option value="F" {{ old('gender', $user->gender) == 'F' ? 'selected' : '' }}>Féminin</option>
                                </select>
                            </div>
                        </div>

                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Enregistrer les modifications
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                Réinitialiser
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab Content: Security -->
            <div class="tab-content" id="security-content">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <div class="settings-card-icon">🔐</div>
                        <div class="settings-card-title">
                            <h3>Sécurité du compte</h3>
                            <p>Protégez votre compte avec un mot de passe fort</p>
                        </div>
                    </div>

                    <div class="setting-item">
                        <div class="setting-item-info">
                            <div class="setting-icon">🔑</div>
                            <div class="setting-text">
                                <h4>Mot de passe</h4>
                                <p>Dernière modification {{ $user->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <button class="btn btn-primary" onclick="openModal('passwordModal')">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Modifier
                        </button>
                    </div>

                    <div class="setting-item">
                        <div class="setting-item-info">
                            <div class="setting-icon">📧</div>
                            <div class="setting-text">
                                <h4>Email de connexion</h4>
                                <p>{{ $user->email }}</p>
                            </div>
                        </div>
                        <button class="btn btn-primary" onclick="openModal('emailModal')">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Modifier
                        </button>
                    </div>

                    <div class="setting-item">
                        <div class="setting-item-info">
                            <div class="setting-icon">🔔</div>
                            <div class="setting-text">
                                <h4>Authentification à deux facteurs</h4>
                                <p>Sécurité renforcée (Bientôt disponible)</p>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" disabled>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Zone de danger -->
                <div class="settings-card">
                    <div class="settings-card-header">
                        <div class="settings-card-icon" style="background: #fee2e2;">⚠️</div>
                        <div class="settings-card-title">
                            <h3 style="color: #dc2626;">Zone de danger</h3>
                            <p>Actions irréversibles sur votre compte</p>
                        </div>
                    </div>

                    <div class="setting-item">
                        <div class="setting-item-info">
                            <div class="setting-icon">🗑️</div>
                            <div class="setting-text">
                                <h4>Supprimer mon compte</h4>
                                <p>Supprimer définitivement votre compte et toutes vos données</p>
                            </div>
                        </div>
                        <button class="btn btn-danger" onclick="openModal('deleteModal')">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Supprimer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Stats -->
            <div class="tab-content" id="stats-content">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <div class="settings-card-icon">📊</div>
                        <div class="settings-card-title">
                            <h3>Vos statistiques</h3>
                            <p>Aperçu de votre activité sur la plateforme</p>
                        </div>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-mini">
                            <div class="stat-mini-icon">📄</div>
                            <div class="stat-mini-value">{{ $totalDeclarations }}</div>
                            <div class="stat-mini-label">Total déclarations</div>
                        </div>
                        <div class="stat-mini">
                            <div class="stat-mini-icon">⏳</div>
                            <div class="stat-mini-value">{{ $enAttente }}</div>
                            <div class="stat-mini-label">En attente</div>
                        </div>
                        <div class="stat-mini">
                            <div class="stat-mini-icon">✅</div>
                            <div class="stat-mini-value">{{ $validees }}</div>
                            <div class="stat-mini-label">Validées</div>
                        </div>
                        <div class="stat-mini">
                            <div class="stat-mini-icon">❌</div>
                            <div class="stat-mini-value">{{ $rejetees ?? 0 }}</div>
                            <div class="stat-mini-label">Rejetées</div>
                        </div>
                    </div>

                    <div class="btn-group" style="margin-top: 2rem;">
                        <a href="{{ route('perte.index') }}" class="btn btn-primary">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Voir toutes mes déclarations
                        </a>
                        <a href="{{ route('perte.create') }}" class="btn btn-secondary">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nouvelle déclaration
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Preferences -->
            <div class="tab-content" id="preferences-content">
                <form method="POST" action="{{ route('profile.preferences') }}" id="preferencesForm">
                    @csrf
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <div class="settings-card-icon">🎨</div>
                            <div class="settings-card-title">
                                <h3>Préférences d'affichage</h3>
                                <p>Personnalisez votre expérience</p>
                            </div>
                        </div>

                        <div class="setting-item">
                            <div class="setting-item-info">
                                <div class="setting-icon">🌙</div>
                                <div class="setting-text">
                                    <h4>Mode sombre</h4>
                                    <p>Activer le thème sombre pour réduire la fatigue oculaire</p>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="dark_mode" value="1" {{ session('user_preferences.dark_mode', false) ? 'checked' : '' }} onchange="toggleDarkMode(this)">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="setting-item">
                            <div class="setting-item-info">
                                <div class="setting-icon">🔔</div>
                                <div class="setting-text">
                                    <h4>Notifications email</h4>
                                    <p>Recevoir des notifications par email pour le suivi de vos déclarations</p>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="email_notifications" value="1" {{ session('user_preferences.email_notifications', true) ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="setting-item">
                            <div class="setting-item-info">
                                <div class="setting-icon">📱</div>
                                <div class="setting-text">
                                    <h4>Notifications SMS</h4>
                                    <p>Recevoir des alertes par SMS (disponible prochainement)</p>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="sms_notifications" value="1" {{ session('user_preferences.sms_notifications', false) ? 'checked' : '' }} disabled>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="settings-card">
                        <div class="settings-card-header">
                            <div class="settings-card-icon">🌍</div>
                            <div class="settings-card-title">
                                <h3>Langue et région</h3>
                                <p>Configurez vos préférences linguistiques</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Langue de l'interface</label>
                            <select class="form-input" name="language" id="languageSelect">
                                <option value="fr" {{ session('user_preferences.language', 'fr') == 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                                <option value="en" {{ session('user_preferences.language') == 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Fuseau horaire</label>
                            <select class="form-input" name="timezone">
                                <option value="Africa/Lome" {{ session('user_preferences.timezone', 'Africa/Lome') == 'Africa/Lome' ? 'selected' : '' }}>🇹🇬 Afrique/Lomé (GMT+0)</option>
                            </select>
                        </div>

                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Enregistrer les préférences
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Modal -->
    <div class="modal" id="passwordModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Changer le mot de passe</div>
                <button class="modal-close" onclick="closeModal('passwordModal')">×</button>
            </div>
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Mot de passe actuel</label>
                    <input type="password" class="form-input" name="current_password" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-input" name="password" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" class="form-input" name="password_confirmation" required>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('passwordModal')">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Email Modal -->
    <div class="modal" id="emailModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Modifier l'email</div>
                <button class="modal-close" onclick="closeModal('emailModal')">×</button>
            </div>
            <form method="POST" action="{{ route('profile.email') }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">Email actuel</label>
                    <input type="email" class="form-input" value="{{ $user->email }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Nouvel email</label>
                    <input type="email" class="form-input" name="email" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Mot de passe (pour confirmation)</label>
                    <input type="password" class="form-input" name="password" required>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('emailModal')">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" style="color: #dc2626;">⚠️ Supprimer le compte</div>
                <button class="modal-close" onclick="closeModal('deleteModal')">×</button>
            </div>
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">🗑️</div>
                <h3 style="color: var(--text-color); margin-bottom: 1rem;">Êtes-vous absolument sûr ?</h3>
                <p style="color: #64748b; margin-bottom: 1rem;">
                    Cette action est <strong style="color: #dc2626;">irréversible</strong>. 
                    Toutes vos données personnelles et déclarations seront définitivement supprimées.
                </p>
            </div>
            <form method="POST" action="{{ route('profile.delete') }}">
                @csrf
                @method('DELETE')
                <div class="form-group">
                    <label class="form-label">Confirmez avec votre mot de passe</label>
                    <input type="password" class="form-input" name="password" required placeholder="Entrez votre mot de passe">
                </div>
                <div class="btn-group" style="gap: 1rem;">
                    <button type="submit" class="btn btn-danger" style="flex: 1;" onclick="return confirm('Cette action est irréversible. Confirmer la suppression ?')">
                        Oui, supprimer définitivement
                    </button>
                    <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="closeModal('deleteModal')">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
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
                const darkModeToggle = document.querySelector('input[name="dark_mode"]');
                if (darkModeToggle) {
                    darkModeToggle.checked = true;
                }
            } else {
                document.body.classList.remove('dark-mode');
                const themeIcon = document.querySelector('#themeIcon');
                if (themeIcon) {
                    themeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
                }
                const darkModeToggle = document.querySelector('input[name="dark_mode"]');
                if (darkModeToggle) {
                    darkModeToggle.checked = false;
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

        function toggleDarkMode(checkbox) {
            toggleGlobalDarkMode();
        }

        window.addEventListener('storage', function(e) {
            if (e.key === 'darkMode') {
                applyTheme(e.newValue === 'true');
            }
        });

        window.addEventListener('themeChanged', function(e) {
            const darkModeToggle = document.querySelector('input[name="dark_mode"]');
            if (darkModeToggle) {
                darkModeToggle.checked = e.detail.darkMode;
            }
        });

        // ============================================
        // NOTIFICATIONS
        // ============================================
        function openNotifications() {
            const notifications = [
                @foreach($dernieresDeclarations->take(3) as $declaration)
                {
                    message: 'Déclaration #{{ $declaration->id }} - {{ $declaration->type_piece }}',
                    statut: '{{ $declaration->statut }}',
                    date: '{{ $declaration->created_at->diffForHumans() }}'
                },
                @endforeach
            ];
            
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 80px;
                right: 20px;
                background: ${document.body.classList.contains('dark-mode') ? '#2d2d2d' : 'white'};
                color: ${document.body.classList.contains('dark-mode') ? 'white' : '#1e3a5f'};
                border-radius: 16px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.15);
                padding: 1.5rem;
                min-width: 320px;
                max-width: 400px;
                z-index: 1000;
                animation: slideIn 0.3s ease;
                border: 1px solid ${document.body.classList.contains('dark-mode') ? '#404040' : '#e8eef5'};
            `;

            if (notifications.length === 0) {
                modal.innerHTML = `
                    <div style="text-align: center; padding: 1rem;">
                        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📭</div>
                        <p style="color: ${document.body.classList.contains('dark-mode') ? '#9ca3af' : '#64748b'};">Aucune notification</p>
                    </div>
                `;
            } else {
                modal.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h4 style="color: ${document.body.classList.contains('dark-mode') ? 'white' : '#1e293b'};">Notifications</h4>
                        <button onclick="this.closest('.modal').remove()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: ${document.body.classList.contains('dark-mode') ? '#9ca3af' : '#64748b'};">✕</button>
                    </div>
                    <div style="max-height: 300px; overflow-y: auto;">
                        ${notifications.map(n => `
                            <div style="padding: 0.8rem; border-bottom: 1px solid ${document.body.classList.contains('dark-mode') ? '#404040' : '#f1f5f9'}; display: flex; gap: 0.8rem;">
                                <span style="font-size: 1.2rem;">${n.statut === 'en_attente' ? '⏳' : n.statut === 'validee' ? '✅' : '❌'}</span>
                                <div>
                                    <p style="font-weight: 600; color: ${document.body.classList.contains('dark-mode') ? 'white' : '#1e293b'};">${n.message}</p>
                                    <small style="color: ${document.body.classList.contains('dark-mode') ? '#9ca3af' : '#64748b'};">${n.date}</small>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    <button onclick="window.location.href='{{ route('perte.index') }}'" style="width: 100%; margin-top: 1rem; padding: 0.8rem; background: linear-gradient(135deg, #10b981, #34d399); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Voir toutes les déclarations
                    </button>
                `;
            }

            modal.classList.add('modal');
            document.body.appendChild(modal);

            setTimeout(() => {
                document.addEventListener('click', function closeModal(e) {
                    if (!modal.contains(e.target) && !e.target.closest('.icon-btn')) {
                        modal.remove();
                        document.removeEventListener('click', closeModal);
                    }
                });
            }, 100);
        }

        // ============================================
        // TAB NAVIGATION
        // ============================================
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            document.getElementById(tabName + '-content').classList.add('active');
            event.target.closest('.tab').classList.add('active');
            
            sessionStorage.setItem('activeTab', tabName);
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadTheme();
            
            const activeTab = sessionStorage.getItem('activeTab');
            if (activeTab) {
                const tab = document.querySelector(`.tab[onclick*="${activeTab}"]`);
                if (tab) {
                    tab.click();
                }
            }
        });

        // ============================================
        // MODAL FUNCTIONS
        // ============================================
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.remove('active');
                }
            });
        });

        // ============================================
        // NOTIFICATION (pour le thème)
        // ============================================
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type}`;
            notification.innerHTML = `
                <span>${type === 'success' ? '✅' : 'ℹ️'}</span>
                <span>${message}</span>
            `;
            notification.style.position = 'fixed';
            notification.style.top = '20px';
            notification.style.right = '20px';
            notification.style.zIndex = '9999';
            notification.style.minWidth = '300px';
            notification.style.animation = 'slideDown 0.3s ease-out';
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // ============================================
        // AUTO-SUBMIT POUR LES TOGGLES (autres que dark_mode)
        // ============================================
        document.querySelectorAll('.toggle-switch input[type="checkbox"]').forEach(toggle => {
            toggle.addEventListener('change', function() {
                if (this.name !== 'dark_mode') {
                    document.getElementById('preferencesForm').submit();
                }
            });
        });

        // ============================================
        // CHANGEMENT DE LANGUE
        // ============================================
        document.getElementById('languageSelect')?.addEventListener('change', function() {
            document.getElementById('preferencesForm').submit();
        });

        // ============================================
        // AUTO-HIDE ALERTS
        // ============================================
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>

    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .theme-toggle svg {
            transition: transform 0.5s ease;
        }
        
        .theme-toggle:hover svg {
            transform: rotate(180deg);
        }
        
        body.dark-mode .icon-btn {
            background: #2d2d2d;
            border-color: #404040;
        }
        
        body.dark-mode .icon-btn svg {
            stroke: #9ca3af;
        }
        
        body.dark-mode .icon-btn:hover {
            border-color: var(--primary);
        }
        
        body.dark-mode .icon-btn:hover svg {
            stroke: var(--primary);
        }
    </style>
</body>
</html>