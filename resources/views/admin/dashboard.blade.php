@extends('layouts.app')

@section('title', 'Tableau de bord - Administrateur Général')

@section('content')
<style>
    /* ===== COULEURS TOGO AVEC DOMINANTE VERTE POUR L'ADMIN ===== */
    :root {
        --primary: #1a7a3a;          /* Vert foncé (Togo) */
        --primary-dark: #0f5c2a;
        --primary-light: #4caf50;
        --accent: #f39c12;           /* Jaune pour les accents */
        --accent-dark: #e67e22;
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
        --red-togo: #d21034;
    }

    /* ===== STYLES GLOBAUX ===== */
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', sans-serif;
        min-height: 100vh;
        display: flex;
        background: #f5f7fa;
        transition: background 0.2s ease;
    }

    body.dark-mode {
        background: #0f172a;
    }

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
        border-right: 1px solid rgba(26,122,58,0.15);
        box-shadow: 2px 0 20px rgba(0,0,0,0.05);
        transition: background 0.2s, border-color 0.2s;
        border-top: 4px solid var(--red-togo); /* Bande rouge distinctive */
    }

    body.dark-mode .sidebar {
        background: rgba(20,20,30,0.98);
        border-right-color: rgba(26,122,58,0.3);
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

    .admin-badge {
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
        border: 1px solid var(--accent);
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
        position: relative;
    }

    body.dark-mode .sidebar-nav a {
        color: #9ca3af;
    }

    .sidebar-nav a i {
        width: 20px;
        font-size: 1.1rem;
    }

    .sidebar-nav a:hover {
        background: rgba(26,122,58,0.08);
        color: var(--primary);
    }

    body.dark-mode .sidebar-nav a:hover {
        background: rgba(26,122,58,0.2);
    }

    .sidebar-nav a.active {
        background: linear-gradient(135deg, rgba(26,122,58,0.12), rgba(76,175,80,0.08));
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

    .nav-badge.orange {
        background: var(--accent);
    }
    .nav-badge.green {
        background: var(--success);
    }
    .nav-badge.blue {
        background: var(--info);
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
        border-left: 6px solid var(--red-togo); /* touche rouge */
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
        background: rgba(26,122,58,0.08);
    }

    .icon-btn:hover svg {
        stroke: var(--primary);
    }

    /* ===== STATISTIQUES ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
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
        text-align: center;
        border-bottom: 4px solid var(--gray-200);
    }

    body.dark-mode .stat-card {
        background: #1e293b;
        border-color: #334155;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        border-color: var(--primary);
        border-bottom-color: var(--accent);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .stat-card .stat-icon {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
        color: var(--primary);
    }

    body.dark-mode .stat-card .stat-icon {
        color: #4caf50;
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
        font-size: 0.7rem;
        color: var(--gray-600);
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* ===== CARTES ===== */
    .card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 16px;
        transition: background 0.2s;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-top: 4px solid var(--accent); /* touche jaune */
    }

    body.dark-mode .card {
        background: #1e293b;
        border-color: #334155;
    }

    .card-header {
        background: white;
        border-bottom: 2px solid #f1f5f9;
        padding: 1rem 1.5rem;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--primary-dark);
    }

    body.dark-mode .card-header {
        background: #1e293b;
        border-bottom-color: #334155;
        color: #e5e7eb;
    }

    .card-body {
        padding: 1.5rem;
    }

    .table {
        margin-bottom: 0;
    }

    body.dark-mode .table {
        color: #e5e7eb;
    }

    .table thead th {
        border-top: none;
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--primary-light);
    }

    body.dark-mode .table thead th {
        background: #1e293b;
        color: #94a3b8;
        border-bottom-color: #4caf50;
    }

    .table tbody tr:hover {
        background: #f8fafc;
    }

    body.dark-mode .table tbody tr:hover {
        background: #334155;
    }

    .badge {
        padding: 0.4rem 0.8rem;
        font-weight: 600;
        border-radius: 50px;
    }

    .badge.bg-danger {
        background: var(--danger) !important;
    }
    .badge.bg-warning {
        background: var(--accent) !important;
        color: #2c3e50 !important;
    }
    .badge.bg-primary {
        background: var(--primary) !important;
    }
    .badge.bg-success {
        background: var(--success) !important;
    }
    .badge.bg-secondary {
        background: #6c757d !important;
    }

    .btn-sm {
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }
    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(26,122,58,0.3);
    }
    .btn-secondary {
        background: var(--gray-200);
        color: var(--gray-600);
    }
    .btn-secondary:hover {
        background: var(--gray-600);
        color: white;
    }
    .btn-danger {
        background: var(--danger);
        color: white;
    }
    .btn-danger:hover {
        background: #c0392b;
        transform: translateY(-2px);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width:1200px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width:1024px) {
        .sidebar {
            width: 100%;
            position: relative;
            height: auto;
        }
        .main {
            margin-left: 0;
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width:640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- ===== SIDEBAR ===== -->
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
        <div class="admin-badge">
            <i class="bi bi-shield-lock"></i> ADMIN
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">PRINCIPAL</div>
        <a href="{{ route('admin.dashboard') }}" class="active">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}">
            <i class="bi bi-people"></i> Utilisateurs
        </a>
        <a href="{{ route('admin.types-pieces.index') }}">
            <i class="bi bi-upc-scan"></i> Types de pièces
        </a>
        <a href="{{ route('admin.roles.index') }}">
            <i class="bi bi-shield-check"></i> Rôles & droits
        </a>

        <div class="nav-section">DÉCLARATIONS</div>
        <a href="{{ route('admin.dashboard') }}">
            <i class="bi bi-files"></i> Toutes les pertes
            <span class="nav-badge">{{ $stats['pertes'] ?? 0 }}</span>
        </a>
        <a href="#">
            <i class="bi bi-search-heart"></i> Documents trouvés
            <span class="nav-badge orange">{{ $stats['documents_trouves'] ?? 0 }}</span>
        </a>

        <div class="nav-section">ANALYTIQUES</div>
        <a href="#">
            <i class="bi bi-graph-up"></i> Statistiques
        </a>
        <a href="#">
            <i class="bi bi-file-text"></i> Rapports
        </a>

        <div class="nav-section">PARAMÈTRES</div>
        <a href="{{ route('admin.profile') }}">
            <i class="bi bi-person-gear"></i> Mon profil
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Voulez-vous vraiment vous déconnecter ?')">
            @csrf
            <button type="submit" class="logout-link">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Déconnecter
            </button>
        </form>
    </div>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="main">
    <!-- TOP BAR -->
    <div class="top-bar">
        <div class="top-bar-left">
            <h1><i class="bi bi-speedometer2 me-2" style="color: var(--primary);"></i>Dashboard Administrateur</h1>
            <p>Gérez les utilisateurs, les types de pièces et les déclarations</p>
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
        </div>
    </div>

    <!-- STATISTIQUES GÉNÉRALES -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <div class="stat-value">{{ $stats['users'] ?? 0 }}</div>
            <div class="stat-label">Utilisateurs</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-upc-scan"></i></div>
            <div class="stat-value">{{ $stats['types_pieces'] ?? 0 }}</div>
            <div class="stat-label">Types de pièces</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-shield-check"></i></div>
            <div class="stat-value">{{ $stats['roles'] ?? 0 }}</div>
            <div class="stat-label">Rôles</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-files"></i></div>
            <div class="stat-value">{{ $stats['pertes'] ?? 0 }}</div>
            <div class="stat-label">Déclarations</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-search-heart"></i></div>
            <div class="stat-value">{{ $stats['documents_trouves'] ?? 0 }}</div>
            <div class="stat-label">Documents trouvés</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
            <div class="stat-value">{{ ($stats['restitue'] ?? 0) + ($stats['validees'] ?? 0) }}</div>
            <div class="stat-label">Traités</div>
        </div>
    </div>

    <!-- STATISTIQUES DÉTAILLÉES (statuts des déclarations) -->
    <div class="stats-grid" style="margin-top: -1rem;">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-clock-history"></i></div>
            <div class="stat-value">{{ $stats['en_attente'] ?? 0 }}</div>
            <div class="stat-label">En attente</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-arrow-repeat"></i></div>
            <div class="stat-value">{{ $stats['en_cours'] ?? 0 }}</div>
            <div class="stat-label">En cours</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-link-45deg"></i></div>
            <div class="stat-value">{{ $stats['correspondance_trouvee'] ?? 0 }}</div>
            <div class="stat-label">Correspondance trouvée</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
            <div class="stat-value">{{ $stats['restitue'] ?? 0 }}</div>
            <div class="stat-label">Restitués</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-emoji-frown"></i></div>
            <div class="stat-value">{{ $stats['non_retrouve'] ?? 0 }}</div>
            <div class="stat-label">Non retrouvés</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-value">{{ ($stats['validees'] ?? 0) + ($stats['rejetees'] ?? 0) }}</div>
            <div class="stat-label">Validées / Rejetées</div>
        </div>
    </div>

    <!-- DERNIERS UTILISATEURS -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <span><i class="bi bi-people me-2"></i>Derniers utilisateurs</span>
            <div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary me-2">
                    <i class="bi bi-plus-lg"></i> Ajouter
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">
                    Voir tout
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Inscrit le</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="ps-4 fw-medium">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Administrateur</span>
                                @elseif($user->role === 'agent')
                                    <span class="badge bg-warning text-dark">Agent</span>
                                @else
                                    <span class="badge bg-primary">Citoyen</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Aucun utilisateur</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TYPES DE PIÈCES + GRAPHIQUE -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <span><i class="bi bi-upc-scan me-2"></i>Types de pièces</span>
                    <a href="{{ route('admin.types-pieces.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg"></i> Ajouter
                    </a>
                </div>
                <div class="card-body">
                    @if($typesPieces->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                @foreach($typesPieces as $type)
                                <tr>
                                    <td>{{ $type->nom }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.types-pieces.edit', $type) }}" class="btn btn-sm btn-secondary me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.types-pieces.destroy', $type) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce type de pièce ?')">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">Aucun type de pièce enregistré</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <span><i class="bi bi-graph-up me-2"></i>Évolution des déclarations</span>
                </div>
                <div class="card-body">
                    <canvas id="statsChart" style="max-height: 280px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== SCRIPTS ===== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Horloge
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        const formatted = now.toLocaleDateString('fr-FR', options).replace(',', ' -');
        const el = document.getElementById('currentDateTime');
        if (el) el.innerHTML = formatted;
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
        const saved = localStorage.getItem('darkMode');
        applyTheme(saved === 'dark');
    }

    function toggleTheme() {
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

    document.addEventListener('DOMContentLoaded', function() {
        loadTheme();
        const btn = document.getElementById('themeToggleBtn');
        if (btn) btn.addEventListener('click', toggleTheme);
    });

    // Graphique
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('statsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chart['labels'] ?? ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin']) !!},
                datasets: [{
                    label: 'Déclarations',
                    data: {!! json_encode($chart['data'] ?? [12, 19, 15, 17, 24, 30]) !!},
                    backgroundColor: 'rgba(26,122,58,0.7)',   // vert
                    borderColor: '#1a7a3a',
                    borderWidth: 2,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { drawBorder: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection