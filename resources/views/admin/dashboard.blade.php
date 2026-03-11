@extends('layouts.app')

@section('title', 'Tableau de bord - Administrateur Général')

@section('content')
<style>
    /* Style pour la sidebar fixe - fusion des deux versions */
    .admin-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 280px; /* Largeur fixe comme version distante */
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); /* Dégradé de la version locale des users */
        color: white;
        z-index: 1000;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        overflow-y: hidden;
        box-shadow: 2px 0 15px rgba(0,0,0,0.1);
    }
    
    /* Contenu principal avec marge à gauche ajustée */
    .main-content {
        margin-left: 280px;
        padding: 2rem;
        background: #f8f9fa;
        min-height: 100vh;
    }
    
    /* Style des liens de navigation */
    .nav-link {
        color: rgba(255,255,255,0.9);
        transition: all 0.3s;
        padding: 0.8rem 1rem;
        border-radius: 8px;
        margin-bottom: 0.3rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .nav-link:hover {
        color: white;
        background: rgba(255,255,255,0.15);
        transform: translateX(5px);
    }
    
    .nav-link.active {
        background: rgba(255,255,255,0.2);
        color: white;
        font-weight: 600;
    }
    
    /* Bouton de déconnexion */
    .logout-container {
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid rgba(255,255,255,0.2);
    }
    
    .btn-logout-sidebar {
        background: rgba(231, 76, 60, 0.8);
        border: none;
        color: white;
        width: 100%;
        text-align: left;
        padding: 0.8rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-logout-sidebar:hover {
        background: #e74c3c;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
    }
    
    /* Adaptation mobile */
    @media (max-width: 768px) {
        .admin-sidebar {
            position: relative;
            width: 100%;
            height: auto;
        }
        .main-content {
            margin-left: 0;
        }
    }
    
    /* Amélioration des cartes */
    .stat-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
        position: relative;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .stat-card .card-body {
        padding: 1.5rem;
    }
    
    .stat-card h6 {
        color: #6c757d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .stat-card h3 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0;
    }
    
    /* Accent color pour chaque carte */
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }
    
    .stat-card:nth-child(1)::before { background: #3498db; }
    .stat-card:nth-child(2)::before { background: #e74c3c; }
    .stat-card:nth-child(3)::before { background: #f39c12; }
    .stat-card:nth-child(4)::before { background: #27ae60; }
    
    /* En-tête de carte */
    .card-header {
        background: white;
        border-bottom: 2px solid #f1f5f9;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }
    
    .card-header strong {
        font-size: 1.1rem;
        color: #2c3e50;
    }
    
    /* Tableaux */
    .table {
        margin-bottom: 0;
    }
    
    .table thead th {
        border-top: none;
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table tbody tr:hover {
        background: #f8fafc;
    }
    
    .badge {
        padding: 0.5rem 1rem;
        font-weight: 600;
        border-radius: 50px;
    }
    
    .badge.bg-danger { background: #e74c3c !important; }
    .badge.bg-warning { background: #f39c12 !important; color: #2c3e50 !important; }
    .badge.bg-primary { background: #3498db !important; }
    
    /* Boutons */
    .btn-sm {
        padding: 0.4rem 1rem;
        font-size: 0.85rem;
        border-radius: 8px;
        font-weight: 600;
    }
    
    .btn-primary {
        background: #3498db;
        border: none;
    }
    
    .btn-primary:hover {
        background: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    }
    
    .btn-secondary {
        background: #95a5a6;
        border: none;
    }
    
    .btn-secondary:hover {
        background: #7f8c8d;
    }
    
    .btn-danger {
        background: #e74c3c;
        border: none;
    }
    
    .btn-danger:hover {
        background: #c0392b;
    }
</style>

<!-- Sidebar fixe -->
<div class="admin-sidebar">
    <h4 class="mb-4 text-center">🇹🇬 e-Déclaration TG</h4>
    <nav class="nav flex-column">
        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
            📊 Tableau de bord
        </a>
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            👤 Gestion des Utilisateurs
        </a>
        <a class="nav-link" href="{{ route('admin.types-pieces.index') }}">
            🪪 Types de Pièces
        </a>
        <a class="nav-link" href="{{ route('admin.roles.index') }}">
            🔐 Rôles & Droits
        </a>
        <a class="nav-link" href="#">
            📈 Statistiques & Rapports
        </a>
    </nav>
    
    <div class="logout-container">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout-sidebar">
                🚪 Se déconnecter
            </button>
        </form>
    </div>
</div>

<!-- Contenu principal -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: #2c3e50;">Tableau de bord Administrateur</h2>
        <div class="text-muted">{{ now()->format('d F Y') }}</div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row mb-4 g-4">
        <div class="col-md-3">
            <div class="stat-card card">
                <div class="card-body text-center">
                    <h6>Utilisateurs Totaux</h6>
                    <h3>{{ $stats['users'] }}</h3>
                    <small class="text-muted">Inscrits</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card">
                <div class="card-body text-center">
                    <h6>Types de Pièces</h6>
                    <h3>{{ $stats['types_pieces'] }}</h3>
                    <small class="text-muted">Actifs</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card">
                <div class="card-body text-center">
                    <h6>Rôles Définis</h6>
                    <h3>{{ $stats['roles'] }}</h3>
                    <small class="text-muted">Dans le système</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card">
                <div class="card-body text-center">
                    <h6>Déclarations</h6>
                    <h3>{{ $stats['pertes'] }}</h3>
                    <small class="text-muted">Soumises</small>
                </div>
            </div>
        </div>
    </div>

    {{-- GESTION UTILISATEURS --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>👥 Derniers Utilisateurs</strong>
            <div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary me-2">+ Ajouter</a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">Voir tout</a>
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
                            <th>Date d'inscription</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
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
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning me-1">✏️</a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">🗑️</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TYPES DE PIÈCES ET STATS --}}
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>🪪 Types de Pièces</strong>
                    <a href="{{ route('admin.types-pieces.create') }}" class="btn btn-sm btn-primary">+ Ajouter</a>
                </div>
                <div class="card-body">
                    @if($typesPieces->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                @foreach($typesPieces as $type)
                                <tr>
                                    <td>{{ $type->nom }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.types-pieces.edit', $type) }}" class="btn btn-sm btn-secondary">✏️</a>
                                        <form action="{{ route('admin.types-pieces.destroy', $type) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce type de pièce ?')">🗑️</button>
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

        {{-- STATISTIQUES --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <strong>📊 Statistiques d'Utilisation</strong>
                </div>
                <div class="card-body">
                    <canvas id="statsChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('statsChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($chart['labels'] ?? ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin']) !!},
            datasets: [{
                label: 'Déclarations',
                data: {!! json_encode($chart['data'] ?? [12, 19, 15, 17, 24, 30]) !!},
                backgroundColor: 'rgba(52, 152, 219, 0.6)',
                borderColor: 'rgba(52, 152, 219, 1)',
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
@endsection