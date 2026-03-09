<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mes Notifications - e-Déclaration TG</title>
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
            max-width: 1200px;
            margin: 0 auto;
        }

        .notifications-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 2rem;
            border-radius: 16px;
            color: white;
            margin-bottom: 2rem;
        }

        .notifications-header h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .notifications-actions {
            margin-bottom: 1.5rem;
            text-align: right;
        }

        .notifications-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .notification-card {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 2px solid transparent;
            transition: all 0.3s;
            cursor: pointer;
        }

        .notification-card.unread {
            background: #f0f9ff;
            border-left: 4px solid #3b82f6;
        }

        .notification-card.read {
            opacity: 0.8;
        }

        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            border-color: #667eea;
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .notification-content {
            flex: 1;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .notification-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e3a5f;
        }

        .notification-date {
            font-size: 0.85rem;
            color: #64748b;
        }

        .notification-content p {
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .notification-reference,
        .notification-sender {
            font-size: 0.85rem;
            color: #667eea;
            background: #f1f5f9;
            display: inline-block;
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            margin-right: 0.5rem;
        }

        .notification-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-read,
        .btn-delete {
            width: 35px;
            height: 35px;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-read {
            background: #e8f5e9;
            color: #10b981;
        }

        .btn-read:hover {
            background: #10b981;
            color: white;
        }

        .btn-delete {
            background: #ffebee;
            color: #ef4444;
        }

        .btn-delete:hover {
            background: #ef4444;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .empty-state h3 {
            font-size: 1.3rem;
            color: #1e3a5f;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #64748b;
        }

        .pagination {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

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

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

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
        body.dark-mode .notification-card {
            background: #404040;
        }
        body.dark-mode .notification-header h3 {
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
        <!-- Top Bar Icons (Thème + Notifications) -->
        <div class="top-bar-icons">
            <!-- Bouton Mode Sombre -->
            <button class="icon-btn theme-toggle" onclick="toggleGlobalDarkMode()" title="Changer le thème">
                <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>

            <!-- Bouton Notifications (redirige vers cette page) -->
            <button class="icon-btn" onclick="window.location.href='{{ route('notifications.index') }}'" title="Voir les notifications">
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
            <div class="notifications-header">
                <h1>🔔 Mes Notifications</h1>
                <p>Restez informé des actions sur vos déclarations</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="notifications-actions">
                <button onclick="markAllAsRead()" class="btn btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M5 13l4 4L19 7"/>
                    </svg>
                    Tout marquer comme lu
                </button>
            </div>

            <div class="notifications-list">
                @forelse($notifications as $notification)
                    <div class="notification-card {{ $notification->is_read ? 'read' : 'unread' }}" 
                         onclick="window.location.href='{{ $notification->action_url ?? '#' }}'">
                        <div class="notification-icon" style="background: {{ $notification->color === 'success' ? '#10b981' : ($notification->color === 'danger' ? '#ef4444' : '#3b82f6') }}20;">
                            <span>{{ $notification->icon }}</span>
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
                        </div>
                        <div class="notification-actions">
                            @if(!$notification->is_read)
                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-read" title="Marquer comme lu">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer cette notification ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" title="Supprimer">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(() => {
                window.location.reload();
            });
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

        document.addEventListener('DOMContentLoaded', loadTheme);
    </script>
</body>
</html>