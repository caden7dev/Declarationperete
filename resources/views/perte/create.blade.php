<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle Déclaration - e-Déclaration TG</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            min-height: 100vh;
            display: flex;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-image: url('{{ asset("images/image3.jpeg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            opacity: 0.15;
            z-index: -1;
        }

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

        .sidebar-header span { font-size: 1.8rem; }

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

        .sidebar-nav a:hover { background: #f1f5f9; color: #27ae60; }
        .sidebar-nav a.active { background: #e8f5e9; color: #27ae60; font-weight: 700; }
        .sidebar-nav a svg { width: 20px; height: 20px; }

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

        .btn-logout:hover { background: #ffe8e6; }

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

        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 2rem;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            border-radius: 30px;
            padding: 3rem;
            box-shadow: 0 25px 60px rgba(0,0,0,0.25);
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #27ae60, #2ecc71, #3498db);
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
            border: 1px solid rgba(0,0,0,0.03);
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

        .icon-btn:hover svg { stroke: #27ae60; }
        .theme-toggle svg { transition: transform 0.5s ease; }
        .theme-toggle:hover svg { transform: rotate(180deg); }

        .notification-badge {
            position: absolute;
            top: -5px; right: -5px;
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

        /* ===== ZONE NAVIGATION HAUT (back + btn document trouvé) ===== */
        .top-nav-zone {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #27ae60;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .back-link:hover {
            gap: 1rem;
            color: #1e8449;
        }

        /* ===== BOUTON DOCUMENT TROUVÉ ===== */
        .btn-doc-trouve {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.4rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.3s;
            box-shadow: 0 6px 18px rgba(59, 130, 246, 0.35);
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-doc-trouve svg {
            width: 18px;
            height: 18px;
            stroke: white;
            flex-shrink: 0;
        }

        .btn-doc-trouve:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.5);
            color: white;
        }

        /* Header */
        .form-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .form-header::after {
            content: '';
            display: block;
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #27ae60, #3498db);
            margin: 1.5rem auto 0;
            border-radius: 2px;
        }

        h1 {
            font-size: 2.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #27ae60, #3498db);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
            font-weight: 400;
        }

        .error-box {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            border-left: 5px solid #dc3545;
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);
        }

        .error-box ul { list-style: none; padding: 0; }

        .error-box li {
            color: #721c24;
            font-weight: 500;
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .error-box li::before { content: '⚠️'; }

        .section {
            margin-bottom: 3rem;
            padding: 2rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .section-title {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: #1e3a5f;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .section-number {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .form-group.full { grid-column: span 2; }

        label {
            font-weight: 600;
            font-size: 0.95rem;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        label .required { color: #dc3545; }

        label .auto-filled {
            background: #d4edda;
            color: #155724;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        input, select, textarea {
            width: 100%;
            padding: 1rem 1.2rem;
            border-radius: 12px;
            border: 2px solid #dee2e6;
            font-size: 1rem;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #27ae60;
            box-shadow: 0 0 0 4px rgba(39, 174, 96, 0.1);
        }

        input[readonly] {
            background: #e9ecef;
            cursor: not-allowed;
            color: #6c757d;
        }

        textarea { resize: vertical; min-height: 120px; }

        .helper-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .helper-text svg { width: 14px; height: 14px; }

        input[type="file"] { padding: 0.8rem 1.2rem; cursor: pointer; }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1.5rem;
            background: #fff3cd;
            border-radius: 12px;
            border: 2px solid #ffc107;
            margin-top: 2rem;
        }

        .checkbox-group input[type="checkbox"] { width: 24px; height: 24px; cursor: pointer; }

        .checkbox-group label {
            font-size: 1rem;
            color: #856404;
            margin: 0;
            cursor: pointer;
        }

        .submit-section { text-align: center; margin-top: 3rem; }

        .submit-btn {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 1.3rem 4rem;
            border-radius: 16px;
            border: none;
            font-weight: 800;
            font-size: 1.2rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s;
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.4);
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .submit-btn:hover::before { left: 100%; }

        .submit-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 35px rgba(39, 174, 96, 0.5);
        }

        .submit-btn:active { transform: translateY(-2px); }

        /* Dark mode */
        body.dark-mode .sidebar { background: #2d2d2d; border-color: #404040; }
        body.dark-mode .sidebar-nav a { color: #9ca3af; }
        body.dark-mode .sidebar-nav a:hover { background: #404040; }
        body.dark-mode .container { background: #2d2d2d; color: #e5e7eb; }
        body.dark-mode .section { background: #404040; }
        body.dark-mode input, body.dark-mode select, body.dark-mode textarea {
            background: #4b5563; border-color: #6b7280; color: white;
        }
        body.dark-mode .icon-btn { background: #2d2d2d; border-color: #404040; }
        body.dark-mode .icon-btn svg { stroke: #9ca3af; }
        body.dark-mode .icon-btn:hover { border-color: #27ae60; }
        body.dark-mode .icon-btn:hover svg { stroke: #27ae60; }

        /* Styles pour les erreurs de validation */
        .is-invalid {
            border-color: #dc3545 !important;
        }
        
        .text-danger {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.3rem;
        }

        @media (max-width: 1024px) {
            body { flex-direction: column; padding: 1rem; }
            .sidebar { width: 100%; position: relative; height: auto; margin-bottom: 1rem; }
            .main-content { margin-left: 0; padding: 1rem; }
            .container { padding: 2rem 1.5rem; }
            h1 { font-size: 2rem; }
            .grid { grid-template-columns: 1fr; }
            .form-group.full { grid-column: span 1; }
            .submit-btn { width: 100%; padding: 1.3rem 2rem; }
            .top-nav-zone { flex-direction: column; align-items: flex-start; }
            .btn-doc-trouve { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

    @php
        $unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
    @endphp

    <div class="sidebar">
        <div class="sidebar-header">
            <h2><span>🇹🇬</span> e-Déclaration TG</h2>
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

    <div class="main-content">
        <div class="container">

            <!-- Top Bar Icons -->
            <div class="top-bar-icons">
                <button class="icon-btn theme-toggle" onclick="toggleGlobalDarkMode()" title="Changer le thème">
                    <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>
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

            <!-- ===== ZONE NAVIGATION : Back + Bouton Document Trouvé ===== -->
            <div class="top-nav-zone">
                <a href="{{ route('dashboard') }}" class="back-link">
                    ← Retour au dashboard
                </a>
                <a href="{{ route('documents-trouves.create') }}" class="btn-doc-trouve">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Déclarer un document trouvé
                </a>
            </div>

            <!-- Header -->
            <div class="form-header">
                <h1>📝 Nouvelle Déclaration de Perte</h1>
                <p class="form-subtitle">Veuillez remplir tous les champs requis avec attention</p>
            </div>

            @if ($errors->any())
                <div class="error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('perte.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="section">
                    <div class="section-title">
                        <span class="section-number">1</span>
                        Informations du déclarant
                    </div>
                    <div class="grid">
                        <div class="form-group">
                            <label>Nom <span class="required">*</span> <span class="auto-filled">✓ Auto-rempli</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name', auth()->user()->last_name ?? auth()->user()->name) }}" readonly required>
                            <div class="helper-text"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Pré-rempli depuis votre profil</div>
                        </div>
                        <div class="form-group">
                            <label>Prénom(s) <span class="required">*</span> <span class="auto-filled">✓ Auto-rempli</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name', auth()->user()->first_name ?? auth()->user()->name) }}" readonly required>
                            <div class="helper-text"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Pré-rempli depuis votre profil</div>
                        </div>
                        <div class="form-group">
                            <label>Numéro de téléphone <span class="required">*</span> <span class="auto-filled">✓ Auto-rempli</span></label>
                            <input type="text" name="contact" value="{{ old('contact', auth()->user()->contact ?? auth()->user()->phone) }}" readonly required>
                            <div class="helper-text"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Pré-rempli depuis votre profil</div>
                        </div>
                        <div class="form-group">
                            <label>Adresse e-mail <span class="required">*</span> <span class="auto-filled">✓ Auto-rempli</span></label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" readonly required>
                            <div class="helper-text"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Pré-rempli depuis votre profil</div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">
                        <span class="section-number">2</span>
                        Informations sur la pièce perdue
                    </div>
                    <div class="grid">
                        <!-- ===== SOLUTION 3 : Utilisation de la table type_pieces ===== -->
                        <div class="form-group">
                            <label>Type de pièce <span class="required">*</span></label>
                            <select name="type_piece" required class="form-control @error('type_piece') is-invalid @enderror">
                                <option value="">-- Sélectionner le type de pièce --</option>
                                @foreach($typesPieces as $type)
                                    <option value="{{ $type->nom }}" {{ old('type_piece') == $type->nom ? 'selected' : '' }}>
                                        {{ $type->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_piece')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Numéro de la pièce</label>
                            <input type="text" name="numero_piece" value="{{ old('numero_piece') }}" placeholder="Ex: 123456789" class="@error('numero_piece') is-invalid @enderror">
                            <div class="helper-text">Si vous vous en souvenez</div>
                            @error('numero_piece')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Date de délivrance</label>
                            <input type="date" name="date_delivrance" value="{{ old('date_delivrance') }}" class="@error('date_delivrance') is-invalid @enderror">
                            @error('date_delivrance')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Autorité de délivrance</label>
                            <input type="text" name="autorite_delivrance" value="{{ old('autorite_delivrance') }}" placeholder="Ex: Préfecture de Lomé" class="@error('autorite_delivrance') is-invalid @enderror">
                            @error('autorite_delivrance')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">
                        <span class="section-number">3</span>
                        Détails de la perte
                    </div>
                    <div class="grid">
                        <div class="form-group">
                            <label>Date de la perte <span class="required">*</span></label>
                            <input type="date" name="date_perte" value="{{ old('date_perte') }}" max="{{ date('Y-m-d') }}" required class="@error('date_perte') is-invalid @enderror">
                            <div class="helper-text">Quand avez-vous constaté la perte ?</div>
                            @error('date_perte')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Lieu de la perte <span class="required">*</span></label>
                            <input type="text" name="lieu_perte" value="{{ old('lieu_perte') }}" placeholder="Ex: Marché de Lomé" required class="@error('lieu_perte') is-invalid @enderror">
                            <div class="helper-text">Où était le dernier endroit où vous l'avez vue ?</div>
                            @error('lieu_perte')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group full">
                            <label>Circonstances de la perte</label>
                            <textarea name="circonstances" rows="4" placeholder="Décrivez les circonstances de la perte..." class="@error('circonstances') is-invalid @enderror">{{ old('circonstances') }}</textarea>
                            <div class="helper-text">Plus de détails aideront à traiter votre déclaration</div>
                            @error('circonstances')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">
                        <span class="section-number">4</span>
                        Justificatifs (optionnels)
                    </div>
                    <div class="grid">
                        <div class="form-group">
                            <label>Copie de la pièce (si existante)</label>
                            <input type="file" name="copie_piece" accept=".pdf,.jpg,.jpeg,.png" class="@error('copie_piece') is-invalid @enderror">
                            <div class="helper-text">PDF, JPG ou PNG • Max 2 Mo</div>
                            @error('copie_piece')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Déclaration de vol (police)</label>
                            <input type="file" name="declaration_vol" accept=".pdf,.jpg,.jpeg,.png" class="@error('declaration_vol') is-invalid @enderror">
                            <div class="helper-text">Si vol, joindre le PV de police</div>
                            @error('declaration_vol')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group full">
                            <label>Document complémentaire</label>
                            <input type="file" name="document_complementaire" accept=".pdf,.jpg,.jpeg,.png" class="@error('document_complementaire') is-invalid @enderror">
                            <div class="helper-text">Tout autre document utile</div>
                            @error('document_complementaire')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="certify" required>
                    <label for="certify">
                        Je certifie sur l'honneur l'exactitude des informations fournies et comprends que toute fausse déclaration peut entraîner des poursuites.
                    </label>
                </div>

                <div class="submit-section">
                    <button type="submit" class="submit-btn">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:24px;height:24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Soumettre la déclaration
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir soumettre cette déclaration ?')) {
                e.preventDefault();
            }
        });

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
            const serverTheme = '{{ auth()->user()->theme ?? "light" }}';
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