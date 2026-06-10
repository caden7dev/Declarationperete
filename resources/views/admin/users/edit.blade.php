<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modifier un utilisateur - Administration</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing:border-box; margin:0; padding:0; font-family:'Nunito',sans-serif; }
        body { background:linear-gradient(135deg,#667eea,#764ba2); min-height:100vh; display:flex; }

        .sidebar { width:280px; background:white; box-shadow:2px 0 15px rgba(0,0,0,0.08); display:flex; flex-direction:column; position:fixed; height:100vh; z-index:10; border-right:1px solid rgba(16,185,129,0.1); }
        .sidebar-header { padding:2rem 1.5rem; border-bottom:1px solid #e8eef5; background:linear-gradient(135deg,#10b981,#059669); }
        .sidebar-header h2 { font-size:1.3rem; font-weight:800; display:flex; align-items:center; gap:0.8rem; color:white; }
        .sidebar-nav { flex:1; padding:1.5rem 1rem; display:flex; flex-direction:column; gap:0.3rem; overflow-y:auto; }
        .sidebar-nav a { text-decoration:none; color:#64748b; font-weight:600; padding:0.9rem 1.2rem; border-radius:10px; display:flex; align-items:center; gap:0.8rem; transition:all 0.2s; font-size:0.95rem; }
        .sidebar-nav a:hover { background:#f1f5f9; color:#10b981; }
        .sidebar-nav a.active { background:#e8f5e9; color:#10b981; font-weight:700; }
        .sidebar-footer { padding:1.5rem 1rem; border-top:1px solid #e8eef5; }
        .btn-logout { width:100%; background:#fff1f0; color:#e74c3c; padding:0.9rem; border:none; border-radius:10px; font-size:0.95rem; font-weight:700; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; justify-content:center; gap:0.5rem; }
        .btn-logout:hover { background:#ffe8e6; }

        .main-content { margin-left:280px; flex:1; padding:2rem; }

        /* ===== PROFIL DROPDOWN ===== */
        .admin-header { background:white; border-radius:16px; padding:1rem 2rem; margin-bottom:2rem; box-shadow:0 2px 10px rgba(0,0,0,0.05); display:flex; justify-content:space-between; align-items:center; }
        .welcome-text h2 { font-size:1.5rem; font-weight:700; color:#2c3e50; margin:0; }
        .welcome-text p  { color:#6c757d; margin:0.25rem 0 0; font-size:0.9rem; }
        .profile-dropdown { position:relative; cursor:pointer; }
        .profile-trigger { display:flex; align-items:center; gap:1rem; padding:0.5rem 1rem; background:#f8f9fa; border-radius:50px; transition:all 0.3s; border:1px solid #e9ecef; }
        .profile-trigger:hover { background:#f1f5f9; border-color:#667eea; }
        .profile-avatar { width:45px; height:45px; background:linear-gradient(135deg,#667eea,#764ba2); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:1.2rem; }
        .profile-info { text-align:right; }
        .profile-name { font-weight:700; color:#2c3e50; font-size:0.95rem; }
        .profile-role { font-size:0.75rem; color:#6c757d; }
        .dropdown-icon { color:#6c757d; transition:transform 0.3s; }
        .profile-dropdown.active .dropdown-icon { transform:rotate(180deg); }
        .dropdown-menu-custom { position:absolute; top:calc(100% + 10px); right:0; width:280px; background:white; border-radius:16px; box-shadow:0 10px 40px rgba(0,0,0,0.15); opacity:0; visibility:hidden; transform:translateY(-10px); transition:all 0.3s; z-index:1000; }
        .profile-dropdown.active .dropdown-menu-custom { opacity:1; visibility:visible; transform:translateY(0); }
        .dropdown-header { padding:1.5rem; background:linear-gradient(135deg,#667eea,#764ba2); border-radius:16px 16px 0 0; color:white; text-align:center; }
        .dropdown-header .avatar-large { width:60px; height:60px; background:rgba(255,255,255,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem; font-size:1.8rem; font-weight:700; }
        .dropdown-header .user-email { font-size:0.8rem; opacity:0.9; }
        .dropdown-divider { height:1px; background:#e9ecef; margin:0.5rem 0; }
        .dropdown-item { display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1.5rem; color:#2c3e50; text-decoration:none; transition:all 0.2s; font-size:0.9rem; }
        .dropdown-item:hover { background:#f8f9fa; }
        .dropdown-item.text-danger:hover { background:#fee2e2; }

        .container { background:rgba(255,255,255,0.95); backdrop-filter:blur(10px); border-radius:30px; box-shadow:0 20px 60px rgba(0,0,0,0.3); padding:30px; max-width:800px; margin:0 auto; }
        .form-group { margin-bottom:1.5rem; }
        .form-label { display:block; font-weight:600; color:#475569; margin-bottom:0.6rem; font-size:0.95rem; }
        .form-control { width:100%; padding:0.85rem 1.1rem; border:2px solid #e2e8f0; border-radius:12px; font-size:0.95rem; transition:all 0.2s; background:#f8fafc; font-family:'Nunito',sans-serif; }
        .form-control:focus { outline:none; border-color:#10b981; background:white; box-shadow:0 0 0 3px rgba(16,185,129,0.1); }
        .btn { padding:0.9rem 1.8rem; border:none; border-radius:12px; font-size:0.95rem; font-weight:700; cursor:pointer; transition:all 0.2s; display:inline-flex; align-items:center; gap:0.6rem; text-decoration:none; }
        .btn-primary { background:linear-gradient(135deg,#10b981,#059669); color:white; box-shadow:0 4px 12px rgba(16,185,129,0.25); }
        .btn-primary:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(16,185,129,0.35); }
        .btn-secondary { background:#f1f5f9; color:#64748b; }
        .btn-secondary:hover { background:#e2e8f0; color:#475569; transform:translateY(-2px); }
        .alert { padding:1rem 1.5rem; border-radius:12px; margin-bottom:1.5rem; display:flex; align-items:center; gap:0.8rem; background:#d4edda; color:#155724; border-left:4px solid #10b981; }
        .alert-danger { background:#f8d7da; color:#721c24; border-left:4px solid #e74c3c; }
    </style>
</head>
<body>

@php $unreadNotifications = \App\Models\Notification::where('user_id',auth()->id())->where('is_read',false)->count(); @endphp

<div class="sidebar">
    <div class="sidebar-header"><h2><span>🇹🇬</span> e-Déclaration TG</h2></div>
    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard')?'active':'' }}">Dashboard</a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*')?'active':'' }}">Utilisateurs</a>
        <a href="{{ route('admin.types-pieces.index') }}" class="{{ request()->routeIs('admin.types-pieces.*')?'active':'' }}">Types de pièces</a>
        <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*')?'active':'' }}">Rôles</a>
    </nav>
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button type="submit" class="btn-logout">Se déconnecter</button>
        </form>
    </div>
</div>

<div class="main-content">

    {{-- ===== HEADER AVEC PROFIL ===== --}}
    <div class="admin-header">
        <div class="welcome-text">
            <h2>✏️ Modifier l'utilisateur</h2>
            <p>Mettez à jour les informations du compte</p>
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

    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin-left:1.5rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="card" style="background:white;border-radius:20px;padding:2rem;box-shadow:0 10px 30px rgba(0,0,0,0.05);border:1px solid rgba(102,126,234,0.1);">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf @method('PUT')
                <div class="form-group"><label class="form-label">Nom complet *</label><input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required></div>
                <div class="form-group"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required></div>
                <div class="form-group"><label class="form-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label><input type="password" name="password" class="form-control"></div>
                <div class="form-group"><label class="form-label">Confirmer le nouveau mot de passe</label><input type="password" name="password_confirmation" class="form-control"></div>
                <div class="form-group">
                    <label class="form-label">Rôle</label>
                    <select name="role" class="form-control">
                        <option value="user"  {{ old('role',$user->role)=='user'  ?'selected':'' }}>Utilisateur</option>
                        <option value="agent" {{ old('role',$user->role)=='agent' ?'selected':'' }}>Agent</option>
                        <option value="admin" {{ old('role',$user->role)=='admin' ?'selected':'' }}>Administrateur</option>
                    </select>
                </div>
                <div style="display:flex;gap:1rem;margin-top:2rem;">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
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
</body>
</html>