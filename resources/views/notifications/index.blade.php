<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mes Notifications - e-Déclaration TG</title>
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

        .main {
            margin-left: 280px;
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

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

        .notifications-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 2rem;
            border-radius: 16px;
            color: white;
            margin-bottom: 2rem;
        }

        .notifications-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .notifications-header p {
            opacity: 0.9;
        }

        .notifications-actions {
            margin-bottom: 1.5rem;
            text-align: right;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 10px;
            font-size: 0.8rem;
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

        .notifications-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .notification-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.2rem;
            background: white;
            border-radius: 16px;
            border: 1px solid var(--gray-200);
            transition: all 0.2s;
            cursor: pointer;
        }

        body.dark-mode .notification-card {
            background: #404048;
            border-color: #4b5563;
        }

        .notification-card.unread {
            background: #f0fdf4;
            border-left: 4px solid var(--primary);
        }

        body.dark-mode .notification-card.unread {
            background: #1a3b2a;
        }

        .notification-card.read {
            opacity: 0.8;
        }

        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            border-color: var(--primary);
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .notification-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark);
        }

        body.dark-mode .notification-header h3 {
            color: #e5e7eb;
        }

        .notification-date {
            font-size: 0.7rem;
            color: var(--gray-600);
        }

        .notification-content p {
            font-size: 0.85rem;
            color: var(--gray-600);
            margin-bottom: 0.5rem;
        }

        .notification-reference,
        .notification-sender {
            font-size: 0.7rem;
            background: var(--gray-100);
            display: inline-block;
            padding: 0.2rem 0.7rem;
            border-radius: 20px;
            margin-right: 0.5rem;
            margin-top: 0.3rem;
            color: var(--primary);
        }

        body.dark-mode .notification-reference,
        body.dark-mode .notification-sender {
            background: #4b5563;
        }

        .notification-actions {
            display: flex;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .btn-read,
        .btn-delete {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-read {
            background: rgba(16, 185, 129, 0.1);
            color: var(--primary);
        }

        .btn-read:hover {
            background: var(--primary);
            color: white;
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .btn-delete:hover {
            background: var(--danger);
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

        .empty-state h3 {
            font-size: 1.2rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        body.dark-mode .empty-state h3 {
            color: #e5e7eb;
        }

        .empty-state p {
            color: var(--gray-600);
        }

        .pagination {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        .alert {
            padding: 1rem 1.2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            background: white;
            border-left: 4px solid var(--primary);
        }

        body.dark-mode .alert {
            background: #2d2d35;
            color: #e5e7eb;
        }

        .alert-success { color: #065f46; }
        body.dark-mode .alert-success { color: #a7f3d0; }

        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--primary);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-download:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        body.dark-mode .btn-download {
            background: #059669;
        }
        body.dark-mode .btn-download:hover {
            background: #047857;
        }

        .btn-outline-primary {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
            padding: 0.2rem 0.7rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }
        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
        }
        body.dark-mode .btn-outline-primary {
            border-color: #34d399;
            color: #34d399;
        }
        body.dark-mode .btn-outline-primary:hover {
            background: #34d399;
            color: #0f172a;
        }

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
            .notification-card {
                flex-direction: column;
                align-items: flex-start;
            }
            .notification-actions {
                align-self: flex-end;
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
        <a href="{{ route('perte.create') }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
            Nouvelle Déclaration
        </a>
        <a href="{{ route('citoyen.messages') }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            Messages
        </a>
        <a href="{{ route('notifications.index') }}" class="active">
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
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
        <div class="notifications-header">
            <h1>🔔 Mes Notifications</h1>
            <p>Restez informé des actions sur vos déclarations</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif

        <div class="notifications-actions">
            <button onclick="markAllAsRead()" class="btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
                Tout marquer comme lu
            </button>
        </div>

        <div class="notifications-list">
            @forelse($notifications as $notification)
                @php
                    // Vérifier si c'est une notification avec un lien de téléchargement
                    $isDownload = $notification->action_url && 
                        (str_contains($notification->action_url, 'download-recu') || 
                         str_contains($notification->title, 'Récépissé'));
                    
                    // === REMPLACEMENT DE match() PHP 8 par conditions PHP 7 ===
                    $colorClass = 'info';
                    if ($notification->color == 'success') $colorClass = 'success';
                    elseif ($notification->color == 'warning') $colorClass = 'warning';
                    elseif ($notification->color == 'danger') $colorClass = 'danger';
                    
                    $typeLabel = ucfirst($notification->type);
                    if ($notification->type == 'validation') $typeLabel = 'Validation';
                    elseif ($notification->type == 'rejet') $typeLabel = 'Rejet';
                    elseif ($notification->type == 'info') $typeLabel = 'Info';
                    elseif ($notification->type == 'message') $typeLabel = 'Message';
                    elseif ($notification->type == 'system') $typeLabel = 'Système';
                    elseif ($notification->type == 'document_retrouve') $typeLabel = 'Document retrouvé';
                @endphp
                <div class="notification-card {{ $notification->is_read ? 'read' : 'unread' }}" 
                     @if(!$isDownload && $notification->action_url) onclick="window.location.href='{{ $notification->action_url }}'" @endif>
                    <div class="notification-icon" style="background: {{ $notification->color === 'success' ? '#10b98120' : ($notification->color === 'danger' ? '#ef444420' : '#3b82f620') }}">
                        <span>{{ $notification->icon ?? '🔔' }}</span>
                    </div>
                    <div class="notification-content">
                        <div class="notification-header">
                            <h3>{{ $notification->title }}</h3>
                            <span class="notification-date">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p>{{ $notification->content }}</p>
                        @if($notification->perte)
                            <div class="notification-reference">
                                Déclaration #{{ $notification->perte->id }} - {{ $notification->perte->type_piece }}
                            </div>
                        @endif
                        @if($notification->fromUser)
                            <div class="notification-sender">
                                De: {{ $notification->fromUser->name }} (Agent)
                            </div>
                        @endif
                        @if($notification->action_url)
                            @if($isDownload)
                                <div style="margin-top: 0.5rem;">
                                    <a href="{{ $notification->action_url }}" class="btn-download" target="_blank">
                                        <i class="bi bi-download"></i> Télécharger le récépissé
                                    </a>
                                </div>
                            @else
                                <div style="margin-top: 0.5rem;">
                                    <a href="{{ $notification->action_url }}" class="btn-outline-primary">
                                        Voir le détail
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="notification-actions" onclick="event.stopPropagation()">
                        @if(!$notification->is_read)
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-read" title="Marquer comme lu">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer cette notification ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Supprimer">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h3>Aucune notification</h3>
                    <p>Vous n'avez pas encore de notifications. Les actions des agents apparaîtront ici.</p>
                </div>
            @endforelse
        </div>

        <div class="pagination">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

<script>
    function markAllAsRead() {
        fetch('{{ route("notifications.mark-all-read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(() => {
            window.location.reload();
        })
        .catch(error => {
            console.error('Erreur:', error);
            window.location.reload();
        });
    }

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
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ dark_mode: isDark })
        }).catch(console.error);
    }

    document.addEventListener('DOMContentLoaded', loadTheme);
</script>
</body>
</html>