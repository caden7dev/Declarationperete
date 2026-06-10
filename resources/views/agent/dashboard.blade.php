<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Agent - e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            font-family: 'Nunito', sans-serif;
        }

        body { 
            display: flex; 
            min-height: 100vh; 
            background: #f5f7fa;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: white;
            box-shadow: 2px 0 15px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 10;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid #e8eef5;
        }

        .sidebar-header h2 { 
            font-size: 1.3rem;
            font-weight: 800;
            display: flex; 
            align-items: center; 
            gap: 0.8rem;
            color: #1e3a5f;
        }

        .agent-badge {
            background: linear-gradient(135deg, #f39c12, #f1c40f);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-top: 0.5rem;
            display: inline-block;
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
            position: relative;
        }

        .sidebar-nav a:hover {
            background: #f1f5f9;
            color: #f39c12;
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.1), rgba(241, 196, 15, 0.05));
            color: #f39c12;
            font-weight: 700;
            border: 2px solid #f39c12;
        }

        .sidebar-nav a svg {
            width: 20px;
            height: 20px;
        }

        /* Badge de notification dans sidebar */
        .nav-badge {
            margin-left: auto;
            background: #e74c3c;
            color: white;
            padding: 0.2rem 0.6rem;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 700;
            animation: pulse 1.5s infinite;
        }

        .nav-badge.orange {
            background: #f39c12;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.9; }
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

        /* Main */
        .main {
            margin-left: 280px;
            flex: 1;
            background: #f5f7fa;
        }

        /* Top Bar */
        .top-bar {
            background: white;
            padding: 1.5rem 2.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar-left h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e3a5f;
            margin-bottom: 0.3rem;
        }

        .top-bar-left p {
            color: #64748b;
            font-size: 0.95rem;
        }

        .top-bar-right {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .notification-badge {
            position: relative;
            background: #f8f9fa;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .notification-badge:hover {
            background: #e9ecef;
            transform: scale(1.05);
        }

        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            font-weight: 700;
            animation: pulse 1.5s infinite;
        }

        /* Content */
        .content {
            padding: 2.5rem;
        }

        /* Alert */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            animation: slideDown 0.3s;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #27ae60;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #e74c3c;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.8rem;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 2px solid transparent;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }

        .stat-card.total::before { background: #3498db; }
        .stat-card.pending::before { background: #f39c12; }
        .stat-card.approved::before { background: #27ae60; }
        .stat-card.rejected::before { background: #e74c3c; }
        .stat-card.mine::before { background: #9b59b6; }
        .stat-card.found::before { background: #16a085; }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-card.total .stat-icon { background: rgba(52, 152, 219, 0.1); }
        .stat-card.pending .stat-icon { background: rgba(243, 156, 18, 0.1); }
        .stat-card.approved .stat-icon { background: rgba(39, 174, 96, 0.1); }
        .stat-card.rejected .stat-icon { background: rgba(231, 76, 60, 0.1); }
        .stat-card.mine .stat-icon { background: rgba(155, 89, 182, 0.1); }
        .stat-card.found .stat-icon { background: rgba(22, 160, 133, 0.1); }

        .stat-value {
            font-size: 2.2rem;
            font-weight: 800;
            color: #1e3a5f;
            margin-bottom: 0.3rem;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Section Headers */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1e3a5f;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .section-action {
            background: linear-gradient(135deg, #f39c12, #f1c40f);
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s;
            font-size: 0.9rem;
            display: inline-block;
        }

        .section-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(243, 156, 18, 0.3);
        }

        .section-action.blue {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }

        .section-action.green {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
        }

        .section-action.red {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        /* Documents Trouvés Card */
        .found-docs-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .found-doc-item {
            display: flex;
            gap: 1.5rem;
            padding: 1.2rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            background: #f8f9fa;
            border-left: 4px solid #16a085;
            transition: all 0.2s;
        }

        .found-doc-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .found-doc-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #16a085, #1abc9c);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            flex-shrink: 0;
        }

        .found-doc-info {
            flex: 1;
        }

        .found-doc-type {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e3a5f;
            margin-bottom: 0.3rem;
        }

        .found-doc-details {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .found-doc-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.85rem;
            color: #94a3b8;
            flex-wrap: wrap;
        }

        .found-doc-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            justify-content: center;
            min-width: 120px;
        }

        .btn-found {
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
            text-align: center;
            white-space: nowrap;
        }

        .btn-found-primary {
            background: #16a085;
            color: white;
        }

        .btn-found-primary:hover {
            background: #138d75;
            transform: translateY(-2px);
        }

        .btn-found-warning {
            background: #f39c12;
            color: white;
        }

        .btn-found-warning:hover {
            background: #e67e22;
            transform: translateY(-2px);
        }

        .btn-found-success {
            background: #27ae60;
            color: white;
        }

        .btn-found-success:hover {
            background: #229954;
            transform: translateY(-2px);
        }

        .match-badge {
            background: #27ae60;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
            background: white;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .filter-tab:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .filter-tab.active {
            background: #f39c12;
            color: white;
            border-color: #f39c12;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #94a3b8;
        }

        .empty-state-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination a, .pagination span {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            background: white;
            color: #64748b;
            text-decoration: none;
            border: 1px solid #e2e8f0;
        }

        .pagination a:hover {
            background: #f1f5f9;
        }

        .pagination .active span {
            background: #f39c12;
            color: white;
            border-color: #f39c12;
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

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .found-doc-item {
                flex-direction: column;
            }

            .found-doc-actions {
                flex-direction: row;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>
                <span>🇹🇬</span> 
                e-Déclaration TG
            </h2>
            <div class="agent-badge">👮 AGENT</div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('agent.dashboard') }}" class="{{ request()->routeIs('agent.dashboard') && !request('statut') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('agent.dashboard', ['statut' => 'en_attente']) }}" class="{{ request('statut') == 'en_attente' ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Déclarations Perte
                @if($stats['en_attente'] > 0)
                    <span class="nav-badge">{{ $stats['en_attente'] }}</span>
                @endif
            </a>

            <a href="{{ route('agent.documents-trouves.index') }}" class="{{ request()->routeIs('agent.documents-trouves.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Documents Trouvés
                @if($statsDocumentsTrouves['en_attente'] > 0)
                    <span class="nav-badge orange">{{ $statsDocumentsTrouves['en_attente'] }}</span>
                @endif
            </a>

            <a href="{{ route('agent.dashboard', ['statut' => 'validee']) }}" class="{{ request('statut') == 'validee' ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Validées
            </a>

            <a href="{{ route('agent.dashboard', ['statut' => 'rejetee']) }}" class="{{ request('statut') == 'rejetee' ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Rejetées
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">
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
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <h1>🎯 Dashboard Agent</h1>
                <p>Gérez les déclarations et documents trouvés</p>
            </div>
            <div class="top-bar-right">
                <div class="notification-badge" onclick="window.location.href='{{ route('agent.dashboard', ['statut' => 'en_attente']) }}'">
                    🔔
                    @php
                        $totalNotif = $stats['en_attente'] + $statsDocumentsTrouves['en_attente'];
                    @endphp
                    @if($totalNotif > 0)
                        <span class="notification-count">{{ $totalNotif }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <span>❌</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card total" onclick="window.location.href='{{ route('agent.dashboard') }}'">
                    <div class="stat-header">
                        <div class="stat-icon">📊</div>
                    </div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-label">Déclarations Perte</div>
                </div>

                <div class="stat-card pending" onclick="window.location.href='{{ route('agent.dashboard', ['statut' => 'en_attente']) }}'">
                    <div class="stat-header">
                        <div class="stat-icon">⏳</div>
                    </div>
                    <div class="stat-value">{{ $stats['en_attente'] }}</div>
                    <div class="stat-label">Perte en attente</div>
                </div>

                <div class="stat-card found" onclick="window.location.href='{{ route('agent.documents-trouves.index') }}'">
                    <div class="stat-header">
                        <div class="stat-icon">📦</div>
                    </div>
                    <div class="stat-value">{{ $statsDocumentsTrouves['total'] }}</div>
                    <div class="stat-label">Documents Trouvés</div>
                </div>

                <div class="stat-card approved" onclick="window.location.href='{{ route('agent.dashboard', ['statut' => 'validee']) }}'">
                    <div class="stat-header">
                        <div class="stat-icon">✅</div>
                    </div>
                    <div class="stat-value">{{ $stats['validees'] }}</div>
                    <div class="stat-label">Validées</div>
                </div>

                <div class="stat-card rejected" onclick="window.location.href='{{ route('agent.dashboard', ['statut' => 'rejetee']) }}'">
                    <div class="stat-header">
                        <div class="stat-icon">❌</div>
                    </div>
                    <div class="stat-value">{{ $stats['rejetees'] }}</div>
                    <div class="stat-label">Rejetées</div>
                </div>

                <div class="stat-card mine">
                    <div class="stat-header">
                        <div class="stat-icon">👤</div>
                    </div>
                    <div class="stat-value">{{ $stats['traitees_par_moi'] }}</div>
                    <div class="stat-label">Traitées par moi</div>
                </div>
            </div>

            <!-- 🔥 FILTER TABS -->
            <div class="filter-tabs">
                <a href="{{ route('agent.dashboard') }}" class="filter-tab {{ !request('statut') ? 'active' : '' }}">Toutes</a>
                <a href="{{ route('agent.dashboard', ['statut' => 'en_attente']) }}" class="filter-tab {{ request('statut') == 'en_attente' ? 'active' : '' }}">⏳ En attente</a>
                <a href="{{ route('agent.dashboard', ['statut' => 'validee']) }}" class="filter-tab {{ request('statut') == 'validee' ? 'active' : '' }}">✅ Validées</a>
                <a href="{{ route('agent.dashboard', ['statut' => 'rejetee']) }}" class="filter-tab {{ request('statut') == 'rejetee' ? 'active' : '' }}">❌ Rejetées</a>
            </div>

            <!-- 🔥 SECTION DÉCLARATIONS DE PERTE -->
            <div class="section-header">
                <div class="section-title">
                    📋 Déclarations de Perte
                    @if($stats['en_attente'] > 0 && !request('statut'))
                        <span class="match-badge" style="background: #f39c12;">{{ $stats['en_attente'] }} en attente</span>
                    @endif
                </div>
            </div>

            <div class="found-docs-card">
                @if($pertes->count() > 0)
                    @foreach($pertes as $perte)
                        @php
                            $statusColor = '#f39c12';
                            $statusIcon = '⏳';
                            $statusText = 'En attente';
                            
                            if($perte->statut == 'validee') {
                                $statusColor = '#27ae60';
                                $statusIcon = '✅';
                                $statusText = 'Validée';
                            } elseif($perte->statut == 'rejetee') {
                                $statusColor = '#e74c3c';
                                $statusIcon = '❌';
                                $statusText = 'Rejetée';
                            }
                            
                            $docIcon = '📄';
                            if($perte->type_piece == 'CNI') $docIcon = '🪪';
                            elseif($perte->type_piece == 'Passeport') $docIcon = '🛂';
                            elseif($perte->type_piece == 'Permis de conduire') $docIcon = '🚗';
                        @endphp
                        
                        <div class="found-doc-item" style="border-left-color: {{ $statusColor }};">
                            <div class="found-doc-icon" style="background: linear-gradient(135deg, {{ $statusColor }}, {{ $statusColor }}dd);">
                                {{ $docIcon }}
                            </div>
                            <div class="found-doc-info">
                                <div class="found-doc-type">
                                    {{ $perte->type_piece }}
                                    @if($perte->numero_piece)
                                        <small style="color: #64748b;">N°{{ $perte->numero_piece }}</small>
                                    @endif
                                </div>
                                <div class="found-doc-details">
                                    <strong>{{ $perte->first_name }} {{ $perte->last_name }}</strong>
                                </div>
                                <div class="found-doc-meta">
                                    <span>📍 {{ $perte->lieu_perte }}</span>
                                    <span>📅 Perte le {{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}</span>
                                    <span>📞 {{ $perte->contact }}</span>
                                    @if($perte->numero_declaration)
                                        <span>🔢 {{ $perte->numero_declaration }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="found-doc-actions">
                                <a href="{{ route('agent.perte.show', $perte->id) }}" class="btn-found btn-found-primary">
                                    👁️ Voir détails
                                </a>
                                <span class="match-badge" style="background: {{ $statusColor }};">
                                    {{ $statusIcon }} {{ $statusText }}
                                </span>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    @if(method_exists($pertes, 'links'))
                        <div class="pagination">
                            {{ $pertes->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <p><strong>Aucune déclaration de perte trouvée</strong></p>
                        <p style="font-size: 0.9rem; margin-top: 0.5rem;">
                            @if(request('statut'))
                                Aucune déclaration avec le statut "{{ request('statut') }}"
                            @else
                                Les nouvelles déclarations apparaîtront ici
                            @endif
                        </p>
                    </div>
                @endif
            </div>

            <!-- 🔥 SECTION DOCUMENTS TROUVÉS -->
            <div class="section-header" style="margin-top: 2rem;">
                <div class="section-title">
                    📦 Documents Trouvés Récents
                    @if($statsDocumentsTrouves['en_attente'] > 0)
                        <span class="match-badge" style="background: #16a085;">{{ $statsDocumentsTrouves['en_attente'] }} nouveau(x)</span>
                    @endif
                </div>
                <a href="{{ route('agent.documents-trouves.index') }}" class="section-action blue">
                    Voir tout →
                </a>
            </div>

            <div class="found-docs-card">
                @if($derniersTrouves->count() > 0)
                    @foreach($derniersTrouves as $docTrouve)
                        @php
                            $docStatusColor = '#16a085';
                            $docStatusText = 'En attente';
                            $docStatusIcon = '⏳';
                            
                            if($docTrouve->statut == 'matche') {
                                $docStatusColor = '#f39c12';
                                $docStatusText = 'Matché';
                                $docStatusIcon = '🔗';
                            } elseif($docTrouve->statut == 'restitue') {
                                $docStatusColor = '#27ae60';
                                $docStatusText = 'Restitué';
                                $docStatusIcon = '✅';
                            }
                        @endphp
                        
                        <div class="found-doc-item" style="border-left-color: {{ $docStatusColor }};">
                            <div class="found-doc-icon" style="background: linear-gradient(135deg, {{ $docStatusColor }}, {{ $docStatusColor }}dd);">
                                📄
                            </div>
                            <div class="found-doc-info">
                                <div class="found-doc-type">
                                    {{ $docTrouve->type_document }}
                                    @if($docTrouve->numero_document)
                                        - N°{{ $docTrouve->numero_document }}
                                    @endif
                                </div>
                                <div class="found-doc-details">
                                    @if($docTrouve->nom_sur_document || $docTrouve->prenom_sur_document)
                                        <strong>{{ $docTrouve->prenom_sur_document }} {{ $docTrouve->nom_sur_document }}</strong>
                                    @endif
                                </div>
                                <div class="found-doc-meta">
                                    <span>📍 {{ $docTrouve->lieu_decouverte }}</span>
                                    <span>📅 Trouvé le {{ \Carbon\Carbon::parse($docTrouve->date_decouverte)->format('d/m/Y') }}</span>
                                    <span>👤 {{ $docTrouve->nom_declarant }}</span>
                                </div>
                            </div>
                            <div class="found-doc-actions">
                                <a href="{{ route('agent.documents-trouves.show', $docTrouve->id) }}" class="btn-found btn-found-primary">
                                    👁️ Voir & Matcher
                                </a>
                                <span class="match-badge" style="background: {{ $docStatusColor }};">
                                    {{ $docStatusIcon }} {{ $docStatusText }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <p><strong>Aucun document trouvé récemment</strong></p>
                        <p style="font-size: 0.9rem; margin-top: 0.5rem;">
                            Les citoyens peuvent déclarer les documents qu'ils trouvent sur la plateforme
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.3s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Stocker les notifications vues dans sessionStorage
        function markNotificationsAsViewed() {
            const totalNotif = {{ $totalNotif ?? 0 }};
            if (totalNotif > 0) {
                sessionStorage.setItem('notifications_viewed_' + new Date().toDateString(), 'true');
            }
        }

        // Vérifier si les notifications doivent être affichées
        function shouldShowNotifications() {
            const today = new Date().toDateString();
            const viewed = sessionStorage.getItem('notifications_viewed_' + today);
            return !viewed;
        }

        // Cacher le badge de notification si on a cliqué
        document.querySelectorAll('.notification-badge, .nav-badge, .filter-tab, .stat-card').forEach(el => {
            if (el) {
                el.addEventListener('click', function() {
                    markNotificationsAsViewed();
                });
            }
        });

        // Log activité
        console.log('✅ Dashboard Agent avec Documents Trouvés et Pertes chargé');
    </script>

</body>
</html>