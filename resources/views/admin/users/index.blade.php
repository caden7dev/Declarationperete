@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs - Administration')

@section('content')
<style>
    .btn-create {
        display: inline-block;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white; padding: 0.8rem 1.8rem; border-radius: 50px;
        font-weight: 700; text-decoration: none; transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(16,185,129,0.3); border: none;
    }
    .btn-create:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(16,185,129,0.4); color: white; }
    .badge-admin { background: #10b981 !important; color: white; }
    .badge-agent { background: #f39c12 !important; color: white; }
    .badge-user  { background: #3498db !important; color: white; }
    .user-card { transition: transform 0.2s, box-shadow 0.2s; border-left: 4px solid transparent; }
    .user-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; }

    .sidebar {
        width: 280px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: fixed; height: 100vh; z-index: 10; color: white;
    }
    .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .sidebar-header h2 { font-size: 1.3rem; font-weight: 800; display: flex; align-items: center; gap: 0.8rem; color: white; }
    .sidebar-nav { flex: 1; padding: 1.5rem 1rem; overflow-y: auto; }
    .sidebar-nav a { text-decoration: none; color: rgba(255,255,255,0.8); font-weight: 600; padding: 0.9rem 1.2rem; border-radius: 10px; display: flex; align-items: center; gap: 0.8rem; transition: all 0.2s; margin-bottom: 0.3rem; }
    .sidebar-nav a:hover { background: rgba(255,255,255,0.15); color: white; }
    .sidebar-nav a.active { background: rgba(255,255,255,0.2); color: white; }
    .main-content { margin-left: 280px; flex: 1; padding: 2rem; background: #f8f9fa; min-height: 100vh; }
    .logout-section { position: absolute; bottom: 0; width: 100%; padding: 1.5rem 1rem; border-top: 1px solid rgba(255,255,255,0.1); }
    .btn-logout { width: 100%; background: rgba(231,76,60,0.8); color: white; padding: 0.9rem; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
    .btn-logout:hover { background: #e74c3c; transform: translateY(-2px); }

    /* ===== PROFIL DROPDOWN ===== */
    .admin-header { background: white; border-radius: 16px; padding: 1rem 2rem; margin-bottom: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
    .welcome-text h2 { font-size: 1.5rem; font-weight: 700; color: #2c3e50; margin: 0; }
    .welcome-text p  { color: #6c757d; margin: 0.25rem 0 0; font-size: 0.9rem; }
    .profile-dropdown { position: relative; cursor: pointer; }
    .profile-trigger { display: flex; align-items: center; gap: 1rem; padding: 0.5rem 1rem; background: #f8f9fa; border-radius: 50px; transition: all 0.3s; border: 1px solid #e9ecef; }
    .profile-trigger:hover { background: #f1f5f9; border-color: #667eea; }
    .profile-avatar { width: 45px; height: 45px; background: linear-gradient(135deg,#667eea,#764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.2rem; }
    .profile-info { text-align: right; }
    .profile-name { font-weight: 700; color: #2c3e50; font-size: 0.95rem; }
    .profile-role { font-size: 0.75rem; color: #6c757d; }
    .dropdown-icon { color: #6c757d; transition: transform 0.3s; }
    .profile-dropdown.active .dropdown-icon { transform: rotate(180deg); }
    .dropdown-menu-custom { position: absolute; top: calc(100% + 10px); right: 0; width: 280px; background: white; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s; z-index: 1000; }
    .profile-dropdown.active .dropdown-menu-custom { opacity: 1; visibility: visible; transform: translateY(0); }
    .dropdown-header { padding: 1.5rem; background: linear-gradient(135deg,#667eea,#764ba2); border-radius: 16px 16px 0 0; color: white; text-align: center; }
    .dropdown-header .avatar-large { width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; font-size: 1.8rem; font-weight: 700; }
    .dropdown-header .user-email { font-size: 0.8rem; opacity: 0.9; }
    .dropdown-divider { height: 1px; background: #e9ecef; margin: 0.5rem 0; }
    .dropdown-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1.5rem; color: #2c3e50; text-decoration: none; transition: all 0.2s; font-size: 0.9rem; }
    .dropdown-item:hover { background: #f8f9fa; }
    .dropdown-item.text-danger:hover { background: #fee2e2; }

    .alert-success { background: #d4edda; color: #155724; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border-left: 4px solid #10b981; }
    .pagination { margin-top: 2rem; display: flex; justify-content: center; gap: 0.5rem; }
    .pagination a, .pagination span { padding: 0.6rem 1rem; border-radius: 8px; background: white; color: #64748b; text-decoration: none; font-weight: 600; }
    .pagination .active { background: #10b981; color: white; }
</style>

<div class="d-flex">
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><span>🇹🇬</span> e-Déclaration TG</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 Tableau de bord</a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">👤 Gestion des Utilisateurs</a>
            <a href="{{ route('admin.types-pieces.index') }}" class="{{ request()->routeIs('admin.types-pieces.*') ? 'active' : '' }}">🪪 Types de Pièces</a>
            <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">🔐 Rôles & Droits</a>
            <a href="#" class="{{ request()->routeIs('admin.stats') ? 'active' : '' }}">📈 Statistiques & Rapports</a>
        </nav>
        <div class="logout-section">
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="btn-logout">🚪 Se déconnecter</button>
            </form>
        </div>
    </div>

    <div class="main-content">

        {{-- ===== HEADER AVEC PROFIL ===== --}}
        <div class="admin-header">
            <div class="welcome-text">
                <h2>👥 Gestion des utilisateurs</h2>
                <p>Créez, modifiez et gérez les comptes de la plateforme</p>
            </div>
            <div style="display:flex;align-items:center;gap:1rem;">
                <button class="btn-create" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Nouvel utilisateur</button>
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-trigger" onclick="toggleDropdown()">
                        <div class="profile-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <div class="profile-info">
                            <div class="profile-name">{{ Auth::user()->name }}</div>
                            <div class="profile-role">Administrateur</div>
                        </div>
                        <svg class="dropdown-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </div>
                    <div class="dropdown-menu-custom">
                        <div class="dropdown-header">
                            <div class="avatar-large">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            <div class="fw-bold">{{ Auth::user()->name }}</div>
                            <div class="user-email">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.profile') }}" class="dropdown-item">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Mon profil
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button type="submit" class="dropdown-item text-danger" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <div class="row mb-4">
            <div class="col-md-3 mb-3"><div class="card shadow-sm border-start border-primary border-4 h-100"><div class="card-body"><h6 class="text-muted mb-2">Total Utilisateurs</h6><h3 class="mb-0">{{ $users->total() }}</h3></div></div></div>
            <div class="col-md-3 mb-3"><div class="card shadow-sm border-start border-danger border-4 h-100"><div class="card-body"><h6 class="text-muted mb-2">Administrateurs</h6><h3 class="mb-0">{{ $users->where('role','admin')->count() }}</h3></div></div></div>
            <div class="col-md-3 mb-3"><div class="card shadow-sm border-start border-warning border-4 h-100"><div class="card-body"><h6 class="text-muted mb-2">Agents</h6><h3 class="mb-0">{{ $users->where('role','agent')->count() }}</h3></div></div></div>
            <div class="col-md-3 mb-3"><div class="card shadow-sm border-start border-info border-4 h-100"><div class="card-body"><h6 class="text-muted mb-2">Citoyens</h6><h3 class="mb-0">{{ $users->where('role','user')->count() }}</h3></div></div></div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                    <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="🔍 Rechercher (nom, email)" value="{{ request('search') }}"></div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">Tous les rôles</option>
                            <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                            <option value="agent" {{ request('role')=='agent'?'selected':'' }}>Agent</option>
                            <option value="user"  {{ request('role')=='user' ?'selected':'' }}>Citoyen</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Réinitialiser</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr><th class="px-4">ID</th><th>Nom</th><th>Email</th><th>Contact</th><th>Rôle</th><th>Inscrit le</th><th class="text-end px-4">Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="px-4 fw-bold">#{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->contact ?? '—' }}</td>
                            <td>
                                @if($user->role==='admin') <span class="badge badge-admin">Administrateur</span>
                                @elseif($user->role==='agent') <span class="badge badge-agent">Agent</span>
                                @else <span class="badge badge-user">Citoyen</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="text-end px-4">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">✏️</button>
                                @if($user->id !== auth()->id())
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">🗑️</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4">Aucun utilisateur trouvé</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pagination">{{ $users->links() }}</div>
    </div>
</div>

{{-- Modal Ajouter --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('admin.users.store') }}">@csrf
            <div class="modal-header bg-success text-white"><h5 class="modal-title">➕ Ajouter un utilisateur</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Nom complet *</label><input type="text" name="name" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Téléphone</label><input type="text" name="contact" class="form-control"></div>
                <div class="mb-3"><label class="form-label">Mot de passe *</label><input type="password" name="password" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Rôle *</label><select name="role" class="form-select" required><option value="user">Citoyen</option><option value="agent">Agent</option><option value="admin">Admin</option></select></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-success">Créer</button></div>
        </form>
    </div></div>
</div>

@foreach($users as $user)
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">@csrf @method('PUT')
            <div class="modal-header bg-warning"><h5 class="modal-title">✏️ Modifier {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Nom complet</label><input type="text" name="name" class="form-control" value="{{ $user->name }}" required></div>
                <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ $user->email }}" required></div>
                <div class="mb-3"><label class="form-label">Téléphone</label><input type="text" name="contact" class="form-control" value="{{ $user->contact }}"></div>
                <div class="mb-3"><label class="form-label">Rôle</label><select name="role" class="form-select" required><option value="user" {{ $user->role==='user'?'selected':'' }}>Citoyen</option><option value="agent" {{ $user->role==='agent'?'selected':'' }}>Agent</option><option value="admin" {{ $user->role==='admin'?'selected':'' }}>Admin</option></select></div>
                <div class="mb-3"><label class="form-label">Nouveau mot de passe (optionnel)</label><input type="password" name="password" class="form-control" placeholder="Laisser vide pour ne pas changer"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-primary">Enregistrer</button></div>
        </form>
    </div></div>
</div>
<div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">@csrf @method('DELETE')
            <div class="modal-header bg-danger text-white"><h5 class="modal-title">⚠️ Confirmer la suppression</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><p>Êtes-vous sûr de vouloir supprimer <strong>{{ $user->name }}</strong> ?</p><p class="text-danger"><small>⚠️ Cette action est irréversible !</small></p></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-danger">Supprimer</button></div>
        </form>
    </div></div>
</div>
@endforeach

<script>
function toggleDropdown() { document.getElementById('profileDropdown').classList.toggle('active'); }
document.addEventListener('click', function(e) {
    const d = document.getElementById('profileDropdown');
    if (d && !d.contains(e.target)) d.classList.remove('active');
});
</script>
@endsection