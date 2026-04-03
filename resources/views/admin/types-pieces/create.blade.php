@extends('layouts.app')

@section('title', 'Ajouter un type de pièce - Administration')

@section('content')
<style>
    /* Style pour la sidebar fixe */
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

    /* Formulaire */
    .form-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1.5rem 2rem;
        color: white;
    }

    .form-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }

    .form-header p {
        margin: 0.5rem 0 0;
        opacity: 0.9;
        font-size: 0.9rem;
    }

    .form-body {
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

    label .required {
        color: #e74c3c;
    }

    input, select, textarea {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
    }

    .btn-submit {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 0.8rem 2rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-cancel {
        background: #e9ecef;
        color: #6c757d;
        padding: 0.8rem 2rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s;
    }

    .btn-cancel:hover {
        background: #dee2e6;
        color: #495057;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #e74c3c;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 1.5rem;
    }

    .helper-text {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.3rem;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            position: relative;
            height: auto;
        }
        .main-content {
            margin-left: 0;
        }
        .form-actions {
            flex-direction: column;
        }
        .btn-submit, .btn-cancel {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="d-flex">
    {{-- SIDEBAR --}}
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><span>🇹🇬</span> e-Déclaration TG</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}">
                📊 Tableau de bord
            </a>
            <a href="{{ route('admin.users.index') }}">
                👤 Gestion des Utilisateurs
            </a>
            <a href="{{ route('admin.types-pieces.index') }}" class="active">
                🪪 Types de Pièces
            </a>
            <a href="{{ route('admin.roles.index') }}">
                🔐 Rôles & Droits
            </a>
            <a href="#">
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
        <div class="form-container">
            <div class="form-header">
                <h1>➕ Ajouter un type de pièce</h1>
                <p>Créez un nouveau type de pièce pour les déclarations de perte</p>
            </div>

            <div class="form-body">
                {{-- Affichage des erreurs de validation --}}
                @if ($errors->any())
                    <div class="alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.types-pieces.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nom du type <span class="required">*</span></label>
                                <input type="text" name="nom" value="{{ old('nom') }}" placeholder="Ex: Carte Nationale d'Identité" required>
                                <div class="helper-text">Le nom affiché aux utilisateurs</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" value="{{ old('code') }}" placeholder="Ex: CNI">
                                <div class="helper-text">Code unique pour référence interne</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Catégorie</label>
                                <select name="categorie">
                                    <option value="">Sélectionner une catégorie</option>
                                    <option value="Identité" {{ old('categorie') == 'Identité' ? 'selected' : '' }}>Identité</option>
                                    <option value="Véhicule" {{ old('categorie') == 'Véhicule' ? 'selected' : '' }}>Véhicule</option>
                                    <option value="Académique" {{ old('categorie') == 'Académique' ? 'selected' : '' }}>Académique</option>
                                    <option value="Professionnel" {{ old('categorie') == 'Professionnel' ? 'selected' : '' }}>Professionnel</option>
                                    <option value="Autre" {{ old('categorie') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Statut</label>
                                <select name="is_active">
                                    <option value="1" {{ old('is_active') == '1' ? 'selected' : 'selected' }}>✅ Actif</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>⏸️ Inactif</option>
                                </select>
                                <div class="helper-text">Les types inactifs ne sont pas proposés aux utilisateurs</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Délai de traitement (jours)</label>
                                <input type="number" name="delai_traitement" value="{{ old('delai_traitement') }}" placeholder="Ex: 7">
                                <div class="helper-text">Nombre de jours estimé pour le traitement</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Prix (FCFA)</label>
                                <input type="number" name="prix" value="{{ old('prix') }}" placeholder="Ex: 5000">
                                <div class="helper-text">Laisser vide si gratuit</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Documents requis</label>
                                <textarea name="documents_requis" rows="3" placeholder="Acte de naissance, Photo d'identité, Certificat de résidence">{{ old('documents_requis') }}</textarea>
                                <div class="helper-text">Séparez les documents par des virgules</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" rows="4" placeholder="Description détaillée du type de pièce...">{{ old('description') }}</textarea>
                                <div class="helper-text">Informations supplémentaires sur ce type de pièce</div>
                            </div>
                        </div>
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
@endsection