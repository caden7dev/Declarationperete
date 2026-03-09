@extends('layouts.app')

@section('title', 'Tableau de bord - Administrateur Général')

@section('content')
<style>
    /* Sidebar fixe qui ne défile pas */
    .sidebar-fixed {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 16.6667%; /* correspond à col-md-2 */
        background: #1e1e2f;
        color: white;
        z-index: 1000;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        overflow-y: hidden; /* empêche le défilement interne */
    }

    /* Contenu principal avec marge à gauche */
    .main-content {
        margin-left: 16.6667%;
        padding: 1.5rem;
    }

    /* Style des liens */
    .nav-link {
        color: rgba(255,255,255,0.8);
        transition: 0.2s;
        padding: 0.6rem 1rem;
        border-radius: 5px;
    }
    .nav-link:hover {
        color: white;
        background: rgba(255,255,255,0.1);
    }

    /* Bouton de déconnexion */
    .logout-container {
        margin-top: auto; /* pousse vers le bas */
        padding-top: 1rem;
        border-top: 1px solid rgba(255,255,255,0.2);
    }
    .btn-logout-sidebar {
        background: transparent;
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        width: 100%;
        text-align: left;
        padding: 0.6rem 1rem;
        border-radius: 5px;
        transition: 0.2s;
        cursor: pointer;
    }
    .btn-logout-sidebar:hover {
        background: #dc3545;
        border-color: #dc3545;
        color: white;
    }

    /* Adaptation mobile */
    @media (max-width: 768px) {
        .sidebar-fixed {
            position: relative;
            width: 100%;
            height: auto;
        }
        .main-content {
            margin-left: 0;
        }
    }

    /* Petites touches d'amélioration */
    .card-header {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    .table th {
        border-top: none;
    }
</style>

<!-- Sidebar fixe -->
<div class="sidebar-fixed">
    <h5 class="mb-4">🇹🇬 e-Déclaration TG</h5>
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">📊 Tableau de bord</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link" href="{{ route('admin.users.index') }}">👤 Gestion des Utilisateurs</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link" href="{{ route('admin.types-pieces.index') }}">🪪 Types de Pièces</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link" href="{{ route('admin.roles.index') }}">🔐 Rôles & Droits</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">📈 Statistiques & Rapports</a>
        </li>
    </ul>
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
    <h4 class="mb-4">Tableau de bord Administrateur Général</h4>

    {{-- STAT CARDS --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>Utilisateurs Totaux</h6>
                    <h3>{{ $stats['users'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>Types de Pièces Actifs</h6>
                    <h3>{{ $stats['types_pieces'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>Rôles Définis</h6>
                    <h3>{{ $stats['roles'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>Déclarations</h6>
                    <h3>{{ $stats['pertes'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- GESTION UTILISATEURS --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Gestion des Utilisateurs</strong>
            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">Ajouter</a>
        </div>
        <div class="card-body table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Modifier</a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- TYPES DE PIÈCES --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">Gestion des Types de Pièces</div>
                <div class="card-body">
                    <table class="table">
                        @foreach($typesPieces as $type)
                        <tr>
                            <td>{{ $type->nom }}</td>
                            <td>
                                <a href="{{ route('admin.types-pieces.edit', $type) }}" class="btn btn-sm btn-secondary">Éditer</a>
                                <form action="{{ route('admin.types-pieces.destroy', $type) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

        {{-- STATISTIQUES --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">Statistiques d’Utilisation</div>
                <div class="card-body">
                    <canvas id="statsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Chart JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('statsChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($chart['labels']) !!},
        datasets: [{
            label: 'Déclarations',
            data: {!! json_encode($chart['data']) !!}
        }]
    }
});
</script>
@endsection