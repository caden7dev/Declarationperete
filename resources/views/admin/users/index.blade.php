@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs - Administration')

@section('content')
<style>
    /* Styles personnalisés supplémentaires (de la version locale) */
    .btn-create {
        display: inline-block;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 0.8rem 1.8rem;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        border: none;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        color: white;
    }

    /* Badges améliorés */
    .badge-admin {
        background: #10b981 !important;
        color: white;
    }

    .badge-agent {
        background: #f39c12 !important;
        color: white;
    }

    .badge-user {
        background: #3498db !important;
        color: white;
    }

    /* Animation pour les cartes */
    .user-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-left: 4px solid transparent;
    }

    .user-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }

    /* Style pour la sidebar fixe (version locale améliorée) */
    .sidebar {
        width: 280px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: fixed;
        height: 100vh;
        z-index: 10;
        color: white;
    }

    .sidebar-header {
        padding: 2rem 1.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar-header h2 {
        font-size: 1.3rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        color: white;
    }

    .sidebar-nav {
        flex: 1;
        padding: 1.5rem 1rem;
        overflow-y: auto;
    }

    .sidebar-nav a {
        text-decoration: none;
        color: rgba(255,255,255,0.8);
        font-weight: 600;
        padding: 0.9rem 1.2rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        transition: all 0.2s;
        margin-bottom: 0.3rem;
    }

    .sidebar-nav a:hover {
        background: rgba(255,255,255,0.15);
        color: white;
    }

    .sidebar-nav a.active {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    .main-content {
        margin-left: 280px;
        flex: 1;
        padding: 2rem;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .logout-section {
        position: absolute;
        bottom: 0;
        width: 100%;
        padding: 1.5rem 1rem;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .btn-logout {
        width: 100%;
        background: rgba(231, 76, 60, 0.8);
        color: white;
        padding: 0.9rem;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-logout:hover {
        background: #e74c3c;
        transform: translateY(-2px);
    }

    /* Style pour les en-têtes */
    .page-header {
        background: white;
        padding: 1.5rem 2rem;
        border-radius: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #1e3a5f 0%, #10b981 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #10b981;
    }

    /* Pagination style */
    .pagination {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .pagination a, .pagination span {
        padding: 0.6rem 1rem;
        border-radius: 8px;
        background: white;
        color: #64748b;
        text-decoration: none;
        font-weight: 600;
    }

    .pagination .active {
        background: #10b981;
        color: white;
    }
</style>

<div class="d-flex">
    {{-- SIDEBAR AMÉLIORÉE (version locale avec couleurs) --}}
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><span>🇹🇬</span> e-Déclaration TG</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                📊 Tableau de bord
            </a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                👤 Gestion des Utilisateurs
            </a>
            <a href="{{ route('admin.types-pieces.index') }}" class="{{ request()->routeIs('admin.types-pieces.*') ? 'active' : '' }}">
                🪪 Types de Pièces
            </a>
            <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                🔐 Rôles & Droits
            </a>
            <a href="#" class="{{ request()->routeIs('admin.stats') ? 'active' : '' }}">
                📈 Statistiques & Rapports
            </a>
        </nav>
        
        <div class="logout-section">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    🚪 Se déconnecter
                </button>
            </form>
        </div>
    </div>

    {{-- CONTENU PRINCIPAL --}}
    <div class="main-content">
        {{-- En-tête de page élégant --}}
        <div class="page-header">
            <h1>👥 Gestion des utilisateurs</h1>
            <button class="btn-create" data-bs-toggle="modal" data-bs-target="#addUserModal">
                + Nouvel utilisateur
            </button>
        </div>

        {{-- Messages de succès/erreur --}}
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Statistiques rapides (de la version distante) --}}
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-start border-primary border-4 h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Total Utilisateurs</h6>
                        <h3 class="mb-0">{{ $users->total() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-start border-danger border-4 h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Administrateurs</h6>
                        <h3 class="mb-0">{{ $users->where('role', 'admin')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-start border-warning border-4 h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Agents</h6>
                        <h3 class="mb-0">{{ $users->where('role', 'agent')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-start border-info border-4 h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Citoyens</h6>
                        <h3 class="mb-0">{{ $users->where('role', 'user')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtres (de la version distante) --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="🔍 Rechercher (nom, email)" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">Tous les rôles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Citoyen</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Réinitialiser</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tableau des utilisateurs (version locale simplifiée) ou cartes (version distante) ? 
             Je garde le tableau car c'est plus adapté pour l'admin --}}
        <div class="card shadow">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Rôle</th>
                            <th>Inscrit le</th>
                            <th class="text-end px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="px-4 fw-bold">#{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->contact ?? '—' }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge badge-admin">Administrateur</span>
                                @elseif($user->role === 'agent')
                                    <span class="badge badge-agent">Agent</span>
                                @else
                                    <span class="badge badge-user">Citoyen</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="text-end px-4">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                    ✏️
                                </button>
                                @if($user->id !== auth()->id())
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">
                                    🗑️
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Aucun utilisateur trouvé</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="pagination">
            {{ $users->links() }}
        </div>
    </div>
</div>

{{-- Modal Ajouter Utilisateur --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">➕ Ajouter un utilisateur</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom complet *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="contact" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe *</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rôle *</label>
                        <select name="role" class="form-select" required>
                            <option value="user">Citoyen</option>
                            <option value="agent">Agent</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modals Modifier pour chaque utilisateur --}}
@foreach($users as $user)
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">✏️ Modifier {{ $user->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="contact" class="form-control" value="{{ $user->contact }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rôle</label>
                        <select name="role" class="form-select" required>
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Citoyen</option>
                            <option value="agent" {{ $user->role === 'agent' ? 'selected' : '' }}>Agent</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe (optionnel)</label>
                        <input type="password" name="password" class="form-control" placeholder="Laisser vide pour ne pas changer">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">⚠️ Confirmer la suppression</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong>{{ $user->name }}</strong> ?</p>
                    <p class="text-danger"><small>⚠️ Cette action est irréversible !</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection