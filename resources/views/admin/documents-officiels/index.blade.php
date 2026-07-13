@extends('layouts.admin')

@section('title', 'Documents Officiels')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-database"></i> Documents Officiels</h4>
            <div>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-upload"></i> Importer CSV
                </button>
                <a href="{{ route('admin.import.template') }}" class="btn btn-info btn-sm">
                    <i class="bi bi-download"></i> Modèle
                </a>
                <a href="{{ route('admin.documents-officiels.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Ajouter
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Messages flash -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Statistiques -->
            <div class="row mb-3">
                <div class="col-md-2">
                    <div class="stat-box bg-primary text-white p-2 rounded">
                        <small>Total</small>
                        <h5 class="mb-0">{{ $stats['total'] ?? 0 }}</h5>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-box bg-success text-white p-2 rounded">
                        <small>Valides</small>
                        <h5 class="mb-0">{{ $stats['valides'] ?? 0 }}</h5>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-box bg-warning text-dark p-2 rounded">
                        <small>Expirés</small>
                        <h5 class="mb-0">{{ $stats['expires'] ?? 0 }}</h5>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-box bg-danger text-white p-2 rounded">
                        <small>Volés</small>
                        <h5 class="mb-0">{{ $stats['voles'] ?? 0 }}</h5>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="stat-box bg-secondary text-white p-2 rounded">
                        <small>Perdus</small>
                        <h5 class="mb-0">{{ $stats['perdus'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.documents-officiels.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Rechercher..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="type_piece" class="form-control form-control-sm">
                                <option value="">Tous les types</option>
                                @foreach($typesPieces ?? [] as $type)
                                    <option value="{{ $type }}" {{ request('type_piece') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="statut" class="form-control form-control-sm">
                                <option value="">Tous les statuts</option>
                                <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Valide</option>
                                <option value="expire" {{ request('statut') == 'expire' ? 'selected' : '' }}>Expiré</option>
                                <option value="vole" {{ request('statut') == 'vole' ? 'selected' : '' }}>Volé</option>
                                <option value="perdu" {{ request('statut') == 'perdu' ? 'selected' : '' }}>Perdu</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-search"></i> Filtrer
                            </button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('admin.documents-officiels.index') }}" class="btn btn-secondary btn-sm w-100">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tableau -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Numéro</th>
                            <th>Titulaire</th>
                            <th>Date délivrance</th>
                            <th>Date expiration</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents ?? [] as $doc)
                            <tr>
                                <td>{{ $doc->id }}</td>
                                <td><span class="badge bg-info">{{ $doc->type_piece }}</span></td>
                                <td><strong>{{ $doc->numero_document }}</strong></td>
                                <td>{{ $doc->nom_complet ?? $doc->nom . ' ' . $doc->prenom }}</td>
                                <td>{{ $doc->date_delivrance ? $doc->date_delivrance->format('d/m/Y') : '-' }}</td>
                                <td>{{ $doc->date_expiration ? $doc->date_expiration->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($doc->est_volé)
                                        <span class="badge bg-danger">🚨 Volé</span>
                                    @elseif($doc->est_perdu)
                                        <span class="badge bg-warning">❌ Perdu</span>
                                    @elseif($doc->est_suspendu)
                                        <span class="badge bg-dark">⛔ Suspendu</span>
                                    @elseif($doc->estExpiré())
                                        <span class="badge bg-warning">⚠️ Expiré</span>
                                    @else
                                        <span class="badge bg-success">✅ Valide</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.documents-officiels.show', $doc->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.documents-officiels.edit', $doc->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.documents-officiels.destroy', $doc->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce document ?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block"></i>
                                    Aucun document officiel enregistré.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $documents->links() ?? '' }}
            </div>
        </div>
    </div>
</div>

<!-- Modal d'import -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-upload"></i> Importer des documents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.import.csv') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Fichier CSV</label>
                        <input type="file" name="file" class="form-control" accept=".csv" required>
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> 
                            <a href="{{ route('admin.import.template') }}" class="text-primary" target="_blank">
                                Télécharger le modèle
                            </a>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-lightbulb"></i>
                        <strong>Colonnes attendues :</strong>
                        <ul class="mb-0 mt-1">
                            <li><code>type_piece</code> - Type de pièce (CNI, Passeport, etc.)</li>
                            <li><code>numero_document</code> - Numéro du document (unique)</li>
                            <li><code>nom</code> - Nom du titulaire</li>
                            <li><code>prenom</code> - Prénom du titulaire</li>
                            <li><code>date_delivrance</code> - Date de délivrance</li>
                            <li><code>date_expiration</code> - Date d'expiration</li>
                            <li><code>autorite_delivrance</code> - Autorité de délivrance</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-upload"></i> Importer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .stat-box {
        text-align: center;
        padding: 0.5rem;
    }
    .stat-box small {
        display: block;
        opacity: 0.8;
    }
    .stat-box h5 {
        font-size: 1.5rem;
        font-weight: 700;
    }
</style>
@endsection