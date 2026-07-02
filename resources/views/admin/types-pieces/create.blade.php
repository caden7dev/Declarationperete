@extends('layouts.app')
@section('title', 'Ajouter un type de pièce - Administration')
@section('content')
<style>
    .sidebar { width:280px; background:linear-gradient(135deg,#667eea,#764ba2); position:fixed; height:100vh; z-index:10; color:white; }
    .sidebar-header { padding:2rem 1.5rem; border-bottom:1px solid rgba(255,255,255,0.1); }
    .sidebar-header h2 { font-size:1.3rem; font-weight:800; display:flex; align-items:center; gap:0.8rem; color:white; }
    .sidebar-nav { flex:1; padding:1.5rem 1rem; overflow-y:auto; }
    .sidebar-nav a { text-decoration:none; color:rgba(255,255,255,0.8); font-weight:600; padding:0.9rem 1.2rem; border-radius:10px; display:flex; align-items:center; gap:0.8rem; transition:all 0.2s; margin-bottom:0.3rem; }
    .sidebar-nav a:hover { background:rgba(255,255,255,0.15); color:white; }
    .sidebar-nav a.active { background:rgba(255,255,255,0.2); color:white; }
    .main-content { margin-left:280px; flex:1; padding:2rem; background:#f8f9fa; min-height:100vh; }
    .logout-section { position:absolute; bottom:0; width:100%; padding:1.5rem 1rem; border-top:1px solid rgba(255,255,255,0.1); }
    .btn-logout { width:100%; background:rgba(231,76,60,0.8); color:white; padding:0.9rem; border:none; border-radius:10px; font-weight:700; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; justify-content:center; gap:0.5rem; }
    .btn-logout:hover { background:#e74c3c; transform:translateY(-2px); }
    .form-container { background:white; border-radius:20px; box-shadow:0 5px 20px rgba(0,0,0,0.05); overflow:hidden; }
    .form-header { background:linear-gradient(135deg,#667eea,#764ba2); padding:1.5rem 2rem; color:white; }
    .form-header h1 { font-size:1.5rem; font-weight:700; margin:0; }
    .form-header p { margin:0.5rem 0 0; opacity:0.9; font-size:0.9rem; }
    .form-body { padding:2rem; }
    .form-group { margin-bottom:1.5rem; }
    label { font-weight:600; color:#2c3e50; margin-bottom:0.5rem; display:block; }
    input, select, textarea { width:100%; padding:0.8rem 1rem; border:2px solid #e9ecef; border-radius:10px; font-size:1rem; transition:all 0.3s; }
    input:focus, select:focus, textarea:focus { outline:none; border-color:#667eea; box-shadow:0 0 0 3px rgba(102,126,234,0.1); }
    .form-actions { display:flex; gap:1rem; margin-top:2rem; padding-top:1rem; border-top:1px solid #e9ecef; }
    .btn-submit { background:linear-gradient(135deg,#10b981,#059669); color:white; padding:0.8rem 2rem; border:none; border-radius:10px; font-weight:600; cursor:pointer; transition:all 0.3s; }
    .btn-submit:hover { transform:translateY(-2px); box-shadow:0 5px 15px rgba(16,185,129,0.3); }
    .btn-cancel { background:#e9ecef; color:#6c757d; padding:0.8rem 2rem; border:none; border-radius:10px; font-weight:600; cursor:pointer; text-decoration:none; display:inline-block; transition:all 0.3s; }
    .btn-cancel:hover { background:#dee2e6; color:#495057; }
    .alert-danger { background:#f8d7da; color:#721c24; padding:1rem; border-radius:10px; margin-bottom:1.5rem; border-left:4px solid #e74c3c; }
    .helper-text { font-size:0.8rem; color:#6c757d; margin-top:0.3rem; }

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

<div class="d-flex">
    <div class="sidebar">
        <div class="sidebar-header"><h2><span>🇹🇬</span> e-Déclaration TG</h2></div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}">📊 Tableau de bord</a>
            <a href="{{ route('admin.users.index') }}">👤 Gestion des Utilisateurs</a>
            <a href="{{ route('admin.types-pieces.index') }}" class="active">🪪 Types de Pièces</a>
            <a href="{{ route('admin.roles.index') }}">🔐 Rôles & Droits</a>
            <a href="#">📈 Statistiques & Rapports</a>
        </nav>
        <div class="logout-section">
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="btn-logout">🚪 Se déconnecter</button></form>
        </div>
    </div>

    <div class="main-content">

        {{-- ===== HEADER AVEC PROFIL (identique au dashboard) ===== --}}
        <div style="background:white;border-radius:16px;padding:1rem 2rem;margin-bottom:2rem;box-shadow:0 2px 10px rgba(0,0,0,0.05);display:flex;justify-content:space-between;align-items:center;">
            <div>
                <h2 style="font-size:1.5rem;font-weight:700;color:#2c3e50;margin:0;">➕ Ajouter un type de pièce</h2>
                <p style="color:#6c757d;margin:0.25rem 0 0;font-size:0.9rem;">Créez un nouveau type de pièce pour les déclarations de perte</p>
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
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Voulez-vous vraiment vous déconnecter ?')">
    @csrf
    <button type="submit" class="logout-link">
        Déconnecter
    </button>
</form>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert-danger"><ul style="margin:0 0 0 1.5rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div class="form-container">
            <div class="form-body">
                <form method="POST" action="{{ route('admin.types-pieces.store') }}">@csrf
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>Nom du type <span style="color:#e74c3c">*</span></label><input type="text" name="nom" value="{{ old('nom') }}" placeholder="Ex: Carte Nationale d'Identité" required><div class="helper-text">Le nom affiché aux utilisateurs</div></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Code</label><input type="text" name="code" value="{{ old('code') }}" placeholder="Ex: CNI"><div class="helper-text">Code unique pour référence interne</div></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Catégorie</label><select name="categorie"><option value="">Sélectionner une catégorie</option><option value="Identité" {{ old('categorie')=='Identité'?'selected':'' }}>Identité</option><option value="Véhicule" {{ old('categorie')=='Véhicule'?'selected':'' }}>Véhicule</option><option value="Académique" {{ old('categorie')=='Académique'?'selected':'' }}>Académique</option><option value="Professionnel" {{ old('categorie')=='Professionnel'?'selected':'' }}>Professionnel</option><option value="Autre" {{ old('categorie')=='Autre'?'selected':'' }}>Autre</option></select></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Statut</label><select name="is_active"><option value="1" {{ old('is_active','1')=='1'?'selected':'' }}>✅ Actif</option><option value="0" {{ old('is_active')=='0'?'selected':'' }}>⏸️ Inactif</option></select></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Délai de traitement (jours)</label><input type="number" name="delai_traitement" value="{{ old('delai_traitement') }}" placeholder="Ex: 7"></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Prix (FCFA)</label><input type="number" name="prix" value="{{ old('prix') }}" placeholder="Ex: 5000"><div class="helper-text">Laisser vide si gratuit</div></div></div>
                        <div class="col-12"><div class="form-group"><label>Documents requis</label><textarea name="documents_requis" rows="3" placeholder="Acte de naissance, Photo d'identité, Certificat de résidence">{{ old('documents_requis') }}</textarea><div class="helper-text">Séparez les documents par des virgules</div></div></div>
                        <div class="col-12"><div class="form-group"><label>Description</label><textarea name="description" rows="4" placeholder="Description détaillée du type de pièce...">{{ old('description') }}</textarea></div></div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">✓ Créer le type de pièce</button>
                        <a href="{{ route('admin.types-pieces.index') }}" class="btn-cancel">✗ Annuler</a>
                    </div>
                </form>
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