<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Détails Déclaration - e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            font-family: 'Nunito', sans-serif;
        }

        body { 
            min-height: 100vh; 
            background: #f5f7fa;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e3a5f;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .back-btn {
            background: #f1f5f9;
            color: #64748b;
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-btn:hover {
            background: #e2e8f0;
        }

        /* Alert */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            animation: slideDown 0.3s;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #27ae60;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #e74c3c;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Status Banner */
        .status-banner {
            padding: 1.5rem 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 600;
        }

        .status-banner.pending {
            background: linear-gradient(135deg, #fff3cd, #ffe8a3);
            color: #856404;
            border-left: 4px solid #f39c12;
        }

        .status-banner.approved {
            background: linear-gradient(135deg, #d4edda, #b7e4c7);
            color: #155724;
            border-left: 4px solid #27ae60;
        }

        .status-banner.rejected {
            background: linear-gradient(135deg, #f8d7da, #f5c2c7);
            color: #721c24;
            border-left: 4px solid #e74c3c;
        }

        .status-icon {
            font-size: 2rem;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        /* Card */
        .card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .card-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #f39c12, #f1c40f);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e3a5f;
        }

        /* Info Row */
        .info-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #1e3a5f;
        }

        /* Citizen Card */
        .citizen-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 1rem;
        }

        .citizen-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 800;
            margin: 0 auto 1rem;
            border: 3px solid rgba(255,255,255,0.3);
        }

        .citizen-name {
            font-size: 1.3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .citizen-email {
            text-align: center;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .citizen-info {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.2);
        }

        .citizen-info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
        }

        /* Action Buttons */
        .action-section {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 16px;
        }

        .action-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e3a5f;
            margin-bottom: 1.5rem;
        }

        .btn {
            width: 100%;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            margin-bottom: 1rem;
            text-decoration: none;
        }

        .btn-approve {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
        }

        .btn-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(39, 174, 96, 0.4);
        }

        .btn-reject {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        }

        .btn-reject:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(231, 76, 60, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            border-color: #94a3b8;
        }

        /* Rejection Box */
        .rejection-box {
            background: #fff3cd;
            border: 2px solid #f39c12;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .rejection-box h4 {
            color: #856404;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .rejection-box p {
            color: #664d03;
            line-height: 1.6;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.65rem;
            top: 0.3rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #f39c12;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #f39c12;
        }

        .timeline-date {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0.3rem;
        }

        .timeline-text {
            font-weight: 600;
            color: #1e3a5f;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .modal.active {
            display: flex;
            animation: fadeIn 0.2s;
        }

        .modal-content {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .modal-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1e3a5f;
        }

        .modal-close {
            background: #f1f5f9;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            font-size: 1.4rem;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: #e2e8f0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.6rem;
        }

        .form-textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Nunito', sans-serif;
            min-height: 150px;
            resize: vertical;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #e74c3c;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-modal {
            flex: 1;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-modal-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-modal-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            body {
                padding: 1rem;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .info-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <h1>📄 Détails de la déclaration</h1>
            <a href="{{ route('agent.dashboard') }}" class="back-btn">
                ← Retour
            </a>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success">
                <span>✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <span>❌</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Status Banner -->
        <div class="status-banner {{ $perte->statut === 'en_attente' ? 'pending' : ($perte->statut === 'validee' ? 'approved' : 'rejected') }}">
            <div class="status-icon">
                @if($perte->statut === 'en_attente')
                    ⏳
                @elseif($perte->statut === 'validee')
                    ✅
                @else
                    ❌
                @endif
            </div>
            <div>
                <div style="font-size: 1.2rem;">
                    Statut : 
                    @if($perte->statut === 'en_attente')
                        En attente de traitement
                    @elseif($perte->statut === 'validee')
                        Déclaration validée
                    @else
                        Déclaration rejetée
                    @endif
                </div>
                @if($perte->numero_declaration)
                    <div style="font-size: 0.9rem; opacity: 0.8;">
                        N° {{ $perte->numero_declaration }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Left Column -->
            <div>
                <!-- Declaration Details -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">📝</div>
                        <div class="card-title">Informations de la déclaration</div>
                    </div>

                    <div class="info-row">
                        <div class="info-item">
                            <div class="info-label">Type de pièce</div>
                            <div class="info-value">{{ $perte->type_piece ?? 'N/A' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Numéro de pièce</div>
                            <div class="info-value">{{ $perte->numero_piece ?? 'Non renseigné' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Date de perte</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Date de déclaration</div>
                            <div class="info-value">{{ $perte->created_at->format('d/m/Y à H:i') }}</div>
                        </div>

                        @if($perte->date_delivrance)
                        <div class="info-item">
                            <div class="info-label">Date de délivrance</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($perte->date_delivrance)->format('d/m/Y') }}</div>
                        </div>
                        @endif

                        @if($perte->autorite_delivrance)
                        <div class="info-item">
                            <div class="info-label">Autorité de délivrance</div>
                            <div class="info-value">{{ $perte->autorite_delivrance }}</div>
                        </div>
                        @endif

                        <div class="info-item full-width">
                            <div class="info-label">Lieu de perte</div>
                            <div class="info-value">{{ $perte->lieu_perte }}</div>
                        </div>

                        @if($perte->circonstances)
                        <div class="info-item full-width">
                            <div class="info-label">Description des circonstances</div>
                            <div class="info-value" style="line-height: 1.6;">{{ $perte->circonstances }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Rejection Reason (if rejected) -->
                @if($perte->statut === 'rejetee' && $perte->motif_rejet)
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon">⚠️</div>
                            <div class="card-title">Motif du rejet</div>
                        </div>
                        <div class="rejection-box">
                            <p>{{ $perte->motif_rejet }}</p>
                        </div>
                    </div>
                @endif

                <!-- Timeline -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">📅</div>
                        <div class="card-title">Chronologie</div>
                    </div>

                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-date">{{ $perte->created_at->format('d/m/Y à H:i') }}</div>
                            <div class="timeline-text">Déclaration soumise par le citoyen</div>
                        </div>

                        @if($perte->validated_at)
                            <div class="timeline-item">
                                <div class="timeline-date">{{ \Carbon\Carbon::parse($perte->validated_at)->format('d/m/Y à H:i') }}</div>
                                <div class="timeline-text">
                                    @if($perte->statut === 'validee')
                                        ✅ Déclaration validée
                                    @else
                                        ❌ Déclaration rejetée
                                    @endif
                                    @if($perte->validator)
                                        par {{ $perte->validator->name }}
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <!-- Citizen Info -->
                <div class="citizen-card">
                    <div class="citizen-avatar">
                        {{ strtoupper(substr($perte->first_name ?? $perte->user->name, 0, 1)) }}
                    </div>
                    <div class="citizen-name">{{ $perte->first_name }} {{ $perte->last_name }}</div>
                    <div class="citizen-email">{{ $perte->email }}</div>
                    
                    <div class="citizen-info">
                        <div class="citizen-info-item">
                            <span>📱 Téléphone</span>
                            <strong>{{ $perte->contact }}</strong>
                        </div>
                        @if($perte->user && $perte->user->address)
                            <div class="citizen-info-item">
                                <span>📍 Adresse</span>
                                <strong>{{ $perte->user->address }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                @if($perte->statut === 'en_attente')
                    <div class="action-section">
                        <div class="action-title">🎯 Actions disponibles</div>
                        
                        <form method="POST" action="{{ route('agent.perte.valider', $perte->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-approve" onclick="return confirm('Êtes-vous sûr de vouloir valider cette déclaration ?')">
                                ✅ Valider la déclaration
                            </button>
                        </form>

                        <button class="btn btn-reject" onclick="openRejectModal()">
                            ❌ Rejeter la déclaration
                        </button>

                        <a href="{{ route('agent.dashboard') }}" class="btn btn-secondary">
                            ← Retour à la liste
                        </a>
                    </div>
                @else
                    <div class="action-section">
                        <div class="action-title">📊 Statut final</div>
                        <p style="margin-bottom: 1rem; color: #64748b; line-height: 1.6;">
                            Cette déclaration a été 
                            @if($perte->statut === 'validee')
                                <strong style="color: #27ae60;">validée</strong>
                            @else
                                <strong style="color: #e74c3c;">rejetée</strong>
                            @endif
                            le {{ \Carbon\Carbon::parse($perte->validated_at)->format('d/m/Y à H:i') }}.
                        </p>
                        <a href="{{ route('agent.dashboard') }}" class="btn btn-secondary">
                            ← Retour à la liste
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal" id="rejectModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">❌ Rejeter la déclaration</div>
                <button class="modal-close" onclick="closeRejectModal()">×</button>
            </div>
            <form method="POST" action="{{ route('agent.perte.rejeter', $perte->id) }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Motif du rejet *</label>
                    <textarea class="form-textarea" name="motif_rejet" required placeholder="Expliquez clairement la raison du rejet pour que le citoyen puisse comprendre et corriger si nécessaire (minimum 10 caractères)..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn-modal btn-modal-danger">Confirmer le rejet</button>
                    <button type="button" class="btn-modal btn-modal-secondary" onclick="closeRejectModal()">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Reject Modal
        function openRejectModal() {
            document.getElementById('rejectModal').classList.add('active');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.remove('active');
        }

        // Close modal on outside click
        document.getElementById('rejectModal').addEventListener('click', (e) => {
            if (e.target.id === 'rejectModal') {
                closeRejectModal();
            }
        });

        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>

</body>
</html>