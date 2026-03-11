@extends('layouts.app')

@section('title', 'Gestion des Rôles')

@section('content')
<style>
    /* Style pour la sidebar fixe */
    .admin-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 250px;
        background: #2c3e50;
        overflow-y: auto;
        z-index: 1000;
    }
    
    .admin-content {
        margin-left: 250px;
        min-height: 100vh;
        background: #f8f9fa;
    }
    
    /* Bouton déconnexion bien positionné */
    .logout-section {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 250px;
        padding: 15px;
        background: #2c3e50;
        border-top: 1px solid rgba(255,255,255,0.1);
    }
    
    .logout-btn {
        width: 100%;
        padding: 10px 15px;
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .logout-btn:hover {
        background: #c0392b;
        transform: translateY(-2px);
    }
    
    /* Padding pour éviter que le contenu passe sous le bouton logout */
    .sidebar-nav-wrapper {
        padding-bottom: 80px;
    }
    
    /* Nav links */
    .nav-link {
        transition: all 0.3s;
        border-radius: 5px;
        margin-bottom: 5px;
    }
    
    .nav-link:hover {
        background: rgba(255,255,255,0.1);
    }
    
    .nav-link.active {
        background: #27ae60 !important;
    }
</style>

<div class="d-flex">
    {{-- SIDEBAR FIXE --}}
    <div class="admin-sidebar text-white p-3">
        <div class="sidebar-nav-wrapper">
            <h5 class="mb-4 text-center">🇹🇬 e-Déclaration TG</h5>
            <nav class="nav flex-column">
                <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
                    📊 Tableau de bord
                </a>
                <a class="nav-link text-white" href="{{ route('admin.users.index') }}">
                    👤 Gestion des Utilisateurs
                </a>
                <a class="nav-link text-white" href="{{ route('admin.types-pieces.index') }}">
                    🪪 Types de Pièces
                </a>
                <a class="nav-link text-white active" href="{{ route('admin.roles.index') }}">
                    🔐 Rôles & Droits
                </a>
                <a class="nav-link text-white" href="#">
                    📈 Statistiques & Rapports
                </a>
            </nav>
        </div>
        
        {{-- Bouton de déconnexion fixe --}}
        <div class="logout-section">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    🚪 Se déconnecter
                </button>
            </form>
        </div>
    </div>

    {{-- CONTENU PRINCIPAL --}}
    <div class="admin-content flex-fill p-4">
        <div class="container-fluid">
            <h4 class="mb-4">Gestion des Rôles et Droits</h4>

            <div class="card shadow-sm">
                <div class="card-header">
                    <strong>Rôles disponibles</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 150px;">Rôle</th>
                                    <th>Description</th>
                                    <th>Permissions</th>
                                    <th style="width: 180px;">Nombre d'utilisateurs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-danger fs-6">Admin</span></td>
                                    <td>Administrateur système</td>
                                    <td>
                                        <small>
                                            ✅ Gestion utilisateurs<br>
                                            ✅ Gestion rôles<br>
                                            ✅ Paramètres système<br>
                                            ✅ Validation déclarations
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <h4 class="mb-0">{{ \App\Models\User::where('role', 'admin')->count() }}</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-warning text-dark fs-6">Agent</span></td>
                                    <td>Agent administratif</td>
                                    <td>
                                        <small>
                                            ✅ Validation déclarations<br>
                                            ✅ Consultation dossiers<br>
                                            ✅ Génération attestations
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <h4 class="mb-0">{{ \App\Models\User::where('role', 'agent')->count() }}</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary fs-6">User</span></td>
                                    <td>Citoyen / Utilisateur standard</td>
                                    <td>
                                        <small>
                                            ✅ Création déclarations<br>
                                            ✅ Suivi des dossiers<br>
                                            ✅ Téléchargement attestations
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <h4 class="mb-0">{{ \App\Models\User::where('role', 'user')->count() }}</h4>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 alert alert-info">
                        <strong>ℹ️ Information :</strong> La gestion avancée des permissions sera disponible dans une prochaine version.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection