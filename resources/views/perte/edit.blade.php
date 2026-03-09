<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modifier la Déclaration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ===== STYLES GLOBAUX ===== */
        * {
            font-family: 'Inter', sans-serif;
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

        /* ===== CONTENU PRINCIPAL ===== */
        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 2rem;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Top Bar Icons */
        .top-bar-icons {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            margin-bottom: 1.5rem;
            justify-content: flex-end;
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

        /* Header */
        .edit-header {
            background: linear-gradient(135deg, #f39c12 0%, #f1c40f 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .edit-header::before {
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

        .breadcrumb-custom {
            background: rgba(255, 255, 255, 0.2);
            padding: 12px 20px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 20px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .breadcrumb-custom a {
            color: white;
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .breadcrumb-custom a:hover {
            opacity: 1;
        }

        .breadcrumb-custom .separator {
            margin: 0 8px;
            opacity: 0.5;
        }

        .breadcrumb-custom .current {
            font-weight: 600;
        }

        .header-title {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .header-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .btn-cancel {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            backdrop-filter: blur(5px);
            position: relative;
            z-index: 1;
            text-decoration: none;
        }

        .btn-cancel:hover {
            background: white;
            color: #f39c12;
            transform: translateX(-5px);
        }

        /* Alert */
        .alert-warning-custom {
            background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
            border: none;
            border-radius: 15px;
            padding: 20px 25px;
            margin-bottom: 25px;
            color: #856404;
            border-left: 4px solid #f39c12;
            animation: slideIn 0.3s ease-out;
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

        /* Form Sections */
        .form-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(102, 126, 234, 0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .form-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
        }

        .form-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #f39c12 0%, #f1c40f 100%);
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #f39c12;
            font-size: 1.5rem;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .required::after {
            content: " *";
            color: #e74c3c;
            font-weight: bold;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #f39c12;
            box-shadow: 0 0 0 4px rgba(243, 156, 18, 0.1);
        }

        /* Current File */
        .current-file {
            background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .file-icon {
            font-size: 1.8rem;
            color: #f39c12;
        }

        .file-details {
            font-weight: 600;
            color: #2c3e50;
        }

        .file-size {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-top: 3px;
        }

        .btn-view-file {
            background: white;
            color: #f39c12;
            border: 2px solid #f39c12;
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-view-file:hover {
            background: #f39c12;
            color: white;
        }

        /* File Upload */
        .file-upload-area {
            border: 3px dashed #e9ecef;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }

        .file-upload-area:hover {
            border-color: #f39c12;
            background: rgba(243, 156, 18, 0.05);
        }

        .file-upload-icon {
            font-size: 3rem;
            color: #f39c12;
            margin-bottom: 15px;
        }

        .file-upload-text {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .file-upload-hint {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .file-preview {
            margin-top: 15px;
            padding: 15px;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-radius: 12px;
            color: #155724;
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Info Alert */
        .info-alert {
            background: linear-gradient(135deg, #e7f5ff 0%, #d0ebff 100%);
            border-radius: 12px;
            padding: 15px 20px;
            margin-top: 20px;
            color: #1864ab;
            border-left: 4px solid #339af0;
        }

        /* Submit Buttons */
        .submit-section {
            background: white;
            border-radius: 20px;
            padding: 25px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(102, 126, 234, 0.1);
            margin-top: 30px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #f39c12 0%, #f1c40f 100%);
            color: white;
            border: none;
            padding: 15px 35px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(243, 156, 18, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(243, 156, 18, 0.4);
        }

        .btn-submit i {
            margin-right: 8px;
        }

        .required-note {
            color: #7f8c8d;
            font-size: 0.95rem;
        }

        .required-note i {
            color: #e74c3c;
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
        body.dark-mode .main-container {
            background: #2d2d2d;
            color: #e5e7eb;
        }
        body.dark-mode .form-section {
            background: #404040;
        }
        body.dark-mode .form-control, body.dark-mode .form-select {
            background: #4b5563;
            border-color: #6b7280;
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
        <div class="main-container">
            <!-- Top Bar Icons (Thème + Notifications) -->
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
            <div class="edit-header">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="breadcrumb-custom">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <span class="separator">›</span>
                        <a href="{{ route('perte.index') }}">Mes Déclarations</a>
                        <span class="separator">›</span>
                        <a href="{{ route('perte.show', $perte->id) }}">Détails #{{ str_pad($perte->id, 6, '0', STR_PAD_LEFT) }}</a>
                        <span class="separator">›</span>
                        <span class="current">Modifier</span>
                    </div>
                    <a href="{{ route('perte.show', $perte->id) }}" class="btn-cancel">
                        <i class="bi bi-x-lg me-2"></i>
                        Annuler
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <h1 class="header-title">
                            <i class="bi bi-pencil-square me-2"></i>
                            Modifier la Déclaration
                        </h1>
                        <p class="header-subtitle">
                            <i class="bi bi-file-text me-2"></i>
                            Déclaration #{{ str_pad($perte->id, 6, '0', STR_PAD_LEFT) }} - 
                            Soumise le {{ $perte->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Alert -->
            <div class="alert-warning-custom">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                    <div>
                        <strong>Attention !</strong> Vous modifiez une déclaration en attente. Toute modification sera soumise à une nouvelle validation par un agent.
                    </div>
                </div>
            </div>

            <!-- Formulaire -->
            <form action="{{ route('perte.update', $perte->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Section 1: Informations personnelles -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="bi bi-person-badge"></i>
                        Informations personnelles
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="last_name" class="form-label required">Nom</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" 
                                   value="{{ old('last_name', $perte->last_name) }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="first_name" class="form-label required">Prénom(s)</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" 
                                   value="{{ old('first_name', $perte->first_name) }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="contact" class="form-label">Contact téléphonique</label>
                            <input type="tel" class="form-control @error('contact') is-invalid @enderror" 
                                   id="contact" name="contact" 
                                   value="{{ old('contact', $perte->contact) }}"
                                   placeholder="90 00 00 00">
                            @error('contact')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="email" class="form-label required">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" 
                                   value="{{ old('email', $perte->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Informations sur la pièce -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="bi bi-card-text"></i>
                        Informations sur la pièce d'identité
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="type_piece" class="form-label required">Type de pièce</label>
                            <select class="form-select @error('type_piece') is-invalid @enderror" 
                                    id="type_piece" name="type_piece" required>
                                <option value="">Sélectionnez le type de pièce</option>
                                @forelse($typesPieces as $type)
                                    <option value="{{ $type->id }}" 
                                        {{ old('type_piece', $perte->type_piece) == $type->id ? 'selected' : '' }}>
                                        {{ $type->nom }}
                                    </option>
                                @empty
                                    <option value="" disabled>Aucun type de pièce disponible</option>
                                @endforelse
                            </select>
                            @error('type_piece')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="numero_piece" class="form-label">Numéro de la pièce</label>
                            <input type="text" class="form-control @error('numero_piece') is-invalid @enderror" 
                                   id="numero_piece" name="numero_piece" 
                                   value="{{ old('numero_piece', $perte->numero_piece) }}">
                            @error('numero_piece')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="date_delivrance" class="form-label">Date de délivrance</label>
                            <input type="date" class="form-control @error('date_delivrance') is-invalid @enderror" 
                                   id="date_delivrance" name="date_delivrance" 
                                   value="{{ old('date_delivrance', $perte->date_delivrance ? $perte->date_delivrance->format('Y-m-d') : '') }}">
                            @error('date_delivrance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="autorite_delivrance" class="form-label">Autorité de délivrance</label>
                            <input type="text" class="form-control @error('autorite_delivrance') is-invalid @enderror" 
                                   id="autorite_delivrance" name="autorite_delivrance" 
                                   value="{{ old('autorite_delivrance', $perte->autorite_delivrance) }}">
                            @error('autorite_delivrance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Informations sur la perte -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="bi bi-exclamation-triangle"></i>
                        Circonstances de la perte
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="date_perte" class="form-label required">Date de la perte</label>
                            <input type="date" class="form-control @error('date_perte') is-invalid @enderror" 
                                   id="date_perte" name="date_perte" 
                                   value="{{ old('date_perte', $perte->date_perte->format('Y-m-d')) }}" required>
                            @error('date_perte')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="lieu_perte" class="form-label required">Lieu de la perte</label>
                            <input type="text" class="form-control @error('lieu_perte') is-invalid @enderror" 
                                   id="lieu_perte" name="lieu_perte" 
                                   value="{{ old('lieu_perte', $perte->lieu_perte) }}" required>
                            @error('lieu_perte')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 form-group">
                            <label for="circonstances" class="form-label">Circonstances détaillées</label>
                            <textarea class="form-control @error('circonstances') is-invalid @enderror" 
                                      id="circonstances" name="circonstances" rows="4">{{ old('circonstances', $perte->circonstances) }}</textarea>
                            <small class="text-muted">Décrivez les circonstances de la perte (lieu précis, comment c'est arrivé, etc.)</small>
                            @error('circonstances')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 4: Documents -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="bi bi-paperclip"></i>
                        Documents justificatifs
                    </div>

                    <div class="row">
                        <!-- Copie de la pièce -->
                        <div class="col-md-4 mb-4">
                            <label class="form-label">Copie de la pièce d'identité</label>
                            
                            @if($perte->document_path)
                                <div class="current-file">
                                    <div class="file-info">
                                        <div class="file-icon">
                                            <i class="bi bi-file-pdf"></i>
                                        </div>
                                        <div class="file-details">
                                            <div>Document actuel</div>
                                            <div class="file-size">PDF • 2.3 MB</div>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $perte->document_path) }}" target="_blank" class="btn-view-file">
                                        <i class="bi bi-eye"></i> Voir
                                    </a>
                                </div>
                            @endif

                            <div class="file-upload-area" onclick="document.getElementById('document_path').click()">
                                <input type="file" id="document_path" name="document_path" 
                                       style="display: none;" accept=".pdf,.jpg,.jpeg,.png"
                                       onchange="previewFile(this, 'filePreview1')">
                                <div class="file-upload-icon">
                                    <i class="bi bi-cloud-upload"></i>
                                </div>
                                <div class="file-upload-text">
                                    Cliquez pour télécharger un fichier
                                </div>
                                <div class="file-upload-hint">
                                    ou glissez-déposez
                                </div>
                            </div>
                            <div id="filePreview1" class="file-preview" style="display: none;"></div>
                            <small class="text-muted d-block mt-2">Format accepté: PDF, JPG, PNG. Max: 2 Mo</small>
                            @error('document_path')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Déclaration de vol -->
                        <div class="col-md-4 mb-4">
                            <label class="form-label">Déclaration de vol/perte</label>
                            
                            @if($perte->declaration_vol)
                                <div class="current-file">
                                    <div class="file-info">
                                        <div class="file-icon">
                                            <i class="bi bi-file-text"></i>
                                        </div>
                                        <div class="file-details">
                                            <div>Document actuel</div>
                                            <div class="file-size">PDF • 1.8 MB</div>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $perte->declaration_vol) }}" target="_blank" class="btn-view-file">
                                        <i class="bi bi-eye"></i> Voir
                                    </a>
                                </div>
                            @endif

                            <div class="file-upload-area" onclick="document.getElementById('declaration_vol').click()">
                                <input type="file" id="declaration_vol" name="declaration_vol" 
                                       style="display: none;" accept=".pdf,.jpg,.jpeg,.png"
                                       onchange="previewFile(this, 'filePreview2')">
                                <div class="file-upload-icon">
                                    <i class="bi bi-cloud-upload"></i>
                                </div>
                                <div class="file-upload-text">
                                    Cliquez pour télécharger un fichier
                                </div>
                                <div class="file-upload-hint">
                                    ou glissez-déposez
                                </div>
                            </div>
                            <div id="filePreview2" class="file-preview" style="display: none;"></div>
                            <small class="text-muted d-block mt-2">Format accepté: PDF, JPG, PNG. Max: 2 Mo</small>
                            @error('declaration_vol')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Document complémentaire -->
                        <div class="col-md-4 mb-4">
                            <label class="form-label">Document complémentaire</label>
                            
                            @if($perte->document_complementaire)
                                <div class="current-file">
                                    <div class="file-info">
                                        <div class="file-icon">
                                            <i class="bi bi-file-earmark"></i>
                                        </div>
                                        <div class="file-details">
                                            <div>Document actuel</div>
                                            <div class="file-size">PDF • 1.5 MB</div>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $perte->document_complementaire) }}" target="_blank" class="btn-view-file">
                                        <i class="bi bi-eye"></i> Voir
                                    </a>
                                </div>
                            @endif

                            <div class="file-upload-area" onclick="document.getElementById('document_complementaire').click()">
                                <input type="file" id="document_complementaire" name="document_complementaire" 
                                       style="display: none;" accept=".pdf,.jpg,.jpeg,.png"
                                       onchange="previewFile(this, 'filePreview3')">
                                <div class="file-upload-icon">
                                    <i class="bi bi-cloud-upload"></i>
                                </div>
                                <div class="file-upload-text">
                                    Cliquez pour télécharger un fichier
                                </div>
                                <div class="file-upload-hint">
                                    ou glissez-déposez
                                </div>
                            </div>
                            <div id="filePreview3" class="file-preview" style="display: none;"></div>
                            <small class="text-muted d-block mt-2">Format accepté: PDF, JPG, PNG. Max: 2 Mo</small>
                            @error('document_complementaire')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="info-alert">
                        <i class="bi bi-info-circle me-2"></i>
                        Laissez vide pour conserver les documents actuels. Un nouveau document remplacera l'ancien.
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="submit-section d-flex justify-content-between align-items-center">
                    <div class="required-note">
                        <i class="bi bi-asterisk text-danger me-1"></i>
                        Les champs marqués d'un astérisque sont obligatoires
                    </div>
                    <div>
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-check-circle"></i>
                            Mettre à jour la déclaration
                        </button>
                        <a href="{{ route('perte.show', $perte->id) }}" class="btn btn-outline-secondary btn-lg ms-3">
                            <i class="bi bi-x-lg"></i>
                            Annuler
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview des fichiers sélectionnés
        function previewFile(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileName = file.name;
                const fileSize = (file.size / 1024).toFixed(2);
                preview.style.display = 'block';
                preview.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2" style="color: #28a745;"></i>
                        <div>
                            <strong>Fichier sélectionné:</strong> ${fileName} (${fileSize} Ko)
                        </div>
                    </div>
                `;
            }
        }

        // Animation des sections
        document.querySelectorAll('.form-section').forEach(section => {
            section.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            section.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Validation du formulaire
        (function() {
            'use strict';
            var forms = document.querySelectorAll('form');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Glisser-déposer pour les zones de fichier
        document.querySelectorAll('.file-upload-area').forEach(area => {
            area.addEventListener('dragover', (e) => {
                e.preventDefault();
                area.style.borderColor = '#f39c12';
                area.style.background = 'rgba(243, 156, 18, 0.1)';
            });
            area.addEventListener('dragleave', (e) => {
                e.preventDefault();
                area.style.borderColor = '#e9ecef';
                area.style.background = 'transparent';
            });
            area.addEventListener('drop', (e) => {
                e.preventDefault();
                area.style.borderColor = '#e9ecef';
                area.style.background = 'transparent';
                const files = e.dataTransfer.files;
                const input = area.querySelector('input[type="file"]');
                if (input && files.length > 0) {
                    input.files = files;
                    const event = new Event('change', { bubbles: true });
                    input.dispatchEvent(event);
                    const previewId = input.id === 'document_path' ? 'filePreview1' :
                                     input.id === 'declaration_vol' ? 'filePreview2' : 'filePreview3';
                    previewFile(input, previewId);
                }
            });
        });

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