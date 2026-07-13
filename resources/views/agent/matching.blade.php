@extends('layouts.agent')

@section('title', 'Recherche de correspondances')

@section('content')
<style>
    .matching-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .matching-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.8rem 2.5rem;
        border-bottom: none;
    }
    .matching-header h3 {
        font-weight: 700;
        margin-bottom: 0.2rem;
    }
    .matching-header .subtitle {
        opacity: 0.9;
        font-size: 0.9rem;
    }
    .matching-header .badge-status {
        background: rgba(255,255,255,0.2);
        padding: 0.3rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-block;
        margin-top: 0.5rem;
    }
    .citizen-summary {
        background: #f8fafc;
        border-radius: 16px;
        padding: 1.2rem 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        align-items: center;
        margin-bottom: 1.5rem;
        border: 1px solid #e9edf2;
    }
    .citizen-summary .avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .citizen-summary .info {
        flex: 1;
    }
    .citizen-summary .info .name {
        font-weight: 700;
        font-size: 1.05rem;
        color: #1f2f3e;
    }
    .citizen-summary .info .details {
        font-size: 0.85rem;
        color: #6c819a;
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 0.2rem;
    }
    .citizen-summary .info .details i {
        margin-right: 0.3rem;
    }
    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: #f1f5f9;
        border-radius: 50px;
        padding: 0.2rem 0.8rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #475569;
    }
    .stat-badge i {
        font-size: 0.8rem;
    }
    .document-card {
        background: white;
        border: 1px solid #eef2f7;
        border-radius: 16px;
        padding: 1.2rem 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.2s;
        position: relative;
        overflow: hidden;
    }
    .document-card:hover {
        border-color: #667eea;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.08);
        transform: translateY(-2px);
    }
    .document-card .match-badge {
        display: inline-block;
        padding: 0.2rem 0.8rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        background: #dbeafe;
        color: #1e40af;
    }
    .document-card .match-badge.high {
        background: #d1fae5;
        color: #065f46;
    }
    .document-card .match-badge.medium {
        background: #fef3c7;
        color: #92400e;
    }
    .document-card .match-badge.low {
        background: #fee2e2;
        color: #991b1b;
    }
    .document-card .doc-title {
        font-weight: 700;
        font-size: 1rem;
        color: #1f2f3e;
        margin-bottom: 0.3rem;
    }
    .document-card .doc-details {
        font-size: 0.85rem;
        color: #6c819a;
        display: flex;
        flex-wrap: wrap;
        gap: 1.2rem;
        margin-bottom: 0.8rem;
    }
    .document-card .doc-details i {
        margin-right: 0.3rem;
        width: 16px;
    }
    .document-card .doc-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        align-items: center;
        margin-top: 0.5rem;
    }
    .document-card .doc-actions .btn-associer {
        background: #667eea;
        color: white;
        border: none;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .document-card .doc-actions .btn-associer:hover {
        background: #5a6fd6;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102,126,234,0.3);
    }
    .document-card .doc-actions .btn-associer:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    .filter-bar {
        background: #f8fafc;
        border-radius: 16px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
        border: 1px solid #eef2f7;
    }
    .filter-bar .filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1 1 200px;
    }
    .filter-bar .filter-group label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 0;
        white-space: nowrap;
    }
    .filter-bar .filter-group input,
    .filter-bar .filter-group select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        background: white;
        width: 100%;
        transition: all 0.2s;
    }
    .filter-bar .filter-group input:focus,
    .filter-bar .filter-group select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }
    .empty-state .icon {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
    .empty-state h4 {
        color: #1f2f3e;
        font-weight: 700;
        margin-bottom: 0.3rem;
    }
    .empty-state p {
        color: #6c819a;
        font-size: 0.9rem;
    }
    .action-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        padding-top: 1.5rem;
        border-top: 1px solid #edf2f7;
        margin-top: 1.5rem;
    }
    .btn-non-retrouve {
        background: #f59e0b;
        color: white;
        border: none;
        padding: 0.7rem 1.8rem;
        border-radius: 50px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        cursor: pointer;
        font-size: 0.95rem;
    }
    .btn-non-retrouve:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(245,158,11,0.3);
    }
    .match-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.2rem 0.7rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .match-indicator.high {
        background: #d1fae5;
        color: #065f46;
    }
    .match-indicator.medium {
        background: #fef3c7;
        color: #92400e;
    }
    .match-indicator.low {
        background: #fee2e2;
        color: #991b1b;
    }
    .modal-confirm {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .modal-confirm.active {
        display: flex;
    }
    .modal-confirm .modal-box {
        background: white;
        border-radius: 24px;
        max-width: 480px;
        width: 100%;
        padding: 2rem;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        animation: slideUp 0.3s ease;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .modal-confirm .modal-box h4 {
        font-weight: 700;
        color: #1f2f3e;
        margin-bottom: 0.5rem;
    }
    .modal-confirm .modal-box p {
        color: #6c819a;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    .modal-confirm .modal-box .actions {
        display: flex;
        gap: 0.8rem;
        justify-content: flex-end;
    }
    .modal-confirm .modal-box .actions button {
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .modal-confirm .modal-box .actions .btn-cancel {
        background: #f1f5f9;
        color: #475569;
    }
    .modal-confirm .modal-box .actions .btn-cancel:hover {
        background: #e2e8f0;
    }
    .modal-confirm .modal-box .actions .btn-confirm {
        background: #f59e0b;
        color: white;
    }
    .modal-confirm .modal-box .actions .btn-confirm:hover {
        background: #d97706;
        transform: translateY(-2px);
    }

    /* ============================================================
    CARTE DE VÉRIFICATION OFFICIELLE
    ============================================================ */
    .verification-card {
        border-radius: 16px;
        padding: 1.2rem 1.5rem;
        margin-bottom: 1.5rem;
        border: 2px solid #e2e8f0;
        transition: all 0.3s;
    }
    .verification-card.valid {
        border-color: #10b981;
        background: #f0fdf4;
    }
    .verification-card.invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }
    .verification-card.pending {
        border-color: #f59e0b;
        background: #fffbeb;
    }
    body.dark-mode .verification-card.valid {
        background: #0a3b2a;
        border-color: #059669;
    }
    body.dark-mode .verification-card.invalid {
        background: #3f1e1e;
        border-color: #dc2626;
    }
    body.dark-mode .verification-card.pending {
        background: #422d0b;
        border-color: #d97706;
    }
    .verification-card .vc-icon {
        font-size: 2rem;
        flex-shrink: 0;
    }
    .verification-card .vc-title {
        font-weight: 700;
        font-size: 1rem;
    }
    .verification-card .vc-detail {
        font-size: 0.85rem;
        color: #6c819a;
        margin-top: 0.2rem;
    }
    body.dark-mode .verification-card .vc-detail {
        color: #94a3b8;
    }
    .verification-card .vc-source {
        font-size: 0.7rem;
        color: #94a3b8;
        margin-top: 0.3rem;
    }
    .btn-verifier {
        background: #667eea;
        color: white;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-verifier:hover {
        background: #5a6fd6;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102,126,234,0.3);
    }
    body.dark-mode .btn-verifier {
        background: #4f46e5;
    }
    body.dark-mode .btn-verifier:hover {
        background: #4338ca;
    }

    /* 🌙 Mode sombre */
    body.dark-mode .matching-container {
        background: #1e293b;
    }
    body.dark-mode .citizen-summary {
        background: #0f172a;
        border-color: #334155;
    }
    body.dark-mode .citizen-summary .info .name {
        color: #e5e7eb;
    }
    body.dark-mode .citizen-summary .info .details {
        color: #94a3b8;
    }
    body.dark-mode .stat-badge {
        background: #1e293b;
        color: #94a3b8;
    }
    body.dark-mode .document-card {
        background: #1e293b;
        border-color: #334155;
    }
    body.dark-mode .document-card:hover {
        border-color: #667eea;
    }
    body.dark-mode .document-card .doc-title {
        color: #e5e7eb;
    }
    body.dark-mode .document-card .doc-details {
        color: #94a3b8;
    }
    body.dark-mode .filter-bar {
        background: #0f172a;
        border-color: #334155;
    }
    body.dark-mode .filter-bar .filter-group label {
        color: #94a3b8;
    }
    body.dark-mode .filter-bar .filter-group input,
    body.dark-mode .filter-bar .filter-group select {
        background: #1e293b;
        border-color: #334155;
        color: #e5e7eb;
    }
    body.dark-mode .empty-state h4 {
        color: #e5e7eb;
    }
    body.dark-mode .empty-state p {
        color: #94a3b8;
    }
    body.dark-mode .modal-confirm .modal-box {
        background: #1e293b;
    }
    body.dark-mode .modal-confirm .modal-box h4 {
        color: #e5e7eb;
    }
    body.dark-mode .modal-confirm .modal-box p {
        color: #94a3b8;
    }
    body.dark-mode .modal-confirm .modal-box .actions .btn-cancel {
        background: #334155;
        color: #e5e7eb;
    }
    body.dark-mode .modal-confirm .modal-box .actions .btn-cancel:hover {
        background: #475569;
    }

    @media (max-width: 768px) {
        .matching-header {
            padding: 1.2rem 1.2rem;
        }
        .citizen-summary {
            flex-direction: column;
            align-items: flex-start;
        }
        .action-footer {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-bar {
            flex-direction: column;
        }
        .filter-bar .filter-group {
            width: 100%;
        }
        .document-card .doc-actions {
            flex-direction: column;
            align-items: stretch;
        }
        .document-card .doc-actions .btn-associer {
            justify-content: center;
        }
        .verification-card {
            flex-direction: column;
            align-items: flex-start !important;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="matching-container">
        <!-- Header -->
        <div class="matching-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <div>
                    <h3><i class="bi bi-search-heart me-2"></i>Recherche de correspondances</h3>
                    <div class="subtitle">
                        Déclaration n°<strong>{{ $perte->numero_declaration }}</strong> – {{ $perte->type_piece }}
                        <span class="badge-status ms-2">
                            <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
                <div class="mt-2 mt-md-0">
                    <span class="badge-status">
                        <i class="bi bi-files"></i> {{ $documents->total() }} correspondance(s)
                    </span>
                    <a href="{{ route('agent.perte.show', $perte->id) }}" class="btn btn-light btn-sm ms-2">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4">
            <!-- Citoyen summary -->
            <div class="citizen-summary">
                <div class="avatar">
                    {{ strtoupper(substr($perte->first_name, 0, 1) . substr($perte->last_name, 0, 1)) }}
                </div>
                <div class="info">
                    <div class="name">{{ $perte->first_name }} {{ $perte->last_name }}</div>
                    <div class="details">
                        <span><i class="bi bi-envelope"></i> {{ $perte->email }}</span>
                        <span><i class="bi bi-telephone"></i> {{ $perte->contact }}</span>
                        <span><i class="bi bi-geo-alt"></i> {{ $perte->lieu_perte }}</span>
                        <span><i class="bi bi-calendar"></i> Perte le {{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}</span>
                    </div>
                </div>
                <div>
                    <span class="stat-badge"><i class="bi bi-clock-history"></i> En cours depuis {{ $perte->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <!-- ============================================================
            ✅ VÉRIFICATION OFFICIELLE DU DOCUMENT
            ============================================================ -->
            @php
                $hasVerification = isset($verification) && !empty($verification);
                $isValid = $hasVerification && ($verification['valide'] ?? false);
                $isPending = !$hasVerification;
            @endphp

            <div class="verification-card {{ $isValid ? 'valid' : ($isPending ? 'pending' : 'invalid') }}">
                <div class="d-flex align-items-start gap-3 flex-wrap">
                    <div class="vc-icon">
                        @if($isValid)
                            ✅
                        @elseif($isPending)
                            ⏳
                        @else
                            ❌
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <div class="vc-title">
                            @if($isValid)
                                Document VALIDE dans la base officielle
                            @elseif($isPending)
                                Vérification en attente
                            @else
                                Document NON VALIDE
                            @endif
                        </div>
                        <div class="vc-detail">
                            @if($isValid)
                                Ce document est enregistré dans la base officielle.
                                @if(isset($verification['nom']))
                                    <br><strong>Nom :</strong> {{ $verification['nom'] }}
                                @endif
                                @if(isset($verification['date_expiration']))
                                    <br><strong>Expiration :</strong> {{ \Carbon\Carbon::parse($verification['date_expiration'])->format('d/m/Y') }}
                                @endif
                                @if(isset($verification['autorite']))
                                    <br><strong>Autorité :</strong> {{ $verification['autorite'] }}
                                @endif
                            @elseif($isPending)
                                Cliquez sur "Vérifier" pour contrôler ce document dans la base officielle.
                            @else
                                @if(isset($verification['message']))
                                    {{ $verification['message'] }}
                                @else
                                    Ce document n'est pas enregistré dans la base officielle.
                                    <br>Vérifiez le numéro ou contactez le service compétent.
                                @endif
                            @endif
                        </div>
                        @if($hasVerification && isset($verification['source']))
                            <div class="vc-source">
                                <i class="bi bi-database"></i> Source : {{ $verification['source'] }}
                                @if(isset($verification['date_verification']))
                                    - Vérifié le {{ \Carbon\Carbon::parse($verification['date_verification'])->format('d/m/Y H:i') }}
                                @endif
                            </div>
                        @endif
                    </div>
                    <div>
                        @if($isPending)
                            <a href="{{ route('agent.perte.recherche', ['id' => $perte->id, 'verifier' => 1]) }}" class="btn-verifier">
                                <i class="bi bi-search"></i> Vérifier le document
                            </a>
                        @elseif($isValid)
                            <span class="badge bg-success" style="font-size:0.85rem; padding:0.4rem 1rem;">
                                <i class="bi bi-check-circle-fill"></i> VALIDE
                            </span>
                        @else
                            <span class="badge bg-danger" style="font-size:0.85rem; padding:0.4rem 1rem;">
                                <i class="bi bi-x-circle-fill"></i> NON VALIDE
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Barre de filtres -->
            <div class="filter-bar">
                <form method="GET" action="{{ route('agent.perte.recherche', ['id' => $perte->id]) }}" class="d-flex flex-wrap gap-2 w-100">
                    <div class="filter-group flex-grow-1">
                        <label><i class="bi bi-search"></i></label>
                        <input type="text" name="nom" placeholder="Rechercher par nom..." class="form-control" value="{{ request('nom') }}">
                    </div>
                    <div class="filter-group flex-grow-1">
                        <label><i class="bi bi-funnel"></i></label>
                        <select name="type_document" class="form-select">
                            <option value="">Tous les types</option>
                            @php
                                $types = $documents->pluck('type_document')->unique();
                            @endphp
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type_document') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group flex-grow-1">
                        <label><i class="bi bi-calendar3"></i></label>
                        <input type="date" name="date_decouverte" class="form-control" value="{{ request('date_decouverte') }}">
                    </div>
                    <div class="filter-group flex-grow-1">
                        <label><i class="bi bi-geo-alt"></i></label>
                        <input type="text" name="lieu" placeholder="Lieu..." class="form-control" value="{{ request('lieu') }}">
                    </div>
                    <div class="filter-group flex-grow-1">
                        <label><i class="bi bi-upc-scan"></i></label>
                        <input type="text" name="numero" placeholder="N° document..." class="form-control" value="{{ request('numero') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filtrer
                    </button>
                    <a href="{{ route('agent.perte.recherche', ['id' => $perte->id]) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </form>
            </div>

            <!-- Statistiques -->
            <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="stat-badge"><i class="bi bi-check-circle text-success"></i> Haute : {{ $highMatchCount ?? 0 }}</span>
                <span class="stat-badge"><i class="bi bi-exclamation-circle text-warning"></i> Moyenne : {{ $mediumMatchCount ?? 0 }}</span>
                <span class="stat-badge"><i class="bi bi-x-circle text-danger"></i> Faible : {{ $lowMatchCount ?? 0 }}</span>
            </div>

            <!-- Liste des documents -->
            <div id="documentsContainer">
                @if($documents->count())
                    @foreach($documents as $doc)
                        @php
                            $scoreClass = $doc->scoreLevel ?? 'low';
                            $scoreLabel = $doc->scoreLabel ?? 'Faible';
                            $scoreValue = $doc->score ?? 0;
                        @endphp
                        <div class="document-card" data-type="{{ $doc->type_document }}" data-date="{{ $doc->date_decouverte->format('Y-m-d') }}" data-search="{{ $doc->type_document }} {{ $doc->numero_document }} {{ $doc->nom_sur_document }} {{ $doc->prenom_sur_document }}">
                            <div class="d-flex flex-wrap justify-content-between align-items-start">
                                <div>
                                    <span class="match-badge {{ $scoreClass }}">
                                        <i class="bi bi-star-fill"></i> Correspondance {{ $scoreLabel }} ({{ $scoreValue }}%)
                                    </span>
                                    <div class="doc-title">{{ $doc->type_document }}</div>
                                    <div class="doc-details">
                                        <span><i class="bi bi-person"></i> {{ $doc->nom_sur_document }} {{ $doc->prenom_sur_document }}</span>
                                        @if($doc->numero_document)
                                            <span><i class="bi bi-upc-scan"></i> N°{{ $doc->numero_document }}</span>
                                        @endif
                                        <span><i class="bi bi-calendar"></i> {{ $doc->date_decouverte->format('d/m/Y') }}</span>
                                        <span><i class="bi bi-geo-alt"></i> {{ $doc->lieu_decouverte }}</span>
                                    </div>
                                </div>
                                <div class="mt-2 mt-md-0">
                                    <span class="stat-badge"><i class="bi bi-clock"></i> Signalé il y a {{ $doc->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="doc-actions">
                                <form method="POST" action="{{ route('agent.perte.associer', $perte->id) }}" class="d-inline" id="associerForm{{ $doc->id }}">
                                    @csrf
                                    <input type="hidden" name="document_trouve_id" value="{{ $doc->id }}">
                                    <button type="submit" class="btn-associer" onclick="return confirmAssocier(event, {{ $doc->id }})">
                                        <i class="bi bi-link-45deg"></i> Associer ce document
                                    </button>
                                </form>
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="viewDocument({{ $doc->id }})">
                                    <i class="bi bi-eye"></i> Détails
                                </button>
                                @if($doc->photo_document)
                                    <a href="{{ Storage::url($doc->photo_document) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-image"></i> Photo
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                        <div class="text-muted small">
                            Affichage de {{ $documents->firstItem() }} à {{ $documents->lastItem() }} sur {{ $documents->total() }} documents
                        </div>
                        {{ $documents->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <div class="icon"><i class="bi bi-inbox"></i></div>
                        <h4>Aucune correspondance trouvée</h4>
                        <p>Aucun document signalé ne correspond aux critères de cette déclaration pour le moment.</p>
                        <p class="text-muted small">Vérifiez que les informations saisies sont correctes ou essayez plus tard.</p>
                    </div>
                @endif
            </div>

            <!-- Actions footer -->
            <div class="action-footer">
                <div>
                    <button type="button" class="btn-non-retrouve" id="btnNonRetrouve">
                        <i class="bi bi-emoji-frown"></i> Déclarer non retrouvé
                    </button>
                    <small class="text-muted d-block mt-1">
                        <i class="bi bi-info-circle"></i> Cette action est irréversible. Un récépissé sera généré.
                    </small>
                </div>
                <div>
                    <a href="{{ route('documents-trouves.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Ajouter un document trouvé
                    </a>
                    <a href="{{ route('agent.perte.show', $perte->id) }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour "Non retrouvé" -->
<div class="modal-confirm" id="modalNonRetrouve">
    <div class="modal-box">
        <h4><i class="bi bi-exclamation-triangle-fill text-warning"></i> Confirmer le non-retrouvé</h4>
        <p>
            Vous êtes sur le point de déclarer que ce document <strong>n'a pas été retrouvé</strong>.<br>
            Cette action est <strong>irréversible</strong> et aura les conséquences suivantes :
        </p>
        <ul class="text-muted" style="font-size:0.9rem; padding-left:1.2rem; margin-bottom:1.5rem;">
            <li>Un récépissé PDF sera généré automatiquement.</li>
            <li>Le citoyen recevra une notification avec un lien de téléchargement.</li>
            <li>Le citoyen sera invité à refaire un titre.</li>
        </ul>
        <div class="actions">
            <button class="btn-cancel" onclick="closeModal()">Annuler</button>
            <form method="POST" action="{{ route('agent.perte.non-retrouve', $perte->id) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn-confirm">Confirmer</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Détails Document -->
<div class="modal fade" id="documentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-text"></i> Détails du document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="documentModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="modalAssociateBtn" style="display:none;">
                    <i class="bi bi-link-45deg"></i> Associer ce document
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Messages flash -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index:9999;" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index:9999;" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index:9999;" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirmation association
        window.confirmAssocier = function(event, docId) {
            event.preventDefault();
            if (confirm('Associer ce document trouvé à la déclaration de perte ? Cette action est définitive.')) {
                document.getElementById('associerForm' + docId).submit();
            }
            return false;
        };

        // Modal non-retrouvé
        const btnNonRetrouve = document.getElementById('btnNonRetrouve');
        const modal = document.getElementById('modalNonRetrouve');

        if (btnNonRetrouve) {
            btnNonRetrouve.addEventListener('click', function() {
                modal.classList.add('active');
            });
        }

        window.closeModal = function() {
            modal.classList.remove('active');
        };

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Auto-hide alerts après 5s
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.3s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Voir les détails d'un document (AJAX)
        window.viewDocument = function(docId) {
            const modal = new bootstrap.Modal(document.getElementById('documentModal'));
            const body = document.getElementById('documentModalBody');
            body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';
            modal.show();

            fetch(`/agent/documents-trouves/${docId}/preview`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    body.innerHTML = data.html;
                    document.getElementById('modalAssociateBtn').style.display = 'block';
                    document.getElementById('modalAssociateBtn').onclick = function() {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route('agent.perte.associer', $perte->id) }}';
                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = '{{ csrf_token() }}';
                        form.appendChild(csrf);
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'document_trouve_id';
                        input.value = docId;
                        form.appendChild(input);
                        document.body.appendChild(form);
                        form.submit();
                    };
                } else {
                    body.innerHTML = '<div class="alert alert-danger">' + (data.message || 'Erreur de chargement') + '</div>';
                    document.getElementById('modalAssociateBtn').style.display = 'none';
                }
            })
            .catch(error => {
                body.innerHTML = '<div class="alert alert-danger">Erreur réseau. Veuillez réessayer.</div>';
                document.getElementById('modalAssociateBtn').style.display = 'none';
            });
        };
    });
</script>

<!-- Bootstrap JS pour le modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection