<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            font-family: 'Inter', sans-serif;
        }

        :root {
            --primary: #10b981;
            --secondary: #3b82f6;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
        }

        body { 
            display: flex; 
            min-height: 100vh; 
            position: relative;
        }

        /* Arrière-plan avec image */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url('{{ asset("images/image3.jpeg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            z-index: -2;
            filter: brightness(0.8);
        }

        /* Overlay sophistiqué */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: 
                linear-gradient(135deg, rgba(16, 185, 129, 0.03) 0%, rgba(59, 130, 246, 0.03) 100%),
                rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(1px);
            z-index: -1;
        }

        /* ===== SIDEBAR PREMIUM ===== */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.95) 100%);
            backdrop-filter: blur(20px);
            box-shadow: 0 0 40px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            border-right: 1px solid rgba(16, 185, 129, 0.1);
        }

        .sidebar-header {
            padding: 2rem 1.8rem;
            background: linear-gradient(135deg, var(--primary), #059669);
            position: relative;
            overflow: hidden;
        }

        .sidebar-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255,255,255,0.15), transparent);
            animation: rotate 15s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .sidebar-header h2 { 
            font-size: 1.4rem;
            font-weight: 800;
            color: white;
            display: flex; 
            align-items: center; 
            gap: 0.8rem;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .sidebar-header span { 
            font-size: 2rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            overflow-y: auto;
        }

        .sidebar-nav a {
            text-decoration: none;
            color: #475569;
            font-weight: 600;
            padding: 0.9rem 1.3rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 0.9rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.95rem;
            position: relative;
        }

        .sidebar-nav a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: var(--primary);
            border-radius: 0 3px 3px 0;
            transition: height 0.25s;
        }

        .sidebar-nav a:hover {
            background: rgba(16, 185, 129, 0.08);
            color: var(--primary);
            transform: translateX(6px);
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.12), rgba(34, 197, 94, 0.08));
            color: var(--primary);
            font-weight: 700;
        }

        .sidebar-nav a.active::before {
            height: 70%;
        }

        .sidebar-nav a svg {
            width: 20px;
            height: 20px;
        }

        .sidebar-footer {
            padding: 1.3rem 1rem;
            border-top: 1px solid rgba(0,0,0,0.06);
        }

        .btn-logout {
            width: 100%;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.35);
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
            gap: 1.2rem;
            margin-bottom: 2rem;
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
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.15);
            border-color: var(--primary);
        }

        .icon-btn svg {
            width: 22px;
            height: 22px;
            stroke: #475569;
            transition: all 0.3s;
        }

        .icon-btn:hover svg {
            stroke: var(--primary);
        }

        /* Notification Badge */
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
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);
            animation: pulse-badge 2s infinite;
        }

        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Theme Toggle Animation */
        .icon-btn.theme-toggle svg {
            transition: transform 0.5s ease;
        }

        .icon-btn.theme-toggle:hover svg {
            transform: rotate(180deg);
        }

        /* Dark mode adjustments for icons */
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

        /* Alert Chic */
        .alert {
            padding: 1.2rem 1.8rem;
            border-radius: 14px;
            margin-bottom: 1.8rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideDown 0.4s ease;
            background: white;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.15);
            border-left: 4px solid var(--primary);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            color: #065f46;
        }

        /* Header Compact et Élégant */
        .dashboard-header {
            background: white;
            padding: 2rem 2.5rem;
            border-radius: 18px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.03);
        }

        .welcome {
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--dark) 0%, var(--primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.4rem;
            letter-spacing: -0.02em;
        }

        .welcome-subtitle {
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
        }

        /* Stats Cards Élégantes */
        .stats { 
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.3rem; 
            margin-bottom: 2rem;
        }

        .stat-card {
            padding: 2rem 1.8rem;
            background: white;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.03);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
        }

        .stat-card.green::before { background: linear-gradient(90deg, #10b981, #34d399); }
        .stat-card.yellow::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .stat-card.blue::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
        .stat-card.red::before { background: linear-gradient(90deg, #ef4444, #f87171); }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        }

        .stat-icon {
            font-size: 2.8rem;
            margin-bottom: 1rem;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.1));
        }

        .stat-card h4 {
            font-size: 0.82rem;
            color: #64748b;
            margin-bottom: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 700;
        }

        .stat-card p { 
            font-size: 2.8rem;
            font-weight: 900;
            color: var(--dark);
        }

        /* Bouton Action Chic */
        .action-section {
            margin-bottom: 2rem;
            text-align: center;
        }

        .btn-new {
            background: linear-gradient(135deg, var(--primary), #34d399);
            color: white;
            padding: 1.3rem 3rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
        }

        .btn-new:hover { 
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
        }

        /* ===== TABLE SECTION RÉDUITE ET CHIC ===== */
        .table-section {
            background: white;
            padding: 1.8rem;
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.03);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1.2rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .table-header h3 {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .table-header svg {
            width: 24px;
            height: 24px;
            color: var(--primary);
        }

        /* Table Compacte et Élégante */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        table th { 
            padding: 0.9rem 1.2rem; 
            text-align: left;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.6px;
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        table th:first-child { border-radius: 8px 0 0 0; }
        table th:last-child { border-radius: 0 8px 0 0; }

        table td {
            padding: 1rem 1.2rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }

        table tbody tr {
            transition: all 0.2s;
            cursor: pointer;
        }

        table tbody tr:hover { 
            background: #f8fafc;
        }

        table tbody tr:last-child td {
            border-bottom: none;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .status-badge.pending {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(251, 191, 36, 0.1));
            color: #92400e;
        }

        .status-badge.validated {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(52, 211, 153, 0.1));
            color: #065f46;
        }

        .status-badge.rejected {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(248, 113, 113, 0.1));
            color: #991b1b;
        }

        .status-dot { 
            width: 8px; 
            height: 8px; 
            border-radius: 50%; 
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .status-pending { background: #f59e0b; }
        .status-validated { background: #10b981; }
        .status-rejected { background: #ef4444; }

        .btn-view {
            background: linear-gradient(135deg, var(--secondary), #60a5fa);
            color: white;
            padding: 0.6rem 1.1rem;
            border: none;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.25);
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.35);
        }

        /* Empty State Compact */
        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
        }

        .empty-state-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            opacity: 0.25;
        }

        .empty-state h4 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.6rem;
        }

        .empty-state p {
            font-size: 0.95rem;
            color: #64748b;
        }

        /* Loading */
        .loading {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .loading.active {
            display: flex;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255,255,255,0.2);
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Scrollbar Chic */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.02);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--primary), #34d399);
            border-radius: 10px;
        }

        /* Dark mode global */
        body.dark-mode {
            background: #1a1a1a;
        }

        body.dark-mode .sidebar {
            background: linear-gradient(180deg, rgba(45, 45, 45, 0.95) 0%, rgba(35, 35, 35, 0.95) 100%);
        }

        body.dark-mode .sidebar-nav a {
            color: #9ca3af;
        }

        body.dark-mode .dashboard-header,
        body.dark-mode .stat-card,
        body.dark-mode .table-section,
        body.dark-mode .alert {
            background: #2d2d2d;
            border-color: #404040;
            color: #e5e7eb;
        }

        body.dark-mode .welcome {
            background: linear-gradient(135deg, #e5e7eb 0%, var(--primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        body.dark-mode .welcome-subtitle,
        body.dark-mode .stat-card h4,
        body.dark-mode .table-header h3 {
            color: #9ca3af;
        }

        body.dark-mode .stat-card p {
            color: #e5e7eb;
        }

        body.dark-mode table th {
            background: #404040;
            color: #9ca3af;
            border-bottom-color: #4b5563;
        }

        body.dark-mode table td {
            border-bottom-color: #404040;
            color: #e5e7eb;
        }

        body.dark-mode table tbody tr:hover {
            background: #404040;
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
                padding: 1.5rem;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .welcome {
                font-size: 1.8rem;
            }

            table {
                font-size: 0.85rem;
            }

            table th, table td {
                padding: 0.8rem;
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

        /* Badge pour les notifications dans la sidebar */
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
    </style>
</head>
<body>
    <!-- Loading -->
    <div class="loading" id="loading">
        <div class="spinner"></div>
    </div>

    <!-- Sidebar (intégrée directement) -->
    @php
        $unreadNotifications = \App\Models\Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();
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

    <!-- Main -->
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

        <!-- Alert -->
        @if(session('success'))
            <div class="alert alert-success">
                <span style="font-size: 1.5rem;">✅</span>
                <span><strong>Succès !</strong> {{ session('success') }}</span>
            </div>
        @endif

        <!-- Header -->
        <div class="dashboard-header">
            <div class="welcome">Bienvenue, {{ $user->name }} 👋</div>
            <div class="welcome-subtitle">Voici un aperçu de vos activités récentes</div>
        </div>

        <!-- Stats -->
        <div class="stats">
            <div class="stat-card green" onclick="window.location.href='{{ route('perte.index') }}'">
                <div class="stat-icon">📄</div>
                <h4>Total Déclarations</h4>
                <p>{{ $totalDeclarations }}</p>
            </div>
            <div class="stat-card yellow" onclick="window.location.href='{{ route('perte.index') }}'">
                <div class="stat-icon">⏳</div>
                <h4>En attente</h4>
                <p>{{ $enAttente }}</p>
            </div>
            <div class="stat-card blue" onclick="window.location.href='{{ route('perte.index') }}'">
                <div class="stat-icon">✅</div>
                <h4>Validées</h4>
                <p>{{ $validees }}</p>
            </div>
            @php
                $rejetees = \App\Models\Perte::where('user_id', auth()->id())->where('statut', 'rejetee')->count();
            @endphp
            @if($rejetees > 0)
            <div class="stat-card red" onclick="window.location.href='{{ route('perte.index') }}'">
                <div class="stat-icon">❌</div>
                <h4>Rejetées</h4>
                <p>{{ $rejetees }}</p>
            </div>
            @endif
        </div>

        <!-- Action Button -->
        <div class="action-section">
            <a href="{{ route('perte.create') }}" class="btn-new">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:22px;height:22px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Déclarer une nouvelle perte
            </a>
        </div>

        <!-- Table Section Compacte -->
        <div class="table-section">
            <div class="table-header">
                <h3>
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Activités récentes
                </h3>
            </div>

            @if($dernieresDeclarations->count() > 0)
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
                            <tr onclick="window.location.href='{{ route('perte.show', $declaration->id) }}'">
                                <td><strong>{{ $declaration->type_piece }}</strong></td>
                                <td>{{ $declaration->created_at->format('d/m/Y') }}</td>
                                <td>{{ $declaration->lieu_perte }}</td>
                                <td>
                                    @if($declaration->statut === 'validee')
                                        <span class="status-badge validated">
                                            <span class="status-dot status-validated"></span>
                                            Validée
                                        </span>
                                    @elseif($declaration->statut === 'rejetee')
                                        <span class="status-badge rejected">
                                            <span class="status-dot status-rejected"></span>
                                            Rejetée
                                        </span>
                                    @else
                                        <span class="status-badge pending">
                                            <span class="status-dot status-pending"></span>
                                            En attente
                                        </span>
                                    @endif
                                </td>
                                <td onclick="event.stopPropagation()">
                                    <a href="{{ route('perte.show', $declaration->id) }}" class="btn-view">
                                        👁️ Voir
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <h4>Aucune déclaration</h4>
                    <p>Cliquez sur le bouton ci-dessus pour créer votre première déclaration</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // ============================================
        // MODE SOMBRE GLOBAL - avec persistance serveur
        // ============================================

        // Appliquer le thème (isDark = true/false)
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
            // Sauvegarde locale pour la session en cours
            localStorage.setItem('darkMode', isDark ? 'dark' : 'light');
        }

        // Charger le thème : priorité au serveur
        function loadTheme() {
            // Récupérer le thème depuis le serveur (injecté par Blade)
            const serverTheme = '{{ $user->theme }}'; // 'dark' ou 'light' (peut être vide)
            // Récupérer le thème depuis le localStorage (fallback)
            const localTheme = localStorage.getItem('darkMode');

            // Utiliser le thème serveur s'il existe, sinon le local, sinon 'light'
            const theme = serverTheme || localTheme || 'light';
            applyTheme(theme === 'dark');
        }

        // Basculer le mode sombre et synchroniser avec le serveur
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
                background: white;
                border-radius: 16px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.15);
                padding: 1.5rem;
                min-width: 320px;
                max-width: 400px;
                z-index: 1000;
                animation: slideIn 0.3s ease;
            `;

            if (notifications.length === 0) {
                modal.innerHTML = `
                    <div style="text-align: center; padding: 1rem;">
                        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📭</div>
                        <p style="color: #64748b;">Aucune notification</p>
                    </div>
                `;
            } else {
                modal.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h4 style="color: #1e293b;">Notifications</h4>
                        <button onclick="this.closest('.modal').remove()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">✕</button>
                    </div>
                    <div style="max-height: 300px; overflow-y: auto;">
                        ${notifications.map(n => `
                            <div style="padding: 0.8rem; border-bottom: 1px solid #f1f5f9; display: flex; gap: 0.8rem;">
                                <span style="font-size: 1.2rem;">${n.statut === 'en_attente' ? '⏳' : n.statut === 'validee' ? '✅' : '❌'}</span>
                                <div>
                                    <p style="font-weight: 600; color: #1e293b;">${n.message}</p>
                                    <small style="color: #64748b;">${n.date}</small>
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

        // Charger le thème au démarrage
        document.addEventListener('DOMContentLoaded', loadTheme);

        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s, transform 0.5s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-15px)';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Hide loading
        window.addEventListener('load', () => {
            document.getElementById('loading').classList.remove('active');
        });

        console.log('✨ Dashboard Chic avec notifications chargé !');
    </script>
</body>
</html>