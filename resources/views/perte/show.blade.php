<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Détails de la Déclaration</title>
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

        /* ===== SIDEBAR ===== */
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

        /* Header */
        .detail-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .detail-header::before {
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

        .btn-back {
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
            display: inline-block;
        }

        .btn-back:hover {
            background: white;
            color: #667eea;
            transform: translateX(-5px);
        }

        .btn-edit-header {
            background: white;
            color: #667eea;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit-header:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        /* Status Badge Large */
        .status-badge-large {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            margin-top: 15px;
        }

        /* Cards */
        .info-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(102, 126, 234, 0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: #667eea;
            font-size: 1.4rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .info-item {
            padding: 15px;
            background: #f8fafc;
            border-radius: 15px;
            transition: all 0.3s;
        }

        .info-item:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            transform: translateX(5px);
        }

        .info-label {
            font-size: 0.85rem;
            color: #7f8c8d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
        }

        /* Motif Rejet */
        .motif-rejet {
            background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #f39c12;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(243, 156, 18, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(243, 156, 18, 0); }
            100% { box-shadow: 0 0 0 0 rgba(243, 156, 18, 0); }
        }

        .motif-rejet h5 {
            color: #856404;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .motif-rejet p {
            color: #856404;
            font-size: 1rem;
            margin: 0;
        }

        /* Documents Grid */
        .documents-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .document-card {
            background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }

        .document-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
        }

        .document-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 15px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .document-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .document-size {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-bottom: 15px;
        }

        .btn-download-card {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-download-card:hover {
            background: #667eea;
            color: white;
        }

        /* Timeline */
        .timeline-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            position: relative;
            padding-left: 35px;
            padding-bottom: 30px;
            border-left: 3px solid #e9ecef;
            margin-left: 15px;
            transition: all 0.3s;
        }

        .timeline-item:last-child {
            border-left-color: transparent;
            padding-bottom: 0;
        }

        .timeline-item:hover {
            border-left-color: #667eea;
        }

        .timeline-dot {
            position: absolute;
            left: -12px;
            top: 0;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: white;
            border: 3px solid #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            transition: all 0.3s;
        }

        .timeline-item:hover .timeline-dot {
            transform: scale(1.2);
            background: #667eea;
            border-color: white;
        }

        .timeline-date {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .timeline-title {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 1.1rem;
        }

        .timeline-subtitle {
            color: #7f8c8d;
            font-size: 0.95rem;
        }

        /* Action Sidebar */
        .action-sidebar {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(102, 126, 234, 0.1);
            position: sticky;
            top: 20px;
        }

        .action-sidebar h5 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .action-sidebar h5 i {
            color: #667eea;
        }

        .btn-action-sidebar {
            display: block;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: left;
            transition: all 0.3s;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-action-sidebar i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .btn-edit-sidebar {
            background: linear-gradient(135deg, #f39c12 0%, #f1c40f 100%);
            color: white;
            box-shadow: 0 10px 20px rgba(243, 156, 18, 0.3);
        }

        .btn-delete-sidebar {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            box-shadow: 0 10px 20px rgba(231, 76, 60, 0.3);
        }

        .btn-new-sidebar {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            box-shadow: 0 10px 20px rgba(39, 174, 96, 0.3);
        }

        .btn-action-sidebar:hover {
            transform: translateY(-3px) translateX(5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .user-info {
            background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 15px;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .user-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .user-email {
            color: #7f8c8d;
            font-size: 0.95rem;
            margin-bottom: 10px;
        }

        .user-phone {
            background: white;
            padding: 8px 15px;
            border-radius: 50px;
            display: inline-block;
            font-weight: 600;
            color: #27ae60;
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
        body.dark-mode .info-card,
        body.dark-mode .timeline-card,
        body.dark-mode .action-sidebar {
            background: #404040;
            color: #e5e7eb;
        }
        body.dark-mode .info-item {
            background: #4b5563;
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

    <!-- ===== SIDEBAR ===== -->
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
        <!-- Top Bar Icons -->
        <div class="top-bar-icons">
            <button class="icon-btn theme-toggle" onclick="toggleGlobalDarkMode()" title="Changer le thème">
                <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>

            <button class="icon-btn" onclick="openNotifications()" title="Voir les notifications" style="position: relative;">
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

        <div class="main-container">
            <!-- Header -->
            <div class="detail-header">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="breadcrumb-custom">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <span class="separator">›</span>
                        <a href="{{ route('perte.index') }}">Mes Déclarations</a>
                        <span class="separator">›</span>
                        <span class="current">Détails #{{ str_pad($perte->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('perte.index') }}" class="btn-back">
                            <i class="bi bi-arrow-left me-2"></i>
                            Retour
                        </a>
                        @if($perte->statut == 'en_attente')
                            <a href="{{ route('perte.edit', $perte->id) }}" class="btn-edit-header">
                                <i class="bi bi-pencil me-2"></i>
                                Modifier
                            </a>
                        @endif
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="header-title">
                            <i class="bi bi-file-text me-2"></i>
                            Déclaration #{{ str_pad($perte->id, 6, '0', STR_PAD_LEFT) }}
                        </h1>
                        <p class="header-subtitle">
                            <i class="bi bi-calendar me-2"></i>
                            Soumise le {{ $perte->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        @php
                            $badgeClass = [
                                'en_attente' => 'bg-warning',
                                'validee' => 'bg-success',
                                'rejetee' => 'bg-danger'
                            ][$perte->statut] ?? 'bg-secondary';
                            
                            $statutLabels = [
                                'en_attente' => 'En attente',
                                'validee' => 'Validée',
                                'rejetee' => 'Rejetée'
                            ];
                        @endphp
                        <span class="status-badge-large">
                            <i class="bi 
                                {{ $perte->statut == 'validee' ? 'bi-check-circle' : '' }}
                                {{ $perte->statut == 'rejetee' ? 'bi-x-circle' : '' }}
                                {{ $perte->statut == 'en_attente' ? 'bi-clock' : '' }}
                            "></i>
                            {{ $statutLabels[$perte->statut] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Motif de rejet -->
            @if($perte->statut == 'rejetee' && $perte->motif_rejet)
                <div class="motif-rejet">
                    <h5><i class="bi bi-exclamation-triangle-fill me-2"></i>Motif du rejet</h5>
                    <p>{{ $perte->motif_rejet }}</p>
                </div>
            @endif

            <div class="row">
                <!-- Colonne principale -->
                <div class="col-lg-8">
                    <!-- Informations générales -->
                    <div class="info-card">
                        <div class="card-title">
                            <i class="bi bi-info-circle"></i>
                            Informations générales
                        </div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Type de pièce</div>
                                <div class="info-value">{{ $perte->typePiece->nom ?? $perte->type_piece }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Numéro de pièce</div>
                                <div class="info-value"><code>{{ $perte->numero_piece ?? 'N/A' }}</code></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Date de délivrance</div>
                                <div class="info-value">{{ $perte->date_delivrance ? $perte->date_delivrance->format('d/m/Y') : 'Non spécifiée' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Autorité de délivrance</div>
                                <div class="info-value">{{ $perte->autorite_delivrance ?? 'Non spécifiée' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Détails de la perte -->
                    <div class="info-card">
                        <div class="card-title">
                            <i class="bi bi-exclamation-triangle"></i>
                            Détails de la perte
                        </div>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Date de la perte</div>
                                <div class="info-value">{{ $perte->date_perte->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $perte->date_perte->diffForHumans() }}</small>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Lieu de la perte</div>
                                <div class="info-value">{{ $perte->lieu_perte ?? 'Non spécifié' }}</div>
                            </div>
                            <div class="info-item" style="grid-column: span 2;">
                                <div class="info-label">Circonstances</div>
                                <div class="info-value">{{ $perte->circonstances ?? 'Non spécifiées' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    @if($perte->document_path || $perte->declaration_vol || $perte->document_complementaire)
                        <div class="info-card">
                            <div class="card-title">
                                <i class="bi bi-paperclip"></i>
                                Documents justificatifs
                            </div>
                            <div class="documents-grid">
                                @if($perte->document_path)
                                    <div class="document-card" onclick="window.open('{{ asset('storage/' . $perte->document_path) }}', '_blank')">
                                        <div class="document-icon">
                                            <i class="bi bi-file-pdf"></i>
                                        </div>
                                        <div class="document-name">Pièce d'identité</div>
                                        <div class="document-size">PDF</div>
                                        <button class="btn-download-card">
                                            <i class="bi bi-download me-2"></i>
                                            Télécharger
                                        </button>
                                    </div>
                                @endif

                                @if($perte->declaration_vol)
                                    <div class="document-card" onclick="window.open('{{ asset('storage/' . $perte->declaration_vol) }}', '_blank')">
                                        <div class="document-icon">
                                            <i class="bi bi-file-text"></i>
                                        </div>
                                        <div class="document-name">Déclaration de vol</div>
                                        <div class="document-size">PDF</div>
                                        <button class="btn-download-card">
                                            <i class="bi bi-download me-2"></i>
                                            Télécharger
                                        </button>
                                    </div>
                                @endif

                                @if($perte->document_complementaire)
                                    <div class="document-card" onclick="window.open('{{ asset('storage/' . $perte->document_complementaire) }}', '_blank')">
                                        <div class="document-icon">
                                            <i class="bi bi-file-earmark"></i>
                                        </div>
                                        <div class="document-name">Document complémentaire</div>
                                        <div class="document-size">PDF</div>
                                        <button class="btn-download-card">
                                            <i class="bi bi-download me-2"></i>
                                            Télécharger
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Timeline -->
                    <div class="timeline-card">
                        <div class="card-title">
                            <i class="bi bi-clock-history"></i>
                            Historique de la déclaration
                        </div>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-date">
                                    <i class="bi bi-calendar me-2"></i>
                                    {{ $perte->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div class="timeline-title">Déclaration soumise</div>
                                <div class="timeline-subtitle">En attente de traitement par un agent</div>
                            </div>
                            
                            @if($perte->statut != 'en_attente')
                                <div class="timeline-item">
                                    <div class="timeline-dot" style="border-color: {{ $perte->statut == 'validee' ? '#27ae60' : '#e74c3c' }};"></div>
                                    <div class="timeline-date">
                                        <i class="bi bi-calendar me-2"></i>
                                        {{ $perte->updated_at->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="timeline-title">
                                        Déclaration {{ $perte->statut == 'validee' ? 'validée' : 'rejetée' }}
                                    </div>
                                    @if($perte->validator)
                                        <div class="timeline-subtitle">
                                            <i class="bi bi-person me-2"></i>
                                            Par {{ $perte->validator->name }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Colonne latérale -->
                <div class="col-lg-4">
                    <!-- Informations du déclarant -->
                    <div class="action-sidebar">
                        <h5>
                            <i class="bi bi-person"></i>
                            Déclarant
                        </h5>
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="user-name">{{ $perte->user->name ?? 'N/A' }}</div>
                            <div class="user-email">
                                <i class="bi bi-envelope me-2"></i>
                                {{ $perte->user->email ?? 'N/A' }}
                            </div>
                            @if($perte->contact)
                                <div class="user-phone">
                                    <i class="bi bi-telephone me-2"></i>
                                    {{ $perte->contact }}
                                </div>
                            @endif
                        </div>

                        <h5 class="mt-4">
                            <i class="bi bi-gear"></i>
                            Actions
                        </h5>
                        
                        @if($perte->statut == 'en_attente')
                            <a href="{{ route('perte.edit', $perte->id) }}" class="btn-action-sidebar btn-edit-sidebar">
                                <i class="bi bi-pencil"></i>
                                Modifier la déclaration
                            </a>
                            
                            <form action="{{ route('perte.destroy', $perte->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette déclaration ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-sidebar btn-delete-sidebar w-100 text-start">
                                    <i class="bi bi-trash"></i>
                                    Supprimer la déclaration
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('perte.create') }}" class="btn-action-sidebar btn-new-sidebar">
                            <i class="bi bi-plus-circle"></i>
                            Nouvelle déclaration
                        </a>

                        @if($perte->document_path)
                            <div class="mt-4 p-3 bg-light rounded-3">
                                <small class="text-muted d-block mb-2">
                                    <i class="bi bi-download me-2"></i>
                                    Télécharger tous les documents
                                </small>
                                <a href="{{ asset('storage/' . $perte->document_path) }}" download class="btn btn-outline-primary w-100">
                                    <i class="bi bi-archive me-2"></i>
                                    Télécharger (ZIP)
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation smooth pour les cards
        document.querySelectorAll('.info-card, .timeline-card, .action-sidebar').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Confirmation de suppression
        document.querySelectorAll('form[onsubmit]').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Êtes-vous sûr de vouloir supprimer cette déclaration ?')) {
                    e.preventDefault();
                }
            });
        });

        // ============================================
        // MODE SOMBRE GLOBAL
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