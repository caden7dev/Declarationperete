@extends('layouts.app')

@section('title', 'Mon Profil - Administration')

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
    
    /* Header */
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
    
    /* Formulaire */
    .profile-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        text-align: center;
        color: white;
    }
    
    .profile-avatar-large {
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2.5rem;
        font-weight: 700;
        border: 3px solid white;
    }
    
    .profile-body {
        padding: 2rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    input, select {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s;
    }
    
    input:focus, select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    input[readonly] {
        background: #f8f9fa;
        cursor: not-allowed;
    }
    
    .btn-save {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 0.8rem 2rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
    }
    
    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #10b981;
    }
    
    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #e74c3c;
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
        <a class="nav-link" href="{{ route('admin.roles.index') }}">🔐 Rôles & Droits</a>
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
        <h1>👤 Mon Profil Administrateur</h1>
        <p>Gérez vos informations personnelles</p>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-danger">
            <ul style="margin: 0; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar-large">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <h3 class="mb-1">{{ Auth::user()->name }}</h3>
            <p class="mb-0 opacity-75">Administrateur Général</p>
        </div>

        <div class="profile-body">
            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nom complet</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Adresse email</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" readonly>
                            <small class="text-muted">L'email ne peut pas être modifié</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nouveau mot de passe</label>
                            <input type="password" name="password" placeholder="Laisser vide pour ne pas changer">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" placeholder="Confirmer le nouveau mot de passe">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn-save">✓ Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection