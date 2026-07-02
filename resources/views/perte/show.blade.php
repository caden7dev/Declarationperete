<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Détails de la Déclaration - e-Déclaration TG</title>
    <script>
    // Anti-flash blanc
    (function() {
        const isDark = localStorage.getItem('darkMode') === 'true';
        if (isDark) {
            document.documentElement.style.backgroundColor = '#0f172a';
            document.body.style.backgroundColor = '#0f172a';
        }
    })();
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* ... vos styles existants (inchangés) ... */
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

        .detail-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
        }

        .breadcrumb-custom {
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .breadcrumb-custom a {
            color: white;
            text-decoration: none;
            opacity: 0.8;
        }

        .breadcrumb-custom a:hover {
            opacity: 1;
        }

        .breadcrumb-custom .separator {
            margin: 0 0.5rem;
            opacity: 0.5;
        }

        .breadcrumb-custom .current {
            font-weight: 600;
        }

        .header-title {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .header-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .btn-back, .btn-edit-header {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-block;
        }

        .btn-back:hover, .btn-edit-header:hover {
            background: white;
            color: var(--primary);
        }

        .status-badge-large {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(5px);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
        }

        .motif-rejet {
            background: #fffbeb;
            border-left: 4px solid var(--warning);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #b45309;
        }

        body.dark-mode .motif-rejet {
            background: #422d0b;
            color: #fbbf24;
        }

        .info-card, .timeline-card, .action-sidebar {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--gray-200);
            transition: all 0.2s;
        }

        body.dark-mode .info-card,
        body.dark-mode .timeline-card,
        body.dark-mode .action-sidebar {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        body.dark-mode .card-title {
            color: #e5e7eb;
        }

        .card-title i {
            color: var(--primary);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .info-item {
            background: var(--gray-100);
            border-radius: 12px;
            padding: 0.8rem;
        }

        body.dark-mode .info-item {
            background: #404048;
        }

        .info-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            margin-bottom: 0.3rem;
        }

        .info-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark);
        }

        body.dark-mode .info-value {
            color: #e5e7eb;
        }

        .documents-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .document-card {
            background: var(--gray-100);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        body.dark-mode .document-card {
            background: #404048;
        }

        .document-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .document-icon {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .document-name {
            font-weight: 600;
            font-size: 0.8rem;
            margin-bottom: 0.3rem;
        }

        .btn-download-card {
            background: white;
            border: 1px solid var(--gray-200);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
        }

        body.dark-mode .btn-download-card {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .timeline {
            position: relative;
            padding-left: 1.5rem;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
            border-left: 2px solid var(--gray-200);
            margin-left: 0.8rem;
        }

        body.dark-mode .timeline-item {
            border-left-color: #4b5563;
        }

        .timeline-item:last-child {
            border-left-color: transparent;
        }

        .timeline-dot {
            position: absolute;
            left: -0.65rem;
            top: 0;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: white;
            border: 2px solid var(--primary);
        }

        .timeline-date {
            font-size: 0.7rem;
            color: var(--gray-600);
            margin-bottom: 0.2rem;
        }

        .timeline-title {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--dark);
        }

        body.dark-mode .timeline-title {
            color: #e5e7eb;
        }

        .timeline-subtitle {
            font-size: 0.8rem;
            color: var(--gray-600);
        }

        .user-info {
            text-align: center;
            background: var(--gray-100);
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        body.dark-mode .user-info {
            background: #404048;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 0.8rem;
            color: white;
        }

        .user-name {
            font-weight: 700;
            font-size: 1rem;
            color: var(--dark);
        }

        body.dark-mode .user-name {
            color: #e5e7eb;
        }

        .user-email, .user-phone {
            font-size: 0.8rem;
            color: var(--gray-600);
            margin-top: 0.3rem;
        }

        .btn-action-sidebar {
            display: block;
            width: 100%;
            padding: 0.8rem;
            border-radius: 12px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 0.8rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-edit-sidebar {
            background: var(--warning);
            color: white;
        }

        .btn-delete-sidebar {
            background: var(--danger);
            color: white;
            border: none;
            width: 100%;
            cursor: pointer;
        }

        .btn-new-sidebar {
            background: var(--primary);
            color: white;
        }

        .btn-action-sidebar:hover {
            transform: translateY(-2px);
            filter: brightness(0.95);
        }

        /* Banner non retrouvé */
        .non-retrouve-banner {
            background: linear-gradient(135deg, #e5e7eb, #d1d5db);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid #9ca3af;
        }

        body.dark-mode .non-retrouve-banner {
            background: #1f2937;
            border-color: #4b5563;
        }

        .non-retrouve-banner .icon {
            font-size: 2rem;
        }

        .non-retrouve-banner .message {
            flex: 1;
        }

        .non-retrouve-banner .btn-light {
            background: white;
            color: #1f2937;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
        }

        .non-retrouve-banner .btn-light:hover {
            background: #f3f4f6;
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
                padding: 1rem;
            }
            .detail-header .d-flex {
                flex-direction: column;
                gap: 1rem;
            }
            .info-grid {
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

    // Détermination du texte et de la classe du statut
    $statusConfig = [
        'en_attente'             => ['label' => 'En attente', 'class' => 'bg-warning', 'icon' => 'bi-clock'],
        'en_cours'               => ['label' => 'En cours', 'class' => 'bg-info', 'icon' => 'bi-arrow-repeat'],
        'correspondance_trouvee' => ['label' => 'Correspondance trouvée', 'class' => 'bg-primary', 'icon' => 'bi-check-circle'],
        'restitue'               => ['label' => 'Restitué', 'class' => 'bg-success', 'icon' => 'bi-check2-circle'],
        'non_retrouve'           => ['label' => 'Non retrouvé', 'class' => 'bg-secondary', 'icon' => 'bi-emoji-frown'],
        'rejetee'                => ['label' => 'Rejetée', 'class' => 'bg-danger', 'icon' => 'bi-x-circle'],
        'validee'                => ['label' => 'Validée', 'class' => 'bg-success', 'icon' => 'bi-check-circle'],
    ];
    $statut = $perte->statut;
    $currentStatus = $statusConfig[$statut] ?? ['label' => ucfirst($statut), 'class' => 'bg-secondary', 'icon' => 'bi-question-circle'];
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
        <a href="{{ route('perte.index') }}" class="active">
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

    <div class="main-container">
        <!-- Header -->
        <div class="detail-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                <div class="breadcrumb-custom">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <span class="separator">›</span>
                    <a href="{{ route('perte.index') }}">Mes Déclarations</a>
                    <span class="separator">›</span>
                    <span class="current">Détails #{{ str_pad($perte->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div>
                    <a href="{{ route('perte.index') }}" class="btn-back me-2"><i class="bi bi-arrow-left me-1"></i>Retour</a>
                    @if($perte->statut == 'en_attente')
                        <a href="{{ route('perte.edit', $perte->id) }}" class="btn-edit-header"><i class="bi bi-pencil me-1"></i>Modifier</a>
                    @endif
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="header-title"><i class="bi bi-file-text me-2"></i>Déclaration #{{ str_pad($perte->id, 6, '0', STR_PAD_LEFT) }}</h1>
                    <p class="header-subtitle"><i class="bi bi-calendar me-2"></i>Soumise le {{ $perte->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="status-badge-large">
                        <i class="bi {{ $currentStatus['icon'] }}"></i>
                        {{ $currentStatus['label'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Motif de rejet -->
        @if($perte->statut == 'rejetee' && $perte->motif_rejet)
            <div class="motif-rejet">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Motif du rejet :</strong> {{ $perte->motif_rejet }}
            </div>
        @endif

        <!-- Banner "Non retrouvé" avec lien vers nouvelle déclaration -->
        @if($perte->statut == 'non_retrouve')
            <div class="non-retrouve-banner">
                <div class="icon">🔍</div>
                <div class="message">
                    <strong>Votre document n’a pas été retrouvé.</strong><br>
                    Vous pouvez refaire une déclaration en reprenant vos informations.
                </div>
                <a href="{{ route('perte.create', ['copy_from' => $perte->id]) }}" class="btn-light">
                    <i class="bi bi-plus-circle"></i> Refaire une déclaration
                </a>
            </div>
        @endif

        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Informations générales -->
                <div class="info-card">
                    <div class="card-title"><i class="bi bi-info-circle"></i>Informations générales</div>
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
                    <div class="card-title"><i class="bi bi-exclamation-triangle"></i>Détails de la perte</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Date de la perte</div>
                            <div class="info-value">{{ $perte->date_perte->format('d/m/Y') }} <small class="text-muted">({{ $perte->date_perte->diffForHumans() }})</small></div>
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

                <!-- Documents justificatifs -->
                @if($perte->copie_piece || $perte->declaration_vol || $perte->document_complementaire)
                    <div class="info-card">
                        <div class="card-title"><i class="bi bi-paperclip"></i>Documents justificatifs</div>
                        <div class="documents-grid">
                            @if($perte->copie_piece)
                                <div class="document-card" onclick="window.open('{{ Storage::url($perte->copie_piece) }}', '_blank')">
                                    <div class="document-icon"><i class="bi bi-file-pdf"></i></div>
                                    <div class="document-name">Pièce d'identité</div>
                                    <a href="{{ Storage::url($perte->copie_piece) }}" download class="btn-download-card"><i class="bi bi-download me-1"></i>Télécharger</a>
                                </div>
                            @endif
                            @if($perte->declaration_vol)
                                <div class="document-card" onclick="window.open('{{ Storage::url($perte->declaration_vol) }}', '_blank')">
                                    <div class="document-icon"><i class="bi bi-file-text"></i></div>
                                    <div class="document-name">Déclaration de vol</div>
                                    <a href="{{ Storage::url($perte->declaration_vol) }}" download class="btn-download-card"><i class="bi bi-download me-1"></i>Télécharger</a>
                                </div>
                            @endif
                            @if($perte->document_complementaire)
                                <div class="document-card" onclick="window.open('{{ Storage::url($perte->document_complementaire) }}', '_blank')">
                                    <div class="document-icon"><i class="bi bi-file-earmark"></i></div>
                                    <div class="document-name">Document complémentaire</div>
                                    <a href="{{ Storage::url($perte->document_complementaire) }}" download class="btn-download-card"><i class="bi bi-download me-1"></i>Télécharger</a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Historique enrichi (timeline) -->
                <div class="timeline-card">
                    <div class="card-title"><i class="bi bi-clock-history"></i>Historique de la déclaration</div>
                    <div class="timeline">
                        <!-- Étape 1 : Soumission -->
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-date"><i class="bi bi-calendar me-1"></i>{{ $perte->created_at->format('d/m/Y H:i') }}</div>
                            <div class="timeline-title">Déclaration soumise</div>
                            <div class="timeline-subtitle">En attente de traitement</div>
                        </div>

                        <!-- Étape 2 : Prise en charge (si statut >= en_cours) -->
                        @php
                            $takenCharge = in_array($perte->statut, ['en_cours', 'correspondance_trouvee', 'restitue', 'non_retrouve', 'validee', 'rejetee']);
                        @endphp
                        <div class="timeline-item">
                            <div class="timeline-dot" style="border-color: {{ $takenCharge ? '#10b981' : '#cbd5e1' }};"></div>
                            <div class="timeline-date">
                                @if($takenCharge && $perte->validated_at)
                                    {{ $perte->validated_at->format('d/m/Y H:i') }}
                                @else
                                    —
                                @endif
                            </div>
                            <div class="timeline-title">Prise en charge par un agent</div>
                            <div class="timeline-subtitle">
                                @if($perte->validator)
                                    Par {{ $perte->validator->name }}
                                @elseif(!$takenCharge)
                                    En attente
                                @endif
                            </div>
                        </div>

                        <!-- Étape 3 : Correspondance trouvée (si statut correspondance_trouvee ou restitue) -->
                        @php
                            $found = in_array($perte->statut, ['correspondance_trouvee', 'restitue']);
                        @endphp
                        <div class="timeline-item">
                            <div class="timeline-dot" style="border-color: {{ $found ? '#10b981' : '#cbd5e1' }};"></div>
                            <div class="timeline-date">
                                @if($found && $perte->document_trouve_id)
                                    {{ $perte->updated_at->format('d/m/Y H:i') }}
                                @else
                                    —
                                @endif
                            </div>
                            <div class="timeline-title">Correspondance trouvée</div>
                            <div class="timeline-subtitle">
                                @if($found)
                                    Un document correspondant a été identifié
                                @else
                                    Non encore trouvé
                                @endif
                            </div>
                        </div>

                        <!-- Étape 4 : Restitution (si restitue) -->
                        <div class="timeline-item">
                            <div class="timeline-dot" style="border-color: {{ $perte->statut == 'restitue' ? '#10b981' : '#cbd5e1' }};"></div>
                            <div class="timeline-date">
                                @if($perte->statut == 'restitue' && $perte->date_restitution)
                                    {{ $perte->date_restitution->format('d/m/Y H:i') }}
                                @else
                                    —
                                @endif
                            </div>
                            <div class="timeline-title">Document restitué</div>
                            <div class="timeline-subtitle">
                                @if($perte->statut == 'restitue')
                                    Félicitations, votre document vous a été rendu
                                @else
                                    En attente de restitution
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne latérale -->
            <div class="col-lg-4">
                <div class="action-sidebar">
                    <div class="card-title"><i class="bi bi-person"></i>Déclarant</div>
                    <div class="user-info">
                        <div class="user-avatar"><i class="bi bi-person-fill"></i></div>
                        <div class="user-name">{{ $perte->user->name ?? 'N/A' }}</div>
                        <div class="user-email"><i class="bi bi-envelope me-1"></i>{{ $perte->user->email ?? 'N/A' }}</div>
                        @if($perte->contact)
                            <div class="user-phone"><i class="bi bi-telephone me-1"></i>{{ $perte->contact }}</div>
                        @endif
                    </div>

                    <div class="card-title"><i class="bi bi-gear"></i>Actions</div>
                    @if($perte->statut == 'en_attente')
                        <a href="{{ route('perte.edit', $perte->id) }}" class="btn-action-sidebar btn-edit-sidebar"><i class="bi bi-pencil me-2"></i>Modifier la déclaration</a>
                        <form action="{{ route('perte.destroy', $perte->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cette déclaration ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action-sidebar btn-delete-sidebar"><i class="bi bi-trash me-2"></i>Supprimer la déclaration</button>
                        </form>
                    @endif
                    <a href="{{ route('perte.create') }}" class="btn-action-sidebar btn-new-sidebar"><i class="bi bi-plus-circle me-2"></i>Nouvelle déclaration</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Dark mode
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
        const serverTheme = '{{ auth()->user()->theme ?? "light" }}';
        const localTheme = localStorage.getItem('darkMode');
        const theme = localTheme === 'dark' ? 'dark' : (serverTheme ?? 'light');
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
</script>
</body>
</html>