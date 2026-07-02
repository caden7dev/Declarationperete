<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Suivi de déclaration - e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* ===== STYLES (inchangés) ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --secondary: #3b82f6;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-600: #64748b;
            --gray-800: #1e293b;
            --dark: #0f172a;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            padding: 2rem;
        }
        body.dark-mode {
            background: #0f172a;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.1);
            transition: background 0.2s;
        }
        body.dark-mode .container {
            background: #1e293b;
            color: #e5e7eb;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .back-btn {
            background: var(--gray-100);
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            color: var(--gray-600);
            transition: 0.2s;
        }
        .back-btn:hover {
            background: var(--gray-200);
        }
        body.dark-mode .back-btn {
            background: #334155;
            color: #94a3b8;
        }
        body.dark-mode .back-btn:hover {
            background: #475569;
        }
        .title h1 {
            font-size: 1.8rem;
            font-weight: 800;
        }
        .title p {
            color: var(--gray-600);
            font-size: 0.9rem;
            margin-top: 0.2rem;
        }
        body.dark-mode .title p {
            color: #94a3b8;
        }
        .status-badge {
            display: inline-block;
            padding: 0.3rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
        }
        .status-pending { background: #fef3c7; color: #b45309; }
        .status-in-progress { background: #dbeafe; color: #1e40af; }
        .status-found { background: #c7d2fe; color: #3730a3; }
        .status-returned { background: #d1fae5; color: #065f46; }
        .status-not-found { background: #fee2e2; color: #991b1b; }
        .status-ready { background: #d1fae5; color: #065f46; }
        body.dark-mode .status-pending { background: #422d0b; color: #fbbf24; }
        body.dark-mode .status-in-progress { background: #1e3a5f; color: #60a5fa; }
        body.dark-mode .status-found { background: #2e2b5c; color: #a78bfa; }
        body.dark-mode .status-returned { background: #0a3b2a; color: #34d399; }
        body.dark-mode .status-not-found { background: #3f1e1e; color: #f87171; }
        body.dark-mode .status-ready { background: #0a3b2a; color: #34d399; }

        /* Alerte flash */
        .alert {
            padding: 1rem 1.2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            background: white;
            border-left: 4px solid var(--primary);
        }
        body.dark-mode .alert {
            background: #2d2d35;
            color: #e5e7eb;
        }
        .alert-success { border-left-color: var(--primary); color: #065f46; }
        .alert-error { border-left-color: #ef4444; color: #991b1b; }
        body.dark-mode .alert-success { color: #a7f3d0; }
        body.dark-mode .alert-error { color: #fecaca; }

        .timeline {
            position: relative;
            padding-left: 2.5rem;
            margin: 2rem 0;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--gray-200);
        }
        body.dark-mode .timeline::before {
            background: #334155;
        }
        .step {
            position: relative;
            padding: 0.8rem 1.2rem;
            margin-bottom: 0.5rem;
            border-radius: 12px;
            background: var(--gray-100);
            transition: background 0.2s;
        }
        body.dark-mode .step {
            background: #2d2d35;
        }
        .step::before {
            content: '';
            position: absolute;
            left: -2rem;
            top: 50%;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: var(--gray-200);
            border: 3px solid white;
            transition: background 0.2s;
        }
        body.dark-mode .step::before {
            border-color: #1e293b;
        }
        .step.done::before {
            background: var(--primary);
        }
        .step.active::before {
            background: var(--secondary);
            animation: pulse 1.5s infinite;
        }
        .step .date {
            font-size: 0.7rem;
            color: var(--gray-600);
            margin-top: 0.2rem;
        }
        body.dark-mode .step .date {
            color: #94a3b8;
        }
        @keyframes pulse {
            0%, 100% { transform: translateY(-50%) scale(1); }
            50% { transform: translateY(-50%) scale(1.2); }
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin: 2rem 0;
            background: var(--gray-100);
            padding: 1.5rem;
            border-radius: 16px;
        }
        body.dark-mode .info-grid {
            background: #2d2d35;
        }
        .info-item label {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: var(--gray-600);
            font-weight: 600;
            display: block;
            margin-bottom: 0.2rem;
        }
        body.dark-mode .info-item label {
            color: #94a3b8;
        }
        .info-item .value {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .notifications-section {
            margin-top: 2rem;
        }
        .notif-item {
            padding: 0.8rem 1rem;
            border-left: 3px solid var(--primary);
            background: var(--gray-100);
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }
        body.dark-mode .notif-item {
            background: #2d2d35;
        }
        .notif-item .notif-date {
            font-size: 0.7rem;
            color: var(--gray-600);
        }
        body.dark-mode .notif-item .notif-date {
            color: #94a3b8;
        }
        .download-btn {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 1rem;
            transition: background 0.2s;
        }
        .download-btn:hover {
            background: var(--primary-dark);
        }

        /* Bouton récupération */
        .btn-recuperation {
            display: block;
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 1.5rem;
            text-align: center;
        }
        .btn-recuperation:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        @media (max-width: 640px) {
            .info-grid { grid-template-columns: 1fr; }
            .container { padding: 1rem; }
        }
    </style>
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('darkMode');
                if (savedTheme === 'dark') {
                    document.documentElement.style.backgroundColor = '#0f172a';
                    document.body.style.backgroundColor = '#0f172a';
                    document.body.classList.add('dark-mode');
                }
            } catch(e) {}
        })();
    </script>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="title">
                <h1>📋 Suivi de déclaration</h1>
                <p>N° {{ $perte->numero_declaration ?? 'En cours...' }}</p>
            </div>
            <button class="back-btn" onclick="history.back()"><i class="bi bi-arrow-left"></i> Retour</button>
        </div>

        <!-- Messages flash -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            </div>
        @endif

        <!-- En-tête déclaration -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 1.5rem;">
            <div>
                <strong style="font-size: 1.1rem;">{{ $perte->type_piece }}</strong>
                <span style="margin-left: 0.5rem; color: var(--gray-600);">({{ $perte->first_name }} {{ $perte->last_name }})</span>
            </div>
            @php
                $statusMap = [
                    'en_attente' => ['class' => 'status-pending', 'label' => 'En attente'],
                    'en_cours' => ['class' => 'status-in-progress', 'label' => 'En cours'],
                    'correspondance_trouvee' => ['class' => 'status-found', 'label' => 'Correspondance trouvée'],
                    'restitue' => ['class' => 'status-returned', 'label' => 'Restitué'],
                    'non_retrouve' => ['class' => 'status-not-found', 'label' => 'Non retrouvé'],
                    'pret_recuperation' => ['class' => 'status-ready', 'label' => 'Prêt à récupérer'],
                ];
                $currentStatus = $statusMap[$perte->statut] ?? ['class' => 'status-pending', 'label' => ucfirst($perte->statut)];
                $statusClass = $currentStatus['class'];
                $statusLabel = $currentStatus['label'];
            @endphp
            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
        </div>

        <!-- Timeline -->
        <div class="timeline">
            <div class="step done">
                <strong>📌 Déclaration enregistrée</strong>
                <div class="date">{{ $perte->created_at->format('d/m/Y à H:i') }}</div>
            </div>

            @if(in_array($perte->statut, ['en_attente', 'en_cours', 'correspondance_trouvee', 'restitue', 'non_retrouve', 'pret_recuperation']))
                <div class="step @if($perte->statut == 'en_attente') active @else done @endif">
                    <strong>⏳ En attente de traitement</strong>
                    <div class="date">@if($perte->statut != 'en_attente') ✅ Traité @else En cours @endif</div>
                </div>
            @endif

            @if(in_array($perte->statut, ['en_cours', 'correspondance_trouvee', 'restitue', 'non_retrouve', 'pret_recuperation']))
                <div class="step @if($perte->statut == 'en_cours') active @else done @endif">
                    <strong>🔍 Recherche en cours</strong>
                    <div class="date">@if($perte->statut != 'en_cours' && $perte->statut != 'en_attente') ✅ Recherche effectuée @else En cours @endif</div>
                </div>
            @endif

            @if(in_array($perte->statut, ['correspondance_trouvee', 'restitue']))
                <div class="step done">
                    <strong>✅ Document retrouvé</strong>
                    <div class="date">{{ $perte->updated_at->format('d/m/Y à H:i') }}</div>
                </div>
            @endif

            @if($perte->statut == 'non_retrouve' || $perte->statut == 'pret_recuperation')
                <div class="step done">
                    <strong>❌ Document non retrouvé</strong>
                    <div class="date">{{ $perte->updated_at->format('d/m/Y à H:i') }}</div>
                    @if($perte->pdf_recu)
                        <a href="{{ route('perte.download-recu', $perte->id) }}" class="download-btn" target="_blank">
                            <i class="bi bi-file-pdf"></i> Télécharger le récépissé
                        </a>
                    @endif
                </div>
            @endif

            @if($perte->statut == 'pret_recuperation')
                <div class="step active">
                    <strong>📍 Prêt à récupérer</strong>
                    <div class="date">{{ $perte->date_preparation ? \Carbon\Carbon::parse($perte->date_preparation)->format('d/m/Y à H:i') : 'Date non définie' }}</div>
                    @if($perte->lieu_recuperation)
                        <div style="margin-top:0.5rem; font-size:0.9rem; background: #fef3c7; padding:0.5rem 1rem; border-radius:8px;">
                            <i class="bi bi-geo-alt"></i> Lieu : <strong>{{ $perte->lieu_recuperation }}</strong>
                        </div>
                    @endif
                </div>
            @endif

            @if($perte->statut == 'restitue')
                <div class="step done">
                    <strong>✅ Document restitué</strong>
                    <div class="date">{{ $perte->date_restitution ? \Carbon\Carbon::parse($perte->date_restitution)->format('d/m/Y à H:i') : 'Date non définie' }}</div>
                </div>
            @endif
        </div>

        <!-- Bouton "J'ai récupéré mon document" -->
        @if($perte->statut === 'pret_recuperation')
            <form method="POST" action="{{ route('citoyen.signaler-recuperation', $perte->id) }}" onsubmit="return confirm('Confirmer que vous avez bien récupéré votre document ?')">
                @csrf
                <button type="submit" class="btn-recuperation">
                    <i class="bi bi-check-circle"></i> J'ai récupéré mon document
                </button>
            </form>
        @endif

        <!-- Informations détaillées -->
        <div class="info-grid">
            <div class="info-item">
                <label>Type de pièce</label>
                <div class="value">{{ $perte->type_piece }}</div>
            </div>
            <div class="info-item">
                <label>Numéro de pièce</label>
                <div class="value">{{ $perte->numero_piece ?? 'Non renseigné' }}</div>
            </div>
            <div class="info-item">
                <label>Date de perte</label>
                <div class="value">{{ $perte->date_perte->format('d/m/Y') }}</div>
            </div>
            <div class="info-item">
                <label>Lieu de perte</label>
                <div class="value">{{ $perte->lieu_perte }}</div>
            </div>
            <div class="info-item" style="grid-column: 1 / -1;">
                <label>Description</label>
                <div class="value">{{ $perte->description ?? 'Aucune description fournie' }}</div>
            </div>
        </div>

        <!-- Notifications liées -->
        @if($notifications->count() > 0)
            <div class="notifications-section">
                <h3 style="margin-bottom: 1rem;"><i class="bi bi-bell"></i> Notifications</h3>
                @foreach($notifications as $notif)
                    <div class="notif-item">
                        <strong>{{ $notif->title ?? 'Message' }}</strong>
                        <p>{{ $notif->content }}</p>
                        <div class="notif-date">{{ $notif->created_at->diffForHumans() }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        <div style="margin-top: 2rem; text-align: center; font-size: 0.8rem; color: var(--gray-600);">
            <i class="bi bi-clock-history"></i> Dernière mise à jour : {{ $perte->updated_at->format('d/m/Y à H:i') }}
        </div>
    </div>
</body>
</html>