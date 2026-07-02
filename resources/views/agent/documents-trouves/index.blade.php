<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Documents trouvés - Agent | e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- ⚡ ANTI-FLASH BLANC -->
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
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #f39c12;
            --primary-dark: #e67e22;
            --primary-light: #f1c40f;
            --success: #27ae60;
            --danger: #e74c3c;
            --info: #3498db;
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

        body.dark-mode {
            background: #0f172a;
        }

        /* ===== SIDEBAR ===== (identique au dashboard agent) */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            border-right: 1px solid rgba(243, 156, 18, 0.15);
            box-shadow: 2px 0 20px rgba(0,0,0,0.05);
            transition: background 0.2s, border-color 0.2s;
        }

        body.dark-mode .sidebar {
            background: rgba(20, 20, 30, 0.98);
            border-right-color: rgba(243, 156, 18, 0.3);
        }

        .sidebar-header {
            padding: 2rem 1.5rem 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        body.dark-mode .sidebar-header {
            border-bottom-color: #334155;
        }

        .sidebar-header h2 {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        body.dark-mode .sidebar-header h2 {
            color: #e5e7eb;
        }

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

        .flag-icon svg {
            width: 100%;
            height: 100%;
        }

        .republic-text {
            font-size: 0.65rem;
            color: var(--gray-600);
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-top: 0.3rem;
            margin-left: 0.5rem;
        }

        body.dark-mode .republic-text {
            color: #94a3b8;
        }

        .agent-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
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

        body.dark-mode .nav-section {
            color: #64748b;
        }

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
        }

        body.dark-mode .sidebar-nav a {
            color: #9ca3af;
        }

        .sidebar-nav a i {
            width: 20px;
            font-size: 1.1rem;
        }

        .sidebar-nav a:hover {
            background: rgba(243, 156, 18, 0.08);
            color: var(--primary);
        }

        body.dark-mode .sidebar-nav a:hover {
            background: rgba(243, 156, 18, 0.2);
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.12), rgba(241, 196, 15, 0.08));
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

        body.dark-mode .sidebar-footer {
            border-top-color: #334155;
        }

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

        /* Top bar */
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
            transition: background 0.2s, border-color 0.2s;
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

        body.dark-mode .top-bar-left h1 {
            color: #f1f5f9;
        }

        .top-bar-left p {
            color: var(--gray-600);
            font-size: 0.85rem;
        }

        body.dark-mode .top-bar-left p {
            color: #94a3b8;
        }

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

        body.dark-mode .icon-btn svg {
            stroke: #9ca3af;
        }

        .icon-btn:hover {
            border-color: var(--primary);
            background: rgba(243, 156, 18, 0.08);
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

        /* Alertes */
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

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.2rem;
            border: 1px solid var(--gray-200);
            transition: all 0.2s;
            border-left: 4px solid;
        }

        body.dark-mode .stat-card {
            background: #1e293b;
            border-color: #334155;
        }

        .stat-card.pending { border-left-color: var(--primary); }
        .stat-card.matched { border-left-color: var(--info); }
        .stat-card.restituted { border-left-color: var(--success); }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark);
        }

        body.dark-mode .stat-card .stat-value {
            color: #f1f5f9;
        }

        .stat-card .stat-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--gray-600);
            letter-spacing: 0.5px;
            margin-top: 0.3rem;
        }

        /* Filtres */
        .filters {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--gray-200);
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: flex-end;
            transition: background 0.2s;
        }

        body.dark-mode .filters {
            background: #1e293b;
            border-color: #334155;
        }

        .filter-group {
            flex: 1;
            min-width: 180px;
        }

        .filter-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--gray-600);
            margin-bottom: 0.3rem;
        }

        .filter-select, .filter-input {
            width: 100%;
            padding: 0.7rem;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            background: white;
            color: var(--dark);
            transition: all 0.2s;
        }

        body.dark-mode .filter-select,
        body.dark-mode .filter-input {
            background: #334155;
            border-color: #4b5563;
            color: #e5e7eb;
        }

        .filter-select:focus, .filter-input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .btn-filter {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(243,156,18,0.3);
        }

        .btn-reset {
            background: var(--gray-100);
            color: var(--gray-600);
            padding: 0.7rem 1.2rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        body.dark-mode .btn-reset {
            background: #334155;
            color: #94a3b8;
        }

        .btn-reset:hover {
            background: var(--gray-200);
            color: var(--primary);
        }

        /* Tableau */
        .table-card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--gray-200);
            overflow: hidden;
            transition: background 0.2s;
        }

        body.dark-mode .table-card {
            background: #1e293b;
            border-color: #334155;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 1rem 1.2rem;
            background: var(--gray-100);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--gray-600);
            border-bottom: 1px solid var(--gray-200);
        }

        body.dark-mode th {
            background: #1e293b;
            color: #94a3b8;
            border-bottom-color: #334155;
        }

        td {
            padding: 1rem 1.2rem;
            border-bottom: 1px solid var(--gray-100);
            font-size: 0.85rem;
            color: var(--gray-800);
        }

        body.dark-mode td {
            border-bottom-color: #334155;
            color: #cbd5e1;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.7rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-pending  { background: #fef3c7; color: #d97706; }
        .status-matched  { background: #dbeafe; color: #1d4ed8; }
        .status-restituted { background: #d1fae5; color: #065f46; }

        body.dark-mode .status-pending  { background: #422d0b; color: #fbbf24; }
        body.dark-mode .status-matched  { background: #1e3a5f; color: #60a5fa; }
        body.dark-mode .status-restituted { background: #0a3b2a; color: #34d399; }

        .btn-sm {
            padding: 0.4rem 0.9rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            transition: all 0.2s;
        }

        .btn-view {
            background: var(--gray-100);
            color: var(--primary);
        }

        body.dark-mode .btn-view {
            background: #334155;
            color: #fbbf24;
        }

        .btn-view:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.3rem;
            margin-top: 1.5rem;
        }

        .pagination a, .pagination span {
            padding: 0.3rem 0.8rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.85rem;
            background: white;
            color: var(--gray-600);
            border: 1px solid var(--gray-200);
        }

        body.dark-mode .pagination a, body.dark-mode .pagination span {
            background: #1e293b;
            border-color: #334155;
            color: #94a3b8;
        }

        .pagination .active span {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--gray-600);
        }

        body.dark-mode .empty-state {
            color: #94a3b8;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        @media (max-width: 1024px) {
            .sidebar { width: 100%; position: relative; height: auto; }
            .main { margin-left: 0; }
            .stats-grid { grid-template-columns: 1fr; }
            .filters { flex-direction: column; }
        }
    </style>
</head>
<body>

@php
    use App\Models\Perte;
    $pendingCount = Perte::where('statut','en_attente')->count();
    $stats = [
        'en_attente' => $documentsTrouves->where('statut','en_attente')->count(),
        'matches'    => $documentsTrouves->where('statut','matche')->count(),
        'restitues'  => $documentsTrouves->where('statut','restitue')->count(),
    ];
    $statut = request('statut');
    $search = request('search', '');
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
        <div class="agent-badge"><i class="bi bi-shield-check"></i> AGENT</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">PRINCIPAL</div>
        <a href="{{ route('agent.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('agent.dashboard', ['statut' => 'en_attente']) }}">
            <i class="bi bi-hourglass-split"></i> En attente
            @if($pendingCount > 0)
                <span class="nav-badge">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('agent.dashboard') }}">
            <i class="bi bi-files"></i> Toutes les pertes
        </a>

        <div class="nav-section">DOCUMENTS</div>
        <a href="{{ route('agent.documents-trouves.index') }}" class="active">
            <i class="bi bi-search-heart"></i> Documents trouvés
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
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="top-bar-left">
            <h1><i class="bi bi-search-heart me-2" style="color: var(--primary);"></i>Documents trouvés</h1>
            <p>Gérez les documents déclarés trouvés par les citoyens</p>
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
            <div class="icon-btn notification-btn" onclick="window.location.href='{{ route('agent.dashboard', ['statut' => 'en_attente']) }}'" title="Notifications">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if($pendingCount > 0)
                    <span class="notification-badge">{{ $pendingCount > 9 ? '9+' : $pendingCount }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Alertes -->
    @if(session('success'))
        <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
    @endif

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card pending">
            <div class="stat-value">{{ $stats['en_attente'] }}</div>
            <div class="stat-label">En attente de matching</div>
        </div>
        <div class="stat-card matched">
            <div class="stat-value">{{ $stats['matches'] }}</div>
            <div class="stat-label">Documents matchés</div>
        </div>
        <div class="stat-card restituted">
            <div class="stat-value">{{ $stats['restitues'] }}</div>
            <div class="stat-label">Documents restitués</div>
        </div>
    </div>

    <!-- Filtres -->
    <form method="GET" class="filters">
        <div class="filter-group">
            <div class="filter-label"><i class="bi bi-funnel"></i> Statut</div>
            <select name="statut" class="filter-select">
                <option value="">Tous</option>
                <option value="en_attente" {{ $statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="matche" {{ $statut == 'matche' ? 'selected' : '' }}>Matché</option>
                <option value="restitue" {{ $statut == 'restitue' ? 'selected' : '' }}>Restitué</option>
            </select>
        </div>
        <div class="filter-group">
            <div class="filter-label"><i class="bi bi-search"></i> Recherche</div>
            <input type="text" name="search" class="filter-input" placeholder="Nom, numéro, déclaration..." value="{{ $search }}">
        </div>
        <button type="submit" class="btn-filter"><i class="bi bi-filter"></i> Filtrer</button>
        <a href="{{ route('agent.documents-trouves.index') }}" class="btn-reset"><i class="bi bi-arrow-repeat"></i> Réinitialiser</a>
    </form>

    <!-- Tableau -->
    <div class="table-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>N° Déclaration</th>
                        <th>Type</th>
                        <th>Nom sur document</th>
                        <th>Date découverte</th>
                        <th>Lieu</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documentsTrouves as $doc)
                    <tr>
                        <td><strong>{{ $doc->numero_declaration }}</strong></td>
                        <td>{{ $doc->type_document }}</td>
                        <td>{{ $doc->nom_sur_document }} {{ $doc->prenom_sur_document }}</td>
                        <td>{{ $doc->date_decouverte->format('d/m/Y') }}</td>
                        <td>{{ $doc->lieu_decouverte }}</td>
                        <td>
                            @if($doc->statut == 'en_attente')
                                <span class="status-badge status-pending"><i class="bi bi-hourglass-split"></i> En attente</span>
                            @elseif($doc->statut == 'matche')
                                <span class="status-badge status-matched"><i class="bi bi-link-45deg"></i> Matché</span>
                            @elseif($doc->statut == 'restitue')
                                <span class="status-badge status-restituted"><i class="bi bi-check-circle"></i> Restitué</span>
                            @else
                                {{ $doc->statut }}
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('agent.documents-trouves.show', $doc->id) }}" class="btn-sm btn-view">
                                <i class="bi bi-eye"></i> Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                            <p>Aucun document trouvé pour le moment</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        {{ $documentsTrouves->appends(request()->query())->links() }}
    </div>
</div>

<script>
    // Horloge temps réel
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        const formatted = now.toLocaleDateString('fr-FR', options).replace(',', ' -');
        const dateTimeEl = document.getElementById('currentDateTime');
        if (dateTimeEl) dateTimeEl.innerHTML = formatted;
    }
    updateDateTime();
    setInterval(updateDateTime, 60000);

    // Mode sombre
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