<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Agent - e-Déclaration TG</title>
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
                    document.documentElement.classList.add('dark-mode');
                } else {
                    document.documentElement.style.backgroundColor = '#f5f7fa';
                    document.body.style.backgroundColor = '#f5f7fa';
                }
            } catch(e) {}
        })();
    </script>
    
    <style>
        /* ===== STYLES COMPLETS (inchangés) ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #f39c12;
            --primary-dark: #e67e22;
            --primary-light: #f1c40f;
            --secondary: #3498db;
            --success: #27ae60;
            --danger: #e74c3c;
            --warning: #f39c12;
            --info: #3b82f6;
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
            transition: background 0.2s ease;
        }
        body.dark-mode { background: #0f172a; }
        
        /* ===== SIDEBAR ===== */
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
        .nav-badge.orange { background: var(--primary); }
        .nav-badge.green { background: var(--success); }
        .nav-badge.blue { background: var(--info); }
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
        
        /* ===== MAIN CONTENT ===== */
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
        .alert {
            padding: 1rem 1.2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            background: white;
            border-left: 4px solid var(--success);
            transition: background 0.2s;
        }
        body.dark-mode .alert {
            background: #1e293b;
            color: #e5e7eb;
        }
        .alert-success { border-left-color: var(--success); }
        .alert-error { border-left-color: var(--danger); }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(6,1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.2rem;
            border: 1px solid var(--gray-200);
            transition: all 0.2s;
            cursor: pointer;
        }
        body.dark-mode .stat-card {
            background: #1e293b;
            border-color: #334155;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        .stat-card .stat-icon { font-size: 1.8rem; margin-bottom: 0.5rem; }
        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark);
        }
        body.dark-mode .stat-card .stat-value { color: #f1f5f9; }
        .stat-card .stat-label {
            font-size: 0.7rem;
            color: var(--gray-600);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .quick-actions {
            background: white;
            border-radius: 16px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--gray-200);
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
            transition: background 0.2s;
        }
        body.dark-mode .quick-actions {
            background: #1e293b;
            border-color: #334155;
        }
        .quick-action-btn {
            padding: 0.7rem 1.2rem;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        .quick-action-btn.primary {
            background: linear-gradient(135deg,var(--primary),var(--primary-dark));
            color: white;
        }
        .quick-action-btn.secondary {
            background: var(--gray-100);
            color: var(--gray-600);
        }
        body.dark-mode .quick-action-btn.secondary {
            background: #334155;
            color: #94a3b8;
        }
        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        .filter-tab {
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            background: white;
            color: var(--gray-600);
            border: 1px solid var(--gray-200);
            transition: all 0.2s;
        }
        body.dark-mode .filter-tab {
            background: #1e293b;
            border-color: #334155;
            color: #94a3b8;
        }
        .filter-tab:hover {
            border-color: var(--primary);
            color: var(--primary);
        }
        .filter-tab.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        body.dark-mode .section-title { color: #e5e7eb; }
        .section-action {
            background: linear-gradient(135deg,var(--primary),var(--primary-dark));
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .section-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(243,156,18,0.3);
        }
        
        /* ===== TABLEAU ===== */
        .table-responsive {
            overflow-x: auto;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 16px;
            overflow: hidden;
        }
        body.dark-mode .table {
            background: #1e293b;
        }
        .table thead {
            background: #f8fafc;
        }
        body.dark-mode .table thead {
            background: #0f172a;
        }
        .table th {
            padding: 0.8rem 1rem;
            text-align: left;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray-600);
            border-bottom: 2px solid var(--gray-200);
        }
        body.dark-mode .table th {
            color: #94a3b8;
            border-bottom-color: #334155;
        }
        .table td {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-800);
        }
        body.dark-mode .table td {
            border-bottom-color: #334155;
            color: #cbd5e1;
        }
        .table tr:last-child td { border-bottom: none; }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.7rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-validated { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-matching { background: #dbeafe; color: #1d4ed8; }
        .status-returned { background: #d1fae5; color: #059669; }
        .status-in-progress { background: #dbeafe; color: #1e40af; }
        .status-not-found { background: #e5e7eb; color: #374151; }
        body.dark-mode .status-pending { background: #422d0b; color: #fbbf24; }
        body.dark-mode .status-validated { background: #0a3b2a; color: #34d399; }
        body.dark-mode .status-rejected { background: #3f1e1e; color: #f87171; }
        body.dark-mode .status-matching { background: #1e3a5f; color: #60a5fa; }
        body.dark-mode .status-returned { background: #0a3b2a; color: #34d399; }
        body.dark-mode .status-in-progress { background: #1e3a5f; color: #60a5fa; }
        body.dark-mode .status-not-found { background: #1f2937; color: #9ca3af; }
        
        .locked-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.7rem;
            border-radius: 50px;
            font-size: 0.65rem;
            font-weight: 700;
            background: #fef3c7;
            color: #d97706;
        }
        body.dark-mode .locked-badge {
            background: #422d0b;
            color: #fbbf24;
        }
        .locked-badge .locked-by {
            font-weight: 400;
            opacity: 0.8;
        }
        
        .card-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
        }
        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: var(--gray-200);
            color: var(--gray-600);
        }
        body.dark-mode .btn-secondary {
            background: #334155;
            color: #94a3b8;
        }
        
        .document-card {
            background: white;
            border-radius: 16px;
            padding: 1.2rem;
            margin-bottom: 1rem;
            border: 1px solid var(--gray-200);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        body.dark-mode .document-card {
            background: #1e293b;
            border-color: #334155;
        }
        .document-card:hover {
            border-color: var(--primary);
            transform: translateX(3px);
        }
        .card-info { flex: 1; min-width: 200px; }
        .card-title {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        body.dark-mode .card-title { color: #e5e7eb; }
        .card-details {
            font-size: 0.8rem;
            color: var(--gray-600);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 0.3rem;
        }
        body.dark-mode .card-details { color: #94a3b8; }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--gray-600);
        }
        body.dark-mode .empty-state { color: #94a3b8; }
        .empty-state-icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.3; }
        
        @media (max-width:1200px) {
            .stats-grid { grid-template-columns: repeat(3,1fr); }
        }
        @media (max-width:1024px) {
            .sidebar { width: 100%; position: relative; height: auto; }
            .main { margin-left: 0; }
            .stats-grid { grid-template-columns: repeat(2,1fr); }
        }
        @media (max-width:640px) {
            .stats-grid { grid-template-columns: 1fr; }
            .card-actions { flex-direction: column; align-items: stretch; }
            .document-card { flex-direction: column; text-align: center; }
            .card-details { justify-content: center; }
        }
    </style>
</head>
<body>

@php
    use App\Models\Perte;
    use App\Models\DocumentTrouve;
    use App\Models\Notification;
    use App\Models\User;
    
    $user = auth()->user();
    $agentId = $user->id;
    
    $stats = [
        'total' => Perte::count(),
        'en_attente' => Perte::where('statut', 'en_attente')->count(),
        'en_cours' => Perte::where('statut', 'en_cours')->count(),
        'correspondance_trouvee' => Perte::where('statut', 'correspondance_trouvee')->count(),
        'restitue' => Perte::where('statut', 'restitue')->count(),
        'non_retrouve' => Perte::where('statut', 'non_retrouve')->count(),
        'validees' => Perte::where('statut', 'validee')->count(),
        'rejetees' => Perte::where('statut', 'rejetee')->count(),
        'traitees_par_moi' => Perte::where('validated_by', $user->id)->count(),
    ];
    
    $statsDocumentsTrouves = [
        'total' => DocumentTrouve::count(),
        'en_attente' => DocumentTrouve::where('statut', 'en_attente')->count(),
        'matche' => DocumentTrouve::where('statut', 'matche')->count(),
        'restitue' => DocumentTrouve::where('statut', 'restitue')->count(),
    ];
    
    $derniersTrouves = DocumentTrouve::orderBy('created_at', 'desc')->limit(5)->get();
    $pertes = Perte::orderBy('created_at', 'desc')->paginate(10);
    
    if(request('statut')) {
        $pertes = Perte::where('statut', request('statut'))->orderBy('created_at', 'desc')->paginate(10);
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
        <div class="republic-text">RÉPUBLIQUE TOGOLAISE</div>
        <div class="agent-badge">
            <i class="bi bi-shield-check"></i> AGENT
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">PRINCIPAL</div>
        <a href="{{ route('agent.dashboard') }}" class="active">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('agent.dashboard', ['statut' => 'en_attente']) }}">
            <i class="bi bi-hourglass-split"></i> En attente
            @if($stats['en_attente'] > 0)
                <span class="nav-badge">{{ $stats['en_attente'] }}</span>
            @endif
        </a>

        <div class="nav-section">DOCUMENTS</div>
        <a href="{{ route('agent.documents-trouves.index') }}">
            <i class="bi bi-search-heart"></i> Documents trouvés
            @if($statsDocumentsTrouves['en_attente'] > 0)
                <span class="nav-badge orange">{{ $statsDocumentsTrouves['en_attente'] }}</span>
            @endif
        </a>

        <div class="nav-section">ACTIONS RAPIDES</div>
        <a href="{{ route('agent.traiter-suivant') }}">
            <i class="bi bi-lightning-charge"></i> Traiter suivant
        </a>

        <div class="nav-section">ANALYTIQUES</div>
        <a href="{{ route('agent.statistiques') }}">
            <i class="bi bi-graph-up"></i> Statistiques
        </a>
        <a href="{{ route('agent.rapports') }}">
            <i class="bi bi-file-text"></i> Rapports
        </a>

        <div class="nav-section">COMMUNICATION</div>
        <a href="{{ route('agent.messages') }}">
            <i class="bi bi-chat-dots"></i> Messages
            <span class="nav-badge blue">2</span>
        </a>
        <a href="{{ route('agent.notifications') }}">
            <i class="bi bi-bell"></i> Notifications
            @php
                $unreadNotificationsCount = \App\Models\Notification::where('user_id', auth()->id())->where('type', 'system')->where('is_read', false)->count();
            @endphp
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
            <h1><i class="bi bi-speedometer2 me-2" style="color: var(--primary);"></i>Dashboard Agent</h1>
            <p>Gérez les déclarations de perte et les documents trouvés</p>
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
                @php
                    $unreadNotificationsCount = \App\Models\Notification::where('user_id', auth()->id())->where('type', 'system')->where('is_read', false)->count();
                @endphp
                @if($unreadNotificationsCount > 0)
                    <span class="notification-badge">{{ $unreadNotificationsCount }}</span>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
    @endif

    <!-- Quick Actions Bar -->
    <div class="quick-actions">
        <span style="font-weight: 600;"><i class="bi bi-lightning-charge me-1"></i> Actions rapides :</span>
        <a href="{{ route('agent.traiter-suivant') }}" class="quick-action-btn primary">
            <i class="bi bi-play-fill"></i> Traiter le prochain
        </a>
        <a href="{{ route('agent.documents-trouves.index') }}?statut=matche" class="quick-action-btn secondary">
            <i class="bi bi-link-45deg"></i> Matchs auto ({{ $statsDocumentsTrouves['matche'] }})
        </a>
        <a href="{{ route('agent.statistiques') }}" class="quick-action-btn secondary">
            <i class="bi bi-graph-up"></i> Voir stats
        </a>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card" onclick="window.location.href='{{ route('agent.dashboard') }}'">
            <div class="stat-icon"><i class="bi bi-files"></i></div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total déclarations</div>
        </div>
        <div class="stat-card" onclick="window.location.href='{{ route('agent.dashboard', ['statut' => 'en_attente']) }}'">
            <div class="stat-icon"><i class="bi bi-clock-history"></i></div>
            <div class="stat-value">{{ $stats['en_attente'] }}</div>
            <div class="stat-label">En attente</div>
        </div>
        <div class="stat-card" onclick="window.location.href='{{ route('agent.dashboard', ['statut' => 'en_cours']) }}'">
            <div class="stat-icon"><i class="bi bi-arrow-repeat"></i></div>
            <div class="stat-value">{{ $stats['en_cours'] }}</div>
            <div class="stat-label">En cours</div>
        </div>
        <div class="stat-card" onclick="window.location.href='{{ route('agent.dashboard', ['statut' => 'correspondance_trouvee']) }}'">
            <div class="stat-icon"><i class="bi bi-link-45deg"></i></div>
            <div class="stat-value">{{ $stats['correspondance_trouvee'] }}</div>
            <div class="stat-label">Correspondance trouvée</div>
        </div>
        <div class="stat-card" onclick="window.location.href='{{ route('agent.dashboard', ['statut' => 'restitue']) }}'">
            <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
            <div class="stat-value">{{ $stats['restitue'] }}</div>
            <div class="stat-label">Restitué</div>
        </div>
        <div class="stat-card" onclick="window.location.href='{{ route('agent.dashboard', ['statut' => 'non_retrouve']) }}'">
            <div class="stat-icon"><i class="bi bi-emoji-frown"></i></div>
            <div class="stat-value">{{ $stats['non_retrouve'] }}</div>
            <div class="stat-label">Non retrouvé</div>
        </div>
        <div class="stat-card" onclick="window.location.href='{{ route('agent.dashboard', ['statut' => 'validee']) }}'">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-value">{{ $stats['validees'] }}</div>
            <div class="stat-label">Validées</div>
        </div>
        <div class="stat-card" onclick="window.location.href='{{ route('agent.dashboard', ['statut' => 'rejetee']) }}'">
            <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
            <div class="stat-value">{{ $stats['rejetees'] }}</div>
            <div class="stat-label">Rejetées</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-person-check"></i></div>
            <div class="stat-value">{{ $stats['traitees_par_moi'] }}</div>
            <div class="stat-label">Traitées par moi</div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filter-tabs">
        <a href="{{ route('agent.dashboard') }}" class="filter-tab {{ !request('statut') ? 'active' : '' }}">
            <i class="bi bi-grid-3x3-gap-fill me-1"></i> Toutes
        </a>
        <a href="{{ route('agent.dashboard', ['statut' => 'en_attente']) }}" class="filter-tab {{ request('statut') == 'en_attente' ? 'active' : '' }}">
            <i class="bi bi-hourglass-split me-1"></i> En attente
            <span class="ms-1" style="background: rgba(255,255,255,0.2); padding: 0 5px; border-radius: 10px;">{{ $stats['en_attente'] }}</span>
        </a>
        <a href="{{ route('agent.dashboard', ['statut' => 'en_cours']) }}" class="filter-tab {{ request('statut') == 'en_cours' ? 'active' : '' }}">
            <i class="bi bi-arrow-repeat me-1"></i> En cours
            <span class="ms-1" style="background: rgba(255,255,255,0.2); padding: 0 5px; border-radius: 10px;">{{ $stats['en_cours'] }}</span>
        </a>
        <a href="{{ route('agent.dashboard', ['statut' => 'correspondance_trouvee']) }}" class="filter-tab {{ request('statut') == 'correspondance_trouvee' ? 'active' : '' }}">
            <i class="bi bi-link-45deg me-1"></i> Correspondance trouvée
            <span class="ms-1" style="background: rgba(255,255,255,0.2); padding: 0 5px; border-radius: 10px;">{{ $stats['correspondance_trouvee'] }}</span>
        </a>
        <a href="{{ route('agent.dashboard', ['statut' => 'restitue']) }}" class="filter-tab {{ request('statut') == 'restitue' ? 'active' : '' }}">
            <i class="bi bi-check2-circle me-1"></i> Restitué
            <span class="ms-1" style="background: rgba(255,255,255,0.2); padding: 0 5px; border-radius: 10px;">{{ $stats['restitue'] }}</span>
        </a>
        <a href="{{ route('agent.dashboard', ['statut' => 'non_retrouve']) }}" class="filter-tab {{ request('statut') == 'non_retrouve' ? 'active' : '' }}">
            <i class="bi bi-emoji-frown me-1"></i> Non retrouvé
            <span class="ms-1" style="background: rgba(255,255,255,0.2); padding: 0 5px; border-radius: 10px;">{{ $stats['non_retrouve'] }}</span>
        </a>
        <a href="{{ route('agent.dashboard', ['statut' => 'validee']) }}" class="filter-tab {{ request('statut') == 'validee' ? 'active' : '' }}">
            <i class="bi bi-check-circle me-1"></i> Validées
            <span class="ms-1" style="background: rgba(255,255,255,0.2); padding: 0 5px; border-radius: 10px;">{{ $stats['validees'] }}</span>
        </a>
        <a href="{{ route('agent.dashboard', ['statut' => 'rejetee']) }}" class="filter-tab {{ request('statut') == 'rejetee' ? 'active' : '' }}">
            <i class="bi bi-x-circle me-1"></i> Rejetées
            <span class="ms-1" style="background: rgba(255,255,255,0.2); padding: 0 5px; border-radius: 10px;">{{ $stats['rejetees'] }}</span>
        </a>
    </div>

    <!-- Section Déclarations de Perte -->
    <div class="section-header">
        <div class="section-title">
            <i class="bi bi-card-list" style="color: var(--primary);"></i>
            Déclarations de Perte
        </div>
    </div>

    @if($pertes->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Déclarant</th>
                        <th>Type</th>
                        <th>Date perte</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pertes as $perte)
                    @php
                        $statusMap = [
                            'en_attente' => ['class' => 'status-pending', 'icon' => '⏳', 'label' => 'En attente'],
                            'en_cours' => ['class' => 'status-in-progress', 'icon' => '🔍', 'label' => 'En cours'],
                            'correspondance_trouvee' => ['class' => 'status-matching', 'icon' => '🔗', 'label' => 'Correspondance trouvée'],
                            'restitue' => ['class' => 'status-returned', 'icon' => '✅', 'label' => 'Restitué'],
                            'non_retrouve' => ['class' => 'status-not-found', 'icon' => '❓', 'label' => 'Non retrouvé'],
                            'validee' => ['class' => 'status-validated', 'icon' => '✅', 'label' => 'Validée'],
                            'rejetee' => ['class' => 'status-rejected', 'icon' => '❌', 'label' => 'Rejetée'],
                        ];
                        $cfg = $statusMap[$perte->statut] ?? ['class' => 'status-pending', 'icon' => '📄', 'label' => ucfirst($perte->statut)];
                    @endphp
                    <tr>
                        <td><strong>#{{ str_pad($perte->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>{{ $perte->first_name }} {{ $perte->last_name }}</td>
                        <td>{{ $perte->type_piece }}</td>
                        <td>{{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}</td>
                        <td>
                            <span class="status-badge {{ $cfg['class'] }}">
                                {{ $cfg['icon'] }} {{ $cfg['label'] }}
                            </span>
                        </td>
                        <td>
                            <div class="card-actions">
                                <a href="{{ route('agent.perte.show', $perte->id) }}" class="btn-action btn-primary">
                                    <i class="bi bi-eye"></i> Voir
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
            <h4>Aucune déclaration trouvée</h4>
            <p>@if(request('statut')) Aucune déclaration avec le statut "{{ request('statut') }}" @else Les nouvelles déclarations apparaîtront ici @endif</p>
        </div>
    @endif

    <!-- Section Documents Trouvés Récents -->
    <div class="section-header" style="margin-top: 2rem;">
        <div class="section-title">
            <i class="bi bi-search-heart" style="color: var(--primary);"></i>
            Documents Trouvés Récents
        </div>
        <a href="{{ route('agent.documents-trouves.index') }}" class="section-action">
            Voir tout <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    @if($derniersTrouves->count() > 0)
        @foreach($derniersTrouves as $doc)
            @php
                if($doc->statut == 'en_attente') {
                    $docStatusClass = 'status-pending';
                    $docStatusIcon = '⏳';
                    $docStatusText = 'En attente';
                } elseif($doc->statut == 'matche') {
                    $docStatusClass = 'status-matching';
                    $docStatusIcon = '🔗';
                    $docStatusText = 'Matché';
                } else {
                    $docStatusClass = 'status-returned';
                    $docStatusIcon = '✅';
                    $docStatusText = 'Restitué';
                }
            @endphp
            <div class="document-card">
                <div class="card-info">
                    <div class="card-title">
                        {{ $doc->type_document }}
                        <span class="status-badge {{ $docStatusClass }}">
                            {{ $docStatusIcon }} {{ $docStatusText }}
                        </span>
                    </div>
                    <div class="card-details">
                        <span><i class="bi bi-geo-alt"></i> {{ $doc->lieu_decouverte }}</span>
                        <span><i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($doc->date_decouverte)->format('d/m/Y') }}</span>
                        <span><i class="bi bi-person"></i> {{ $doc->nom_declarant }}</span>
                        @if($doc->numero_document)
                            <span><i class="bi bi-upc-scan"></i> N°{{ $doc->numero_document }}</span>
                        @endif
                    </div>
                </div>
                <div class="card-actions">
                    <a href="{{ route('agent.documents-trouves.show', $doc->id) }}" class="btn-action btn-primary">
                        <i class="bi bi-eye"></i> Voir & Matcher
                    </a>
                </div>
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
            <h4>Aucun document trouvé</h4>
            <p>Les citoyens peuvent déclarer les documents qu'ils trouvent sur la plateforme</p>
        </div>
    @endif
</div>

<script>
    // ============================================================
    // HORLOGE
    // ============================================================
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        const formatted = now.toLocaleDateString('fr-FR', options).replace(',', ' -');
        const dateTimeEl = document.getElementById('currentDateTime');
        if (dateTimeEl) dateTimeEl.innerHTML = formatted;
    }
    updateDateTime();
    setInterval(updateDateTime, 60000);

    // ============================================================
    // MODE SOMBRE
    // ============================================================
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
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

    // Auto-hide alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.transition = 'opacity 0.3s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
</script>
</body>
</html>