@extends('layouts.app')

@section('title', 'Gestion des Rôles et Droits')

@section('content')
<style>
    /* Style pour la sidebar fixe */
    .admin-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 280px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        z-index: 1000;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        overflow-y: hidden;
        box-shadow: 2px 0 15px rgba(0,0,0,0.1);
    }
    
    .main-content {
        margin-left: 280px;
        padding: 2rem;
        background: #f8f9fa;
        min-height: 100vh;
    }
    
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
        text-decoration: none;
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
    }
    
    .page-header {
        background: white;
        border-radius: 16px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .page-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }
    
    .page-header p {
        color: #6c757d;
        margin: 0.25rem 0 0;
    }
    
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .table th {
        background: #f8fafc;
        font-weight: 600;
        border-top: none;
    }
    
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
    }
    
    .btn-sm {
        padding: 0.4rem 1rem;
    }
    
    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #10b981;
    }
    
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
</style>

<div class="admin-sidebar">
    <h4 class="mb-4 text-center">🇹🇬 e-Déclaration TG</h4>
    <nav class="nav flex-column">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">📊 Tableau de bord</a>
        <a class="nav-link" href="{{ route('admin.users.index') }}">👤 Gestion des Utilisateurs</a>
        <a class="nav-link" href="{{ route('admin.types-pieces.index') }}">🪪 Types de Pièces</a>
        <a class="nav-link active" href="{{ route('admin.roles.index') }}">🔐 Rôles & Droits</a>
        <a class="nav-link" href="#">📈 Statistiques & Rapports</a>
    </nav>
    
    <div class="logout-container">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout-sidebar">🚪 Se déconnecter</button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="page-header">
        <h1>🔐 Gestion des Rôles et Droits</h1>
        <p>Attribuez ou modifiez les rôles des utilisateurs (Admin, Agent, Citoyen)</p>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle actuel</th>
                            <th>Modifier le rôle</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="ps-4">#{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
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
                            <td>
                                <form method="POST" action="{{ route('admin.roles.update', $user->id) }}" class="d-flex gap-2">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" class="form-select form-select-sm" style="width: auto;">
                                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Citoyen</option>
                                        <option value="agent" {{ $user->role == 'agent' ? 'selected' : '' }}>Agent</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Appliquer</button>
                                </form>
                            </td>
                            <td class="text-end pe-4">
                                @if($user->id !== auth()->id())
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">
                                        🗑️ Supprimer
                                    </button>
                                @else
                                    <span class="text-muted">(Vous-même)</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Modal suppression (optionnel) --}}
                        <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">⚠️ Confirmer la suppression</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Êtes-vous sûr de vouloir supprimer <strong>{{ $user->name }}</strong> ?</p>
                                            <p class="text-danger"><small>Action irréversible !</small></p>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection