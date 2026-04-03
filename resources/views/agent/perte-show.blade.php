<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dossier Perte {{ $perte->numero_declaration ?? '' }} | Agent e-Déclaration TG</title>
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
            animation: pulse 2s infinite;
        }

        .nav-badge.orange {
            background: #f39c12;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
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
            min-height: 100vh;
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
            animation: pulse 2s infinite;
        }

        /* Content */
        .content {
            padding: 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
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

        /* Page Header */
        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e3a5f;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .back-btn {
            background: #f1f5f9;
            color: #64748b;
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-btn:hover {
            background: #e2e8f0;
        }

        /* Status Banner */
        .status-banner {
            padding: 1.5rem 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 600;
        }

        .status-banner.pending {
            background: linear-gradient(135deg, #fff3cd, #ffe8a3);
            color: #856404;
            border-left: 4px solid #f39c12;
        }

        .status-banner.approved {
            background: linear-gradient(135deg, #d4edda, #b7e4c7);
            color: #155724;
            border-left: 4px solid #27ae60;
        }

        .status-banner.rejected {
            background: linear-gradient(135deg, #f8d7da, #f5c2c7);
            color: #721c24;
            border-left: 4px solid #e74c3c;
        }

        .status-icon {
            font-size: 2rem;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            align-items: start;
        }

        /* Card */
        .card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .card:last-child {
            margin-bottom: 0;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .card-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #f39c12, #f1c40f);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e3a5f;
        }

        /* Info Row */
        .info-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #1e3a5f;
        }

        /* Citizen Card */
        .citizen-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 1rem;
        }

        .citizen-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 800;
            margin: 0 auto 1rem;
            border: 3px solid rgba(255,255,255,0.3);
        }

        .citizen-name {
            font-size: 1.3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .citizen-email {
            text-align: center;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .citizen-info {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.2);
        }

        .citizen-info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
        }

        /* Action Buttons */
        .action-section {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 16px;
        }

        .action-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e3a5f;
            margin-bottom: 1.5rem;
        }

        .btn {
            width: 100%;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            margin-bottom: 1rem;
            text-decoration: none;
        }

        .btn-approve {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
        }

        .btn-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(39, 174, 96, 0.4);
        }

        .btn-reject {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        }

        .btn-reject:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(231, 76, 60, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            border-color: #94a3b8;
        }

        /* Rejection Box */
        .rejection-box {
            background: #fff3cd;
            border: 2px solid #f39c12;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .rejection-box h4 {
            color: #856404;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .rejection-box p {
            color: #664d03;
            line-height: 1.6;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.65rem;
            top: 0.3rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #f39c12;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #f39c12;
        }

        .timeline-date {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0.3rem;
        }

        .timeline-text {
            font-weight: 600;
            color: #1e3a5f;
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
            padding: 2rem;
        }

        .modal.active {
            display: flex;
            animation: fadeIn 0.2s;
        }

        .modal-content {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .modal-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1e3a5f;
        }

        .modal-close {
            background: #f1f5f9;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            font-size: 1.4rem;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: #e2e8f0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.6rem;
        }

        .form-textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Nunito', sans-serif;
            min-height: 150px;
            resize: vertical;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #e74c3c;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-modal {
            flex: 1;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-modal-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-modal-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        /* File chips */
        .file-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f1f5f9;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            padding: 0.5rem 0.9rem;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            color: #2563ff;
            transition: all 0.18s;
            margin: 0.25rem 0.25rem 0 0;
        }

        .file-chip:hover {
            background: #eff6ff;
            border-color: #2563ff;
        }

        .file-chip svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .content {
                padding: 1rem;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .info-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

@php
    $pendingCount = \App\Models\Perte::where('statut','en_attente')->count();
    $statut = $perte->statut;
    $sMap = [
        'en_attente' => ['label'=>'En attente', 'class'=>'pending', 'icon'=>'⏳'],
        'validee'    => ['label'=>'Validée',    'class'=>'approved', 'icon'=>'✅'],
        'rejetee'    => ['label'=>'Rejetée',    'class'=>'rejected', 'icon'=>'❌'],
    ];
    $s = $sMap[$statut] ?? $sMap['en_attente'];
    $docIcons = ['Passeport'=>'🛂',"Carte d'identité (CNI)"=>'🪪','Permis de conduire'=>'🚗',"Carte d'électeur"=>'🗳️','Acte de naissance'=>'📋','Certificat de nationalité'=>'📜'];
    $docIcon = $docIcons[$perte->type_piece] ?? '📄';
    $ref = $perte->numero_declaration ?? 'DL-'.str_pad($perte->id, 5, '0', STR_PAD_LEFT);
    $initials = strtoupper(substr(auth()->user()->first_name ?? auth()->user()->name ?? 'A', 0,1) . substr(auth()->user()->last_name ?? '', 0,1));
@endphp

<!-- SIDEBAR -->
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
            @if($pendingCount > 0)
                <span class="nav-badge">{{ $pendingCount }}</span>
            @endif
        </a>

        <a href="{{ route('agent.documents-trouves.index') }}" class="{{ request()->routeIs('agent.documents-trouves.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Documents Trouvés
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

<!-- MAIN -->
<div class="main">
    <header class="top-bar">
        <div class="top-bar-left">
            <h1>📄 Détails de la déclaration</h1>
            <p>{{ $ref }} • {{ $perte->type_piece }}</p>
        </div>
        <div class="top-bar-right">
            <div class="notification-badge" onclick="window.location.href='{{ route('agent.dashboard', ['statut' => 'en_attente']) }}'">
                🔔
                @php
                    $totalNotif = $pendingCount;
                @endphp
                @if($totalNotif > 0)
                    <span class="notification-count">{{ $totalNotif }}</span>
                @endif
            </div>
        </div>
    </header>

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

        <!-- Page Header with Back Button -->
        <div class="page-header">
            <h1>{{ $docIcon }} {{ $perte->type_piece }}</h1>
            <a href="{{ route('agent.dashboard') }}" class="back-btn">
                ← Retour
            </a>
        </div>

        <!-- Status Banner -->
        <div class="status-banner {{ $s['class'] }}">
            <div class="status-icon">{{ $s['icon'] }}</div>
            <div>
                <div style="font-size: 1.2rem;">
                    Statut : {{ $s['label'] }}
                </div>
                @if($perte->numero_declaration)
                    <div style="font-size: 0.9rem; opacity: 0.8;">
                        N° {{ $perte->numero_declaration }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">

            <!-- Left Column -->
            <div>

                <!-- Déclarant -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">👤</div>
                        <div class="card-title">Informations du déclarant</div>
                    </div>

                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">Nom</div>
                            <div class="info-value">{{ $perte->last_name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Prénom</div>
                            <div class="info-value">{{ $perte->first_name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Téléphone</div>
                            <div class="info-value">{{ $perte->contact }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $perte->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Document perdu -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">📄</div>
                        <div class="card-title">Informations du document perdu</div>
                    </div>

                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">Type de pièce</div>
                            <div class="info-value">{{ $perte->type_piece }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Numéro de pièce</div>
                            <div class="info-value">{{ $perte->numero_piece ?? 'Non renseigné' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date de perte</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Lieu de perte</div>
                            <div class="info-value">{{ $perte->lieu_perte }}</div>
                        </div>
                        @if($perte->date_delivrance)
                        <div class="info-item">
                            <div class="info-label">Date de délivrance</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($perte->date_delivrance)->format('d/m/Y') }}</div>
                        </div>
                        @endif
                        @if($perte->autorite_delivrance)
                        <div class="info-item">
                            <div class="info-label">Autorité de délivrance</div>
                            <div class="info-value">{{ $perte->autorite_delivrance }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Circonstances -->
                @if($perte->circonstances)
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">📝</div>
                        <div class="card-title">Circonstances de la perte</div>
                    </div>
                    <div class="info-item full-width">
                        <div class="info-value" style="line-height: 1.6;">{{ $perte->circonstances }}</div>
                    </div>
                </div>
                @endif

                <!-- Pièces jointes -->
                @if($perte->copie_piece || $perte->declaration_vol || $perte->document_complementaire)
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">📎</div>
                        <div class="card-title">Pièces jointes</div>
                    </div>
                    <div class="info-item full-width">
                        <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                            @if($perte->copie_piece)
                            <a href="{{ Storage::url($perte->copie_piece) }}" target="_blank" class="file-chip">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Copie de la pièce
                            </a>
                            @endif
                            @if($perte->declaration_vol)
                            <a href="{{ Storage::url($perte->declaration_vol) }}" target="_blank" class="file-chip">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Déclaration de vol
                            </a>
                            @endif
                            @if($perte->document_complementaire)
                            <a href="{{ Storage::url($perte->document_complementaire) }}" target="_blank" class="file-chip">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Doc. complémentaire
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Rejection Reason -->
                @if($perte->statut === 'rejetee' && $perte->motif_rejet)
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon">⚠️</div>
                            <div class="card-title">Motif du rejet</div>
                        </div>
                        <div class="rejection-box">
                            <p>{{ $perte->motif_rejet }}</p>
                        </div>
                    </div>
                @endif

                <!-- Timeline -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">📅</div>
                        <div class="card-title">Chronologie</div>
                    </div>

                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-date">{{ \Carbon\Carbon::parse($perte->created_at)->format('d/m/Y à H:i') }}</div>
                            <div class="timeline-text">Déclaration soumise par le citoyen</div>
                        </div>

                        @if($perte->validated_at)
                            <div class="timeline-item">
                                <div class="timeline-date">{{ \Carbon\Carbon::parse($perte->validated_at)->format('d/m/Y à H:i') }}</div>
                                <div class="timeline-text">
                                    @if($perte->statut === 'validee')
                                        ✅ Déclaration validée
                                    @else
                                        ❌ Déclaration rejetée
                                    @endif
                                    @if($perte->validator)
                                        par {{ $perte->validator->name }}
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <!-- Citizen Info -->
                <div class="citizen-card">
                    <div class="citizen-avatar">
                        {{ strtoupper(substr($perte->first_name ?? $perte->user->name, 0, 1)) }}
                    </div>
                    <div class="citizen-name">{{ $perte->first_name }} {{ $perte->last_name }}</div>
                    <div class="citizen-email">{{ $perte->email }}</div>
                    
                    <div class="citizen-info">
                        <div class="citizen-info-item">
                            <span>📱 Téléphone</span>
                            <strong>{{ $perte->contact }}</strong>
                        </div>
                        @if($perte->user && $perte->user->address)
                            <div class="citizen-info-item">
                                <span>📍 Adresse</span>
                                <strong>{{ $perte->user->address }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                @if($perte->statut === 'en_attente')
                    <div class="action-section">
                        <div class="action-title">🎯 Actions disponibles</div>
                        
                        <form method="POST" action="{{ route('agent.perte.valider', $perte->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-approve" onclick="return confirm('Êtes-vous sûr de vouloir valider cette déclaration ?')">
                                ✅ Valider la déclaration
                            </button>
                        </form>

                        <button class="btn btn-reject" onclick="openRejectModal()">
                            ❌ Rejeter la déclaration
                        </button>

                        <a href="{{ route('agent.dashboard') }}" class="btn btn-secondary">
                            ← Retour à la liste
                        </a>
                    </div>
                @else
                    <div class="action-section">
                        <div class="action-title">📊 Statut final</div>
                        <p style="margin-bottom: 1rem; color: #64748b; line-height: 1.6;">
                            Cette déclaration a été 
                            @if($perte->statut === 'validee')
                                <strong style="color: #27ae60;">validée</strong>
                            @else
                                <strong style="color: #e74c3c;">rejetée</strong>
                            @endif
                            le {{ \Carbon\Carbon::parse($perte->validated_at)->format('d/m/Y à H:i') }}.
                        </p>
                        <a href="{{ route('agent.dashboard') }}" class="btn btn-secondary">
                            ← Retour à la liste
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal" id="rejectModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">❌ Rejeter la déclaration</div>
            <button class="modal-close" onclick="closeRejectModal()">×</button>
        </div>
        <form method="POST" action="{{ route('agent.perte.rejeter', $perte->id) }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Motif du rejet *</label>
                <textarea class="form-textarea" name="motif_rejet" required placeholder="Expliquez clairement la raison du rejet pour que le citoyen puisse comprendre et corriger si nécessaire (minimum 10 caractères)..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn-modal btn-modal-danger">Confirmer le rejet</button>
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeRejectModal()">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Reject Modal
    function openRejectModal() {
        document.getElementById('rejectModal').classList.add('active');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('active');
    }

    // Close modal on outside click
    document.getElementById('rejectModal').addEventListener('click', (e) => {
        if (e.target.id === 'rejectModal') {
            closeRejectModal();
        }
    });

    // Auto-hide alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
</script>

</body>
</html>