<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Paramètres - e-Déclaration TG</title>
    <script>
    // Anti-flash blanc - À mettre tout en haut du head
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
        /* ===== MÊMES STYLES QUE LE DASHBOARD ===== */
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

        /* Alertes */
        .alert {
            padding: 1rem 1.2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            background: white;
            border-left: 4px solid var(--primary);
            transition: background 0.3s, color 0.3s;
        }

        body.dark-mode .alert {
            background: #2d2d35;
            color: #e5e7eb;
        }
        body.dark-mode .alert-success { color: #a7f3d0; }
        body.dark-mode .alert-error { color: #fecaca; background: #3f1e1e; }

        /* Top Bar Icons */
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

        /* Top Bar Titre */
        .top-bar {
            background: white;
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--gray-200);
            transition: background 0.3s, border-color 0.3s;
        }

        body.dark-mode .top-bar {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .top-bar h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark);
        }

        body.dark-mode .top-bar h1 {
            color: #f1f5f9;
        }

        .top-bar p {
            color: var(--gray-600);
            font-size: 0.85rem;
            margin-top: 0.2rem;
        }

        /* Profile Header Card */
        .profile-header-card {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            color: white;
            flex-wrap: wrap;
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
        }

        .profile-header-info h2 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .profile-badges {
            display: flex;
            gap: 0.8rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .badge {
            background: rgba(255,255,255,0.2);
            padding: 0.3rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            background: white;
            padding: 0.5rem;
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            flex-wrap: wrap;
        }

        body.dark-mode .tabs {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .tab {
            padding: 0.8rem 1.5rem;
            border: none;
            background: transparent;
            color: var(--gray-600);
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        body.dark-mode .tab {
            color: #9ca3af;
        }

        .tab:hover {
            background: rgba(16, 185, 129, 0.08);
            color: var(--primary);
        }

        .tab.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .tab svg {
            width: 16px;
            height: 16px;
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
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--gray-200);
            transition: background 0.3s, border-color 0.3s;
        }

        body.dark-mode .settings-card {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .settings-card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .settings-card-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #10b981, #34d399);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: white;
        }

        .settings-card-title h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
        }

        body.dark-mode .settings-card-title h3 {
            color: #e5e7eb;
        }

        .settings-card-title p {
            font-size: 0.8rem;
            color: var(--gray-600);
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
            color: var(--gray-800);
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.2s;
            background: white;
            color: var(--gray-800);
        }

        body.dark-mode .form-input {
            background: #4b5563;
            border-color: #6b7280;
            color: #e5e7eb;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-input:disabled {
            background: var(--gray-100);
            cursor: not-allowed;
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-600);
        }

        body.dark-mode .btn-secondary {
            background: #404040;
            color: #9ca3af;
        }

        .btn-secondary:hover {
            background: var(--gray-200);
        }

        .btn-danger {
            background: #fef2f2;
            color: var(--danger);
        }

        body.dark-mode .btn-danger {
            background: #3f1e1e;
            color: #f87171;
        }

        .btn-danger:hover {
            background: #fee2e2;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        /* Setting Item */
        .setting-item {
            padding: 1.2rem;
            background: var(--gray-100);
            border-radius: 16px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s;
            flex-wrap: wrap;
            gap: 1rem;
        }

        body.dark-mode .setting-item {
            background: #404048;
        }

        .setting-item-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .setting-icon {
            width: 45px;
            height: 45px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        body.dark-mode .setting-icon {
            background: #2d2d35;
        }

        .setting-text h4 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.2rem;
        }

        body.dark-mode .setting-text h4 {
            color: #e5e7eb;
        }

        .setting-text p {
            font-size: 0.75rem;
            color: var(--gray-600);
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
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-mini {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 16px;
            padding: 1rem;
            text-align: center;
        }

        body.dark-mode .stat-mini {
            background: #2d2d35;
            border-color: #4b5563;
        }

        .stat-mini-icon {
            font-size: 2rem;
            margin-bottom: 0.3rem;
        }

        .stat-mini-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark);
        }

        body.dark-mode .stat-mini-value {
            color: #f1f5f9;
        }

        .stat-mini-label {
            font-size: 0.75rem;
            color: var(--gray-600);
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
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 24px;
            max-width: 500px;
            width: 90%;
            animation: modalIn 0.3s ease-out;
        }

        body.dark-mode .modal-content {
            background: #2d2d35;
        }

        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--dark);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray-600);
        }

        /* Toast notification */
        .toast-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--primary);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            z-index: 2000;
            animation: fadeInUp 0.3s ease-out;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .toast-notification.error {
            background: var(--danger);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
            .profile-header-card {
                flex-direction: column;
                text-align: center;
            }
            .profile-avatar-large {
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>

@php
    // FORCER L'APPLICATION DE LA LANGUE DEPUIS LA SESSION
    if(session('locale')) {
        App::setLocale(session('locale'));
    }
    
    use App\Models\Perte;
    use App\Models\Notification;
    $user = auth()->user();
    $totalDeclarations = Perte::where('user_id', auth()->id())->count();
    $enAttente = Perte::where('user_id', auth()->id())->where('statut', 'en_attente')->count();
    $validees = Perte::where('user_id', auth()->id())->where('statut', 'validee')->count();
    $rejetees = Perte::where('user_id', auth()->id())->where('statut', 'rejetee')->count();
    $dernieresDeclarations = Perte::where('user_id', auth()->id())->orderBy('created_at', 'desc')->take(3)->get();

    // ============================================================
    // ⚠️ COMPTEUR CORRIGÉ : Exclusion des messages (agent_message)
    // et des notifications expirées
    // ============================================================
    $unreadNotificationsCount = Notification::where('user_id', auth()->id())
        ->where('type', '!=', 'agent_message')
        ->notExpired()
        ->where('is_read', false)
        ->count();

    // Préférences chargées depuis l'utilisateur ou session
    $preferences = session('user_preferences', $user->preferences ?? []);
    // S'assurer que la langue des préférences est utilisée
    if(isset($preferences['language']) && $preferences['language'] != app()->getLocale()) {
        App::setLocale($preferences['language']);
        session(['locale' => $preferences['language']]);
    }
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
        <a href="{{ route('notifications.index') }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            Notifications
            @if($unreadNotificationsCount > 0)
                <span style="background: #ef4444; color: white; font-size: 0.65rem; border-radius: 20px; padding: 0 0.4rem; margin-left: auto;">{{ $unreadNotificationsCount }}</span>
            @endif
        </a>
        <a href="{{ route('profile.index') }}" class="active">
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
    <!-- Top Bar Icons -->
    <div class="top-bar-icons">
        <button class="icon-btn theme-toggle" onclick="toggleGlobalDarkMode()" title="Changer le thème">
            <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </button>
        <button class="icon-btn notification-btn" onclick="openNotifications()" title="Voir les notifications">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            @if($unreadNotificationsCount > 0)
                <span class="notification-badge">{{ $unreadNotificationsCount }}</span>
            @endif
        </button>
    </div>

    <!-- Top Bar Titre -->
    <div class="top-bar">
        <h1>⚙️ Paramètres du compte</h1>
        <p>Gérez vos informations personnelles et vos préférences</p>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">❌ {{ $errors->first() }}</div>
        @endif

        <!-- Profile Header -->
        <div class="profile-header-card">
            <div class="profile-avatar-large">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
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
            <button class="tab active" onclick="showTab('profile')"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> Profil</button>
            <button class="tab" onclick="showTab('security')"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> Sécurité</button>
            <button class="tab" onclick="showTab('stats')"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg> Statistiques</button>
            <button class="tab" onclick="showTab('preferences')"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg> Préférences</button>
        </div>

        <!-- Tab Profile (inchangé) -->
        <div class="tab-content active" id="profile-content">
            <div class="settings-card">
                <div class="settings-card-header"><div class="settings-card-icon">👤</div><div class="settings-card-title"><h3>Informations personnelles</h3><p>Mettez à jour vos informations de profil</p></div></div>
                <form method="POST" action="{{ route('profile.update') }}" id="profileForm">@csrf @method('PUT')
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Nom complet *</label><input type="text" class="form-input" name="name" value="{{ old('name', $user->name) }}" required></div>
                        <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-input" value="{{ $user->email }}" disabled></div>
                        <div class="form-group"><label class="form-label">Téléphone</label><input type="tel" class="form-input" name="contact" value="{{ old('contact', $user->contact ?? '') }}"></div>
                        <div class="form-group"><label class="form-label">Date de naissance</label><input type="date" class="form-input" name="birth_date" value="{{ old('birth_date', $user->birth_date ?? '') }}"></div>
                        <div class="form-group full-width"><label class="form-label">Adresse complète</label><input type="text" class="form-input" name="address" value="{{ old('address', $user->address ?? '') }}"></div>
                        <div class="form-group"><label class="form-label">Nationalité</label><input type="text" class="form-input" name="nationality" value="{{ old('nationality', $user->nationality ?? 'Togolaise') }}"></div>
                        <div class="form-group"><label class="form-label">Genre</label><select class="form-input" name="gender"><option value="M" {{ old('gender', $user->gender) == 'M' ? 'selected' : '' }}>Masculin</option><option value="F" {{ old('gender', $user->gender) == 'F' ? 'selected' : '' }}>Féminin</option></select></div>
                    </div>
                    <div class="btn-group"><button type="submit" class="btn btn-primary">💾 Enregistrer</button><button type="reset" class="btn btn-secondary">↺ Réinitialiser</button></div>
                </form>
            </div>
        </div>

        <!-- Tab Security (inchangé) -->
        <div class="tab-content" id="security-content">
            <div class="settings-card">
                <div class="settings-card-header"><div class="settings-card-icon">🔐</div><div class="settings-card-title"><h3>Sécurité du compte</h3><p>Protégez votre compte</p></div></div>
                <div class="setting-item"><div class="setting-item-info"><div class="setting-icon">🔑</div><div class="setting-text"><h4>Mot de passe</h4><p>Dernière modification {{ $user->updated_at->diffForHumans() }}</p></div></div><button class="btn btn-primary" onclick="openModal('passwordModal')">Modifier</button></div>
                <div class="setting-item"><div class="setting-item-info"><div class="setting-icon">📧</div><div class="setting-text"><h4>Email de connexion</h4><p>{{ $user->email }}</p></div></div><button class="btn btn-primary" onclick="openModal('emailModal')">Modifier</button></div>
                <div class="setting-item"><div class="setting-item-info"><div class="setting-icon">🔔</div><div class="setting-text"><h4>2FA</h4><p>Bientôt disponible</p></div></div><label class="toggle-switch"><input type="checkbox" disabled><span class="toggle-slider"></span></label></div>
            </div>
            <div class="settings-card"><div class="settings-card-header"><div class="settings-card-icon" style="background:#fee2e2;">⚠️</div><div class="settings-card-title"><h3 style="color:#dc2626;">Zone de danger</h3><p>Actions irréversibles</p></div></div><div class="setting-item"><div class="setting-item-info"><div class="setting-icon">🗑️</div><div class="setting-text"><h4>Supprimer mon compte</h4><p>Supprimer définitivement votre compte</p></div></div><button class="btn btn-danger" onclick="openModal('deleteModal')">Supprimer</button></div></div>
        </div>

        <!-- Tab Stats (inchangé) -->
        <div class="tab-content" id="stats-content">
            <div class="settings-card">
                <div class="settings-card-header"><div class="settings-card-icon">📊</div><div class="settings-card-title"><h3>Vos statistiques</h3><p>Aperçu de votre activité</p></div></div>
                <div class="stats-grid"><div class="stat-mini"><div class="stat-mini-icon">📄</div><div class="stat-mini-value">{{ $totalDeclarations }}</div><div class="stat-mini-label">Total</div></div><div class="stat-mini"><div class="stat-mini-icon">⏳</div><div class="stat-mini-value">{{ $enAttente }}</div><div class="stat-mini-label">En attente</div></div><div class="stat-mini"><div class="stat-mini-icon">✅</div><div class="stat-mini-value">{{ $validees }}</div><div class="stat-mini-label">Validées</div></div><div class="stat-mini"><div class="stat-mini-icon">❌</div><div class="stat-mini-value">{{ $rejetees }}</div><div class="stat-mini-label">Rejetées</div></div></div>
                <div class="btn-group"><a href="{{ route('perte.index') }}" class="btn btn-primary">📋 Voir toutes mes déclarations</a><a href="{{ route('perte.create') }}" class="btn btn-secondary">➕ Nouvelle déclaration</a></div>
            </div>
        </div>

        <!-- Tab Preferences (AVEC FORMULAIRE POST POUR LA LANGUE) -->
        <div class="tab-content" id="preferences-content">
            <div class="settings-card">
                <div class="settings-card-header"><div class="settings-card-icon">🎨</div><div class="settings-card-title"><h3>Préférences d'affichage</h3><p>Personnalisez votre expérience</p></div></div>
                <div class="setting-item"><div class="setting-item-info"><div class="setting-icon">🌙</div><div class="setting-text"><h4>Mode sombre</h4><p>Activer le thème sombre</p></div></div><label class="toggle-switch"><input type="checkbox" name="dark_mode" value="1" {{ ($preferences['dark_mode'] ?? false) ? 'checked' : '' }} id="darkModeToggle"><span class="toggle-slider"></span></label></div>
                <div class="setting-item"><div class="setting-item-info"><div class="setting-icon">🔔</div><div class="setting-text"><h4>Notifications email</h4><p>Recevoir des notifications par email</p></div></div><label class="toggle-switch"><input type="checkbox" name="email_notifications" value="1" {{ ($preferences['email_notifications'] ?? true) ? 'checked' : '' }} id="emailNotifToggle"><span class="toggle-slider"></span></label></div>
                <div class="setting-item"><div class="setting-item-info"><div class="setting-icon">📱</div><div class="setting-text"><h4>Notifications SMS</h4><p>Disponible prochainement</p></div></div><label class="toggle-switch"><input type="checkbox" disabled><span class="toggle-slider"></span></label></div>
            </div>

            <!-- ✅ NOUVEAU FORMULAIRE POST POUR LA LANGUE ET LE FUSEAU HORAIRE -->
            <div class="settings-card">
                <div class="settings-card-header">
                    <div class="settings-card-icon">🌍</div>
                    <div class="settings-card-title">
                        <h3>Langue et région</h3>
                        <p>Configurez vos préférences linguistiques</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.language') }}">
                    @csrf

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label">Langue de l'interface</label>
                        <select class="form-input" name="locale" id="languageSelect">
                            <option value="fr" {{ app()->getLocale() === 'fr' ? 'selected' : '' }}>
                                🇫🇷 Français
                            </option>
                            <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>
                                🇬🇧 English
                            </option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label">Fuseau horaire</label>
                        <select class="form-input" name="timezone" id="timezoneSelect">
                            <option value="Africa/Lome" {{ ($preferences['timezone'] ?? 'Africa/Lome') == 'Africa/Lome' ? 'selected' : '' }}>
                                🇹🇬 Afrique/Lomé (GMT+0)
                            </option>
                        </select>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            🌐 Enregistrer les préférences
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modals (inchangés) -->
<div class="modal" id="passwordModal"><div class="modal-content"><div class="modal-header"><div class="modal-title">Changer le mot de passe</div><button class="modal-close" onclick="closeModal('passwordModal')">×</button></div><form method="POST" action="{{ route('profile.password') }}">@csrf @method('PUT')<div class="form-group"><label class="form-label">Mot de passe actuel</label><input type="password" class="form-input" name="current_password" required></div><div class="form-group"><label class="form-label">Nouveau mot de passe</label><input type="password" class="form-input" name="password" required></div><div class="form-group"><label class="form-label">Confirmer</label><input type="password" class="form-input" name="password_confirmation" required></div><div class="btn-group"><button type="submit" class="btn btn-primary">Enregistrer</button><button type="button" class="btn btn-secondary" onclick="closeModal('passwordModal')">Annuler</button></div></form></div></div>
<div class="modal" id="emailModal"><div class="modal-content"><div class="modal-header"><div class="modal-title">Modifier l'email</div><button class="modal-close" onclick="closeModal('emailModal')">×</button></div><form method="POST" action="{{ route('profile.email') }}">@csrf @method('PUT')<div class="form-group"><label class="form-label">Email actuel</label><input type="email" class="form-input" value="{{ $user->email }}" disabled></div><div class="form-group"><label class="form-label">Nouvel email</label><input type="email" class="form-input" name="email" required></div><div class="form-group"><label class="form-label">Mot de passe</label><input type="password" class="form-input" name="password" required></div><div class="btn-group"><button type="submit" class="btn btn-primary">Enregistrer</button><button type="button" class="btn btn-secondary" onclick="closeModal('emailModal')">Annuler</button></div></form></div></div>
<div class="modal" id="deleteModal"><div class="modal-content"><div class="modal-header"><div class="modal-title" style="color:#dc2626;">⚠️ Supprimer le compte</div><button class="modal-close" onclick="closeModal('deleteModal')">×</button></div><div style="text-align:center;margin-bottom:1rem;"><div style="font-size:3rem;">🗑️</div><h3>Êtes-vous absolument sûr ?</h3><p style="color:var(--gray-600);margin-top:0.5rem;">Action irréversible</p></div><form method="POST" action="{{ route('profile.delete') }}">@csrf @method('DELETE')<div class="form-group"><label class="form-label">Confirmez avec votre mot de passe</label><input type="password" class="form-input" name="password" required placeholder="Mot de passe"></div><div class="btn-group"><button type="submit" class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?')">Oui, supprimer</button><button type="button" class="btn btn-secondary" onclick="closeModal('deleteModal')">Annuler</button></div></form></div></div>

<script>
    // ===================== THÈME =====================
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
        const darkToggle = document.getElementById('darkModeToggle');
        if (darkToggle) darkToggle.checked = isDark;
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
            headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json'},
            body: JSON.stringify({ dark_mode: isDark })
        }).catch(console.error);
        showToast('Mode ' + (isDark ? 'sombre' : 'clair') + ' activé');
    }

    // ===================== PRÉFÉRENCES AJAX (sans la langue) =====================
    async function savePreferences() {
        const darkMode = document.getElementById('darkModeToggle').checked;
        const emailNotif = document.getElementById('emailNotifToggle').checked;

        const formData = new FormData();
        formData.append('dark_mode', darkMode ? 1 : 0);
        formData.append('email_notifications', emailNotif ? 1 : 0);
        // ⚠️ On envoie PAS la langue ici, elle est gérée par le formulaire POST

        try {
            const response = await fetch('{{ route("profile.preferences") }}', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
                body: formData
            });
            const data = await response.json();
            if (response.ok && data.success) {
                showToast('✅ Préférences enregistrées');
            } else {
                showToast('❌ Erreur lors de l\'enregistrement', true);
            }
        } catch (error) {
            console.error(error);
            showToast('❌ Erreur réseau', true);
        }
    }

    function showToast(message, isError = false) {
        const toast = document.createElement('div');
        toast.className = 'toast-notification' + (isError ? ' error' : '');
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // ===================== NOTIFICATIONS =====================
    function openNotifications() {
        const notifications = [
            @foreach($dernieresDeclarations as $declaration)
            { message: 'Déclaration #{{ $declaration->id }} - {{ $declaration->type_piece }}', statut: '{{ $declaration->statut }}', date: '{{ $declaration->created_at->diffForHumans() }}' },
            @endforeach
        ];
        const modal = document.createElement('div');
        modal.style.cssText = `position:fixed;top:80px;right:20px;background:${document.body.classList.contains('dark-mode') ? '#2d2d35' : 'white'};border-radius:16px;padding:1.5rem;min-width:320px;z-index:1000;box-shadow:0 20px 40px rgba(0,0,0,0.15);color:${document.body.classList.contains('dark-mode') ? '#e5e7eb' : '#1e293b'}`;
        if (notifications.length === 0) modal.innerHTML = `<div style="text-align:center;"><div style="font-size:2rem;">📭</div><p>Aucune notification</p></div>`;
        else {
            modal.innerHTML = `<div style="display:flex;justify-content:space-between;margin-bottom:1rem;"><h4>Notifications</h4><button onclick="this.closest('div').remove()" style="background:none;border:none;font-size:1.2rem;">✕</button></div>
            ${notifications.map(n => `<div style="padding:0.5rem 0;border-bottom:1px solid #eee;"><span>${n.statut === 'en_attente' ? '⏳' : n.statut === 'validee' ? '✅' : '❌'}</span> ${n.message}<br><small>${n.date}</small></div>`).join('')}
            <button onclick="window.location.href='{{ route('perte.index') }}'" style="width:100%;margin-top:1rem;padding:0.8rem;background:#10b981;color:white;border:none;border-radius:8px;">Voir toutes</button>`;
        }
        document.body.appendChild(modal);
        setTimeout(() => document.addEventListener('click', function close(e){ if(!modal.contains(e.target) && !e.target.closest('.notification-btn')) modal.remove(); document.removeEventListener('click', close); }), 100);
    }

    // ===================== TABS =====================
    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.getElementById(tabName + '-content').classList.add('active');
        event.target.closest('.tab').classList.add('active');
        sessionStorage.setItem('activeTab', tabName);
    }

    // ===================== MODALS =====================
    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }
    document.querySelectorAll('.modal').forEach(m => m.addEventListener('click', e => { if(e.target === m) m.classList.remove('active'); }));

    // ===================== INIT =====================
    document.addEventListener('DOMContentLoaded', () => {
        loadTheme();
        const activeTab = sessionStorage.getItem('activeTab');
        if (activeTab) {
            const btn = document.querySelector(`.tab[onclick*="${activeTab}"]`);
            if (btn) btn.click();
        }
        
        // Événements pour le mode sombre et les notifications email
        document.getElementById('darkModeToggle').addEventListener('change', function(e) {
            const isDark = this.checked;
            applyTheme(isDark);
            fetch('{{ route("profile.toggle-dark-mode") }}', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json'},
                body: JSON.stringify({ dark_mode: isDark })
            }).catch(console.error);
        });
        
        document.getElementById('emailNotifToggle').addEventListener('change', savePreferences);
        
        // ⚠️ Plus d'événements sur languageSelect et timezoneSelect car ils sont dans un formulaire POST
    });
</script>
</body>
</html>