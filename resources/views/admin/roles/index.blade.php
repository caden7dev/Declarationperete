@extends('layouts.app')
@section('title', 'Gestion des Rôles et Droits')
@section('content')
<style>
    .admin-sidebar { position:fixed; top:0; left:0; height:100vh; width:280px; background:linear-gradient(135deg,#667eea,#764ba2); color:white; z-index:1000; padding:1rem; display:flex; flex-direction:column; overflow-y:hidden; box-shadow:2px 0 15px rgba(0,0,0,0.1); }
    .main-content { margin-left:280px; padding:2rem; background:#f8f9fa; min-height:100vh; }
    .nav-link { color:rgba(255,255,255,0.9); transition:all 0.3s; padding:0.8rem 1rem; border-radius:8px; margin-bottom:0.3rem; font-weight:500; display:flex; align-items:center; gap:0.5rem; text-decoration:none; }
    .nav-link:hover { color:white; background:rgba(255,255,255,0.15); transform:translateX(5px); }
    .nav-link.active { background:rgba(255,255,255,0.2); color:white; font-weight:600; }
    .logout-container { margin-top:auto; padding-top:1rem; border-top:1px solid rgba(255,255,255,0.2); }
    .btn-logout-sidebar { background:rgba(231,76,60,0.8); border:none; color:white; width:100%; text-align:left; padding:0.8rem 1rem; border-radius:8px; font-weight:600; transition:all 0.3s; cursor:pointer; display:flex; align-items:center; gap:0.5rem; }
    .btn-logout-sidebar:hover { background:#e74c3c; transform:translateY(-2px); }
    .card { border:none; border-radius:16px; box-shadow:0 2px 10px rgba(0,0,0,0.05); }
    .table th { background:#f8fafc; font-weight:600; border-top:none; }
    .badge { padding:0.5rem 1rem; border-radius:50px; }
    .btn-sm { padding:0.4rem 1rem; }
    .alert-success { background:#d4edda; color:#155724; padding:1rem; border-radius:12px; margin-bottom:1.5rem; border-left:4px solid #10b981; }

    /* ===== PROFIL DROPDOWN ===== */
    .profile-dropdown { position:relative; cursor:pointer; }
    .profile-trigger { display:flex; align-items:center; gap:1rem; padding:0.5rem 1rem; background:#f8f9fa; border-radius:50px; transition:all 0.3s; border:1px solid #e9ecef; }
    .profile-trigger:hover { background:#f1f5f9; border-color:#667eea; }
    .profile-avatar { width:45px; height:45px; background:linear-gradient(135deg,#667eea,#764ba2); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:1.2rem; }
    .profile-info { text-align:right; }
    .profile-name { font-weight:700; color:#2c3e50; font-size:0.95rem; }
    .profile-role { font-size:0.75rem; color:#6c757d; }
    .dropdown-icon { color:#6c757d; transition:transform 0.3s; }
    .profile-dropdown.active .dropdown-icon { transform:rotate(180deg); }
    .dropdown-menu-custom { position:absolute; top:calc(100% + 10px); right:0; width:280px; background:white; border-radius:16px; box-shadow:0 10px 40px rgba(0,0,0,0.15); opacity:0; visibility:hidden; transform:translateY(-10px); transition:all 0.3s; z-index:2000; }
    .profile-dropdown.active .dropdown-menu-custom { opacity:1; visibility:visible; transform:translateY(0); }
    .dropdown-header { padding:1.5rem; background:linear-gradient(135deg,#667eea,#764ba2); border-radius:16px 16px 0 0; color:white; text-align:center; }
    .dropdown-header .avatar-large { width:60px; height:60px; background:rgba(255,255,255,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem; font-size:1.8rem; font-weight:700; }
    .dropdown-header .user-email { font-size:0.8rem; opacity:0.9; }
    .dropdown-divider { height:1px; background:#e9ecef; margin:0.5rem 0; }
    .dropdown-item2 { display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1.5rem; color:#2c3e50; text-decoration:none; transition:all 0.2s; font-size:0.9rem; width:100%; text-align:left; background:none; border:none; cursor:pointer; font-family:inherit; }
    .dropdown-item2:hover { background:#f8f9fa; }
    .dropdown-item2.danger:hover { background:#fee2e2; color:#dc2626; }
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
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button type="submit" class="btn-logout-sidebar">🚪 Se déconnecter</button>
        </form>
    </div>
</div>

<div class="main-content">

        {{-- ===== HEADER AVEC PROFIL (identique au dashboard) ===== --}}
        <div style="background:white;border-radius:16px;padding:1rem 2rem;margin-bottom:2rem;box-shadow:0 2px 10px rgba(0,0,0,0.05);display:flex;justify-content:space-between;align-items:center;">
            <div>
                <h2 style="font-size:1.5rem;font-weight:700;color:#2c3e50;margin:0;">🔐 Gestion des Rôles et Droits</h2>
                <p style="color:#6c757d;margin:0.25rem 0 0;font-size:0.9rem;">Attribuez ou modifiez les rôles des utilisateurs</p>
            </div>
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
                        <div style="font-weight:700;">{{ Auth::user()->name }}</div>
                        <div class="user-email">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.profile') }}" class="dropdown-item2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Mon profil
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item2 danger">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </div>

    @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">ID</th><th>Nom</th><th>Email</th>
                            <th>Rôle actuel</th><th>Modifier le rôle</th>
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
                                @if($user->role==='admin') <span class="badge bg-danger">Administrateur</span>
                                @elseif($user->role==='agent') <span class="badge bg-warning text-dark">Agent</span>
                                @else <span class="badge bg-primary">Citoyen</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.roles.update', $user->id) }}" class="d-flex gap-2">
                                    @csrf @method('PUT')
                                    <select name="role" class="form-select form-select-sm" style="width:auto;">
                                        <option value="user" {{ $user->role=='user'?'selected':'' }}>Citoyen</option>
                                        <option value="agent" {{ $user->role=='agent'?'selected':'' }}>Agent</option>
                                        <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Appliquer</button>
                                </form>
                            </td>
                            <td class="text-end pe-4">
                                @if($user->id !== auth()->id())
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delUser{{ $user->id }}">🗑️ Supprimer</button>
                                @else
                                    <span class="text-muted">(Vous-même)</span>
                                @endif
                            </td>
                        </tr>
                        <div class="modal fade" id="delUser{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">@csrf @method('DELETE')
                                    <div class="modal-header bg-danger text-white"><h5 class="modal-title">⚠️ Confirmer</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body"><p>Supprimer <strong>{{ $user->name }}</strong> ?</p><p class="text-danger"><small>Action irréversible !</small></p></div>
                                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-danger">Supprimer</button></div>
                                </form>
                            </div></div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function toggleDropdown() { document.getElementById('profileDropdown').classList.toggle('active'); }
document.addEventListener('click', function(e) {
    const d = document.getElementById('profileDropdown');
    if (d && !d.contains(e.target)) d.classList.remove('active');
});
</script>
@endsection