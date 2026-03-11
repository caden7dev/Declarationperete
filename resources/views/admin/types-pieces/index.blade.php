@extends('layouts.app')

@section('title', 'Gestion des Types de Pièces')

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

    /* Cartes types de pièces */
    .type-piece-card {
        background: white;
        border-radius: 0;
        padding: 0;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        overflow: hidden;
        transition: all 0.3s;
    }

    .type-piece-card:hover {
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .type-card-header {
        background: #f8f9fa;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e0e0e0;
    }

    .type-header-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .type-id-badge {
        background: #3498db;
        color: white;
        padding: 5px 15px;
        border-radius: 3px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .type-name-header {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .type-status-badge {
        padding: 6px 15px;
        border-radius: 3px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .type-actions-header {
        display: flex;
        gap: 8px;
    }

    .action-btn-header {
        padding: 6px 12px;
        border-radius: 5px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.9rem;
    }

    .type-details-body {
        padding: 25px 30px;
        background: white;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        row-gap: 15px;
    }

    .detail-row {
        display: flex;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 10px;
    }

    .detail-label-col {
        font-weight: 600;
        color: #2c3e50;
        width: 180px;
        flex-shrink: 0;
    }

    .detail-value-col {
        color: #555;
        flex: 1;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .details-grid {
            grid-template-columns: 1fr;
        }
        
        .type-card-header {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }
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
                <a class="nav-link text-white active" href="{{ route('admin.types-pieces.index') }}">
                    🪪 Types de Pièces
                </a>
                <a class="nav-link text-white" href="{{ route('admin.roles.index') }}">
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Gestion des Types de Pièces</h4>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTypePieceModal">
                    ➕ Ajouter un type de pièce
                </button>
            </div>

            {{-- Messages de succès/erreur --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    ✅ {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ❌ {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Statistiques rapides --}}
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-start border-primary border-4 h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Total Types</h6>
                            <h3 class="mb-0">{{ $typesPieces->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-start border-success border-4 h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Types Actifs</h6>
                            <h3 class="mb-0">{{ $typesPieces->where('is_active', true)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-start border-warning border-4 h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Types Inactifs</h6>
                            <h3 class="mb-0">{{ $typesPieces->where('is_active', false)->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-start border-info border-4 h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Catégories</h6>
                            <h3 class="mb-0">{{ $typesPieces->unique('categorie')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filtres --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.types-pieces.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="🔍 Rechercher un type de pièce" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactifs</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                            <a href="{{ route('admin.types-pieces.index') }}" class="btn btn-secondary">Réinitialiser</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Cartes Types de Pièces --}}
            <div class="types-pieces-list">
                @forelse($typesPieces as $type)
                <div class="type-piece-card">
                    {{-- Header de la carte --}}
                    <div class="type-card-header">
                        <div class="type-header-left">
                            <span class="type-id-badge">{{ $type->id }}</span>
                            <div>
                                <div class="type-name-header">{{ $type->nom }}</div>
                                <small class="text-muted">{{ $type->categorie ?? 'Sans catégorie' }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                @if($type->is_active)
                                    <span class="type-status-badge bg-success text-white">✅ Actif</span>
                                @else
                                    <span class="type-status-badge bg-warning text-dark">⏸️ Inactif</span>
                                @endif
                            </div>
                            <div class="type-actions-header">
                                <button type="button" class="action-btn-header btn-info text-white" data-bs-toggle="modal" data-bs-target="#editTypePieceModal{{ $type->id }}">
                                    ✏️ Modifier
                                </button>
                                <button type="button" class="action-btn-header btn-danger text-white" data-bs-toggle="modal" data-bs-target="#deleteTypePieceModal{{ $type->id }}">
                                    🗑️ Supprimer
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Corps de la carte avec détails --}}
                    <div class="type-details-body">
                        <div class="details-grid">
                            <div class="detail-row">
                                <span class="detail-label-col">Nom du type:</span>
                                <span class="detail-value-col">{{ $type->nom }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label-col">Code:</span>
                                <span class="detail-value-col">{{ $type->code ?? 'N/A' }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label-col">Catégorie:</span>
                                <span class="detail-value-col">{{ $type->categorie ?? 'N/A' }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label-col">Statut:</span>
                                <span class="detail-value-col">
                                    @if($type->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Inactif</span>
                                    @endif
                                </span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label-col">Délai de traitement:</span>
                                <span class="detail-value-col">{{ $type->delai_traitement ?? 'N/A' }} jours</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label-col">Prix:</span>
                                <span class="detail-value-col">{{ $type->prix ? number_format($type->prix, 0, ',', ' ') . ' FCFA' : 'Gratuit' }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label-col">Documents requis:</span>
                                <span class="detail-value-col">{{ $type->documents_requis ?? 'Aucun' }}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label-col">Créé le:</span>
                                <span class="detail-value-col">{{ $type->created_at->format('d/m/Y à H:i') }}</span>
                            </div>

                            @if($type->description)
                            <div class="detail-row" style="grid-column: 1 / -1;">
                                <span class="detail-label-col">Description:</span>
                                <span class="detail-value-col">{{ $type->description }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Modal Modifier --}}
                <div class="modal fade" id="editTypePieceModal{{ $type->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admin.types-pieces.update', $type->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title">✏️ Modifier le type de pièce</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nom du type *</label>
                                            <input type="text" name="nom" class="form-control" value="{{ $type->nom }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Code</label>
                                            <input type="text" name="code" class="form-control" value="{{ $type->code }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Catégorie</label>
                                            <select name="categorie" class="form-select">
                                                <option value="">Sélectionner...</option>
                                                <option value="Identité" {{ $type->categorie == 'Identité' ? 'selected' : '' }}>Identité</option>
                                                <option value="Véhicule" {{ $type->categorie == 'Véhicule' ? 'selected' : '' }}>Véhicule</option>
                                                <option value="Académique" {{ $type->categorie == 'Académique' ? 'selected' : '' }}>Académique</option>
                                                <option value="Professionnel" {{ $type->categorie == 'Professionnel' ? 'selected' : '' }}>Professionnel</option>
                                                <option value="Autre" {{ $type->categorie == 'Autre' ? 'selected' : '' }}>Autre</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Statut</label>
                                            <select name="is_active" class="form-select">
                                                <option value="1" {{ $type->is_active ? 'selected' : '' }}>Actif</option>
                                                <option value="0" {{ !$type->is_active ? 'selected' : '' }}>Inactif</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Délai de traitement (jours)</label>
                                            <input type="number" name="delai_traitement" class="form-control" value="{{ $type->delai_traitement }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Prix (FCFA)</label>
                                            <input type="number" name="prix" class="form-control" value="{{ $type->prix }}">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Documents requis</label>
                                            <textarea name="documents_requis" class="form-control" rows="2">{{ $type->documents_requis }}</textarea>
                                            <small class="text-muted">Séparez les documents par des virgules</small>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea name="description" class="form-control" rows="3">{{ $type->description }}</textarea>
                                        </div>
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

                {{-- Modal Supprimer --}}
                <div class="modal fade" id="deleteTypePieceModal{{ $type->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admin.types-pieces.destroy', $type->id) }}">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">⚠️ Confirmer la suppression</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Êtes-vous sûr de vouloir supprimer le type de pièce <strong>{{ $type->nom }}</strong> ?</p>
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
                @empty
                <div class="alert alert-info">
                    <p class="mb-0">Aucun type de pièce trouvé. Commencez par en ajouter un !</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Modal Ajouter Type de Pièce --}}
<div class="modal fade" id="addTypePieceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.types-pieces.store') }}">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">➕ Ajouter un type de pièce</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom du type *</label>
                            <input type="text" name="nom" class="form-control" placeholder="Ex: Carte d'identité nationale" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control" placeholder="Ex: CIN">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Catégorie</label>
                            <select name="categorie" class="form-select">
                                <option value="">Sélectionner...</option>
                                <option value="Identité">Identité</option>
                                <option value="Véhicule">Véhicule</option>
                                <option value="Académique">Académique</option>
                                <option value="Professionnel">Professionnel</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Statut</label>
                            <select name="is_active" class="form-select">
                                <option value="1" selected>Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Délai de traitement (jours)</label>
                            <input type="number" name="delai_traitement" class="form-control" placeholder="Ex: 7">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prix (FCFA)</label>
                            <input type="number" name="prix" class="form-control" placeholder="Ex: 5000">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Documents requis</label>
                            <textarea name="documents_requis" class="form-control" rows="2" placeholder="Acte de naissance, Photo d'identité, Certificat de résidence"></textarea>
                            <small class="text-muted">Séparez les documents par des virgules</small>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Description du type de pièce..."></textarea>
                        </div>
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
@endsection