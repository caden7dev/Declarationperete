<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Notifications Agent - e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('darkMode');
                const isDark = savedTheme === 'dark';
                if (isDark) {
                    document.documentElement.style.backgroundColor = '#0f172a';
                    document.body.style.backgroundColor = '#0f172a';
                }
            } catch(e) {}
        })();
    </script>
    
    <style>
        /* ===== STYLES (reprenant le même design que la sidebar agent) ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #f39c12;
            --primary-dark: #e67e22;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-600: #64748b;
            --dark: #0f172a;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            transition: background 0.2s ease;
        }
        body.dark-mode { background: #0f172a; }

        /* Sidebar (identique au dashboard agent) */
        .sidebar {
            width: 280px;
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            border-right: 1px solid rgba(243,156,18,0.15);
            box-shadow: 2px 0 20px rgba(0,0,0,0.05);
            transition: background 0.2s,border-color 0.2s;
        }
        body.dark-mode .sidebar {
            background: rgba(20,20,30,0.98);
            border-right-color: rgba(243,156,18,0.3);
        }
        .sidebar-header {
            padding: 2rem 1.5rem 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }
        body.dark-mode .sidebar-header { border-bottom-color: #334155; }
        .sidebar-header h2 {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        body.dark-mode .sidebar-header h2 { color: #e5e7eb; }
        .flag-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 28px;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }
        .flag-icon svg { width: 100%; height: 100%; }
        .republic-text {
            font-size: 0.65rem;
            color: var(--gray-600);
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-top: 0.3rem;
            margin-left: 0.5rem;
        }
        body.dark-mode .republic-text { color: #94a3b8; }
        .agent-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: linear-gradient(135deg,var(--primary),var(--primary-dark));
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            margin-top: 0.5rem;
            text-transform: uppercase;
        }
        .nav-section {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray-600);
            padding: 1rem 1.5rem 0.5rem 1.5rem;
        }
        body.dark-mode .nav-section { color: #64748b; }
        .sidebar-nav {
            flex: 1;
            padding: 0.5rem 0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .sidebar-nav a {
            text-decoration: none;
            color: var(--gray-600);
            font-weight: 500;
            padding: 0.7rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.2s;
            font-size: 0.9rem;
            border-radius: 0 12px 12px 0;
            position: relative;
        }
        body.dark-mode .sidebar-nav a { color: #9ca3af; }
        .sidebar-nav a i { width: 20px; font-size: 1.1rem; }
        .sidebar-nav a:hover {
            background: rgba(243,156,18,0.08);
            color: var(--primary);
        }
        body.dark-mode .sidebar-nav a:hover { background: rgba(243,156,18,0.2); }
        .sidebar-nav a.active {
            background: linear-gradient(135deg,rgba(243,156,18,0.12),rgba(241,196,15,0.08));
            color: var(--primary);
            font-weight: 600;
            border-right: 3px solid var(--primary);
        }
        .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            min-width: 22px;
            height: 22px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
        }
        .sidebar-footer {
            padding: 0.8rem 1rem;
            border-top: 1px solid var(--gray-200);
        }
        body.dark-mode .sidebar-footer { border-top-color: #334155; }
        .logout-link {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            color: var(--danger);
            font-weight: 950;
            background: none;
            border: none;
            width: 100%;
            cursor: pointer;
            padding: 0.4rem 0;
        }
        .logout-link svg { width: 16px; height: 16px; }
        .logout-link:hover { opacity: 0.8; transform: translateX(3px); }

        /* Main content */
        .main {
            margin-left: 280px;
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }
        .top-bar {
            background: white;
            border-radius: 20px;
            padding: 1.2rem 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            transition: background 0.2s,border-color 0.2s;
        }
        body.dark-mode .top-bar {
            background: #1e293b;
            border-color: #334155;
        }
        .top-bar-left h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.2rem;
        }
        body.dark-mode .top-bar-left h1 { color: #f1f5f9; }
        .top-bar-left p {
            color: var(--gray-600);
            font-size: 0.85rem;
        }
        body.dark-mode .top-bar-left p { color: #94a3b8; }
        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .date-time {
            font-size: 0.85rem;
            color: var(--gray-600);
            background: var(--gray-100);
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-weight: 500;
        }
        body.dark-mode .date-time {
            background: #334155;
            color: #94a3b8;
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
            width: 38px;
            height: 38px;
        }
        body.dark-mode .icon-btn {
            background: #1e293b;
            border-color: #4b5563;
        }
        .icon-btn svg {
            width: 18px;
            height: 18px;
            stroke: var(--gray-600);
        }
        body.dark-mode .icon-btn svg { stroke: #9ca3af; }
        .icon-btn:hover {
            border-color: var(--primary);
            background: rgba(243,156,18,0.08);
        }
        .icon-btn:hover svg { stroke: var(--primary); }
        .notification-btn { position: relative; }
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

        /* Notifications */
        .main-container {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            border: 1px solid var(--gray-200);
            transition: background 0.3s, border-color 0.3s;
        }
        body.dark-mode .main-container {
            background: #1e293b;
            border-color: #334155;
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
            box-shadow: 0 6px 20px rgba(243,156,18,0.4);
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
            background: #2d2d35;
            border-color: #4b5563;
        }
        .notification-card.unread {
            background: #fef9e7;
            border-left: 4px solid var(--primary);
        }
        body.dark-mode .notification-card.unread {
            background: #3d2d0b;
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
        body.dark-mode .notification-header h3 { color: #e5e7eb; }
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
        .btn-read, .btn-delete {
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
            background: rgba(243,156,18,0.1);
            color: var(--primary);
        }
        .btn-read:hover {
            background: var(--primary);
            color: white;
        }
        .btn-delete {
            background: rgba(239,68,68,0.1);
            color: #ef4444;
        }
        .btn-delete:hover {
            background: #ef4444;
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
        body.dark-mode .empty-state h3 { color: #e5e7eb; }
        .empty-state p { color: var(--gray-600); }
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
            border-color: #fbbf24;
            color: #fbbf24;
        }
        body.dark-mode .btn-outline-primary:hover {
            background: #fbbf24;
            color: #0f172a;
        }

        @media (max-width: 1024px) {
            .sidebar { width: 100%; position: relative; height: auto; }
            .main { margin-left: 0; }
            .notification-card { flex-direction: column; align-items: flex-start; }
            .notification-actions { align-self: flex-end; }
        }
    </style>
</head>
<body>

@php
    use App\Models\Notification;
    $user = auth()->user();
    $unreadNotificationsCount = Notification::where('user_id', $user->id)->where('type', 'system')->where('is_read', false)->count();
@endphp

<!-- Sidebar (identique au dashboard agent) -->
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
        <div class="republic-text">RÉPUBLIQUE TOGOLAISE</div>
        <div class="agent-badge">
            <i class="bi bi-shield-check"></i> AGENT
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">PRINCIPAL</div>
        <a href="{{ route('agent.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('agent.dashboard', ['statut' => 'en_attente']) }}">
            <i class="bi bi-hourglass-split"></i> En attente
        </a>
        <a href="{{ route('agent.dashboard') }}">
            <i class="bi bi-files"></i> Toutes les pertes
        </a>

        <div class="nav-section">DOCUMENTS</div>
        <a href="{{ route('agent.documents-trouves.index') }}">
            <i class="bi bi-search-heart"></i> Documents trouvés
        </a>

        <div class="nav-section">COMMUNICATION</div>
        <a href="{{ route('agent.messages') }}">
            <i class="bi bi-chat-dots"></i> Messages
        </a>
        <a href="{{ route('agent.notifications') }}" class="active">
            <i class="bi bi-bell"></i> Notifications
            @if($unreadNotificationsCount > 0)
                <span class="nav-badge">{{ $unreadNotificationsCount }}</span>
            @endif
        </a>

        <div class="nav-section">PARAMÈTRES</div>
        <a href="{{ route('agent.profile') }}">
            <i class="bi bi-person-gear"></i> Paramètres
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
    <div class="top-bar">
        <div class="top-bar-left">
            <h1><i class="bi bi-bell me-2" style="color: var(--primary);"></i>Notifications</h1>
            <p>Consultez les alertes et actions sur les déclarations</p>
        </div>
        <div class="top-bar-right">
            <div class="date-time" id="currentDateTime">
                {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY - HH:mm') }}
            </div>
            <button class="icon-btn theme-toggle" id="themeToggleBtn" title="Changer le thème">
                <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>
            <div class="icon-btn notification-btn" onclick="window.location.href='{{ route('agent.notifications') }}'" title="Notifications">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if($unreadNotificationsCount > 0)
                    <span class="notification-badge">{{ $unreadNotificationsCount }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="notifications-header">
            <h1>🔔 Mes Notifications (Agent)</h1>
            <p>Suivez les actions et alertes concernant les déclarations</p>
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
                <div class="notification-card {{ $notification->is_read ? 'read' : 'unread' }}">
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
                            <div style="margin-top: 0.5rem;">
                                <a href="{{ $notification->action_url }}" class="btn-outline-primary">
                                    Voir la déclaration
                                </a>
                            </div>
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
                    <p>Vous n'avez pas encore de notifications. Les actions des citoyens et les alertes système apparaîtront ici.</p>
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(() => {
            window.location.reload();
        });
    }

    // ===== MODE SOMBRE =====
    function applyTheme(isDark) {
        if (isDark) {
            document.body.classList.add('dark-mode');
            const icon = document.querySelector('#themeIcon');
            if (icon) icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
        } else {
            document.body.classList.remove('dark-mode');
            const icon = document.querySelector('#themeIcon');
            if (icon) icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
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
        }).catch(() => console.log('Mode sombre sauvegardé localement'));
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadTheme();
        const themeBtn = document.getElementById('themeToggleBtn');
        if (themeBtn) themeBtn.addEventListener('click', toggleGlobalDarkMode);
    });

    // Horloge
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        const formatted = now.toLocaleDateString('fr-FR', options).replace(',', ' -');
        const dateTimeEl = document.getElementById('currentDateTime');
        if (dateTimeEl) dateTimeEl.innerHTML = formatted;
    }
    updateDateTime();
    setInterval(updateDateTime, 60000);
</script>
</body>
</html>