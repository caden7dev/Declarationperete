<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Documents trouvés - Agent</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ... mêmes styles que précédemment ... */
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            font-family: 'Nunito', sans-serif;
        }

        body { 
            display: flex; 
            min-height: 100vh; 
            background: #f5f7fa;
        }

        .sidebar {
            width: 280px;
            background: white;
            box-shadow: 2px 0 15px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 10;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid #e8eef5;
        }

        .sidebar-header h2 { 
            font-size: 1.3rem;
            font-weight: 800;
            display: flex; 
            align-items: center; 
            gap: 0.8rem;
            color: #1e3a5f;
        }

        .agent-badge {
            background: linear-gradient(135deg, #f39c12, #f1c40f);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-top: 0.5rem;
            display: inline-block;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            overflow-y: auto;
        }

        .sidebar-nav a {
            text-decoration: none;
            color: #64748b;
            font-weight: 600;
            padding: 0.9rem 1.2rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .sidebar-nav a:hover {
            background: #f1f5f9;
            color: #f39c12;
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.1), rgba(241, 196, 15, 0.05));
            color: #f39c12;
            font-weight: 700;
            border: 2px solid #f39c12;
        }

        .sidebar-footer {
            padding: 1.5rem 1rem;
            border-top: 1px solid #e8eef5;
        }

        .btn-logout {
            width: 100%;
            background: #fff1f0;
            color: #e74c3c;
            padding: 0.9rem;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-logout:hover {
            background: #ffe8e6;
        }

        /* Main */
        .main {
            margin-left: 280px;
            flex: 1;
            background: #f5f7fa;
        }

        .top-bar {
            background: white;
            padding: 1.5rem 2.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e3a5f;
        }

        .content {
            padding: 2.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 4px solid;
        }

        .stat-card.pending { border-left-color: #f39c12; }
        .stat-card.matched { border-left-color: #3498db; }
        .stat-card.restituted { border-left-color: #27ae60; }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #1e3a5f;
        }

        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-label {
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .filter-select, .filter-input {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
        }

        .btn-filter {
            padding: 0.8rem 1.5rem;
            background: #f39c12;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-reset {
            background: #f1f5f9;
            color: #64748b;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }

        .table-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8f9fa;
            padding: 1rem;
            text-align: left;
            font-weight: 700;
            color: #475569;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-matched {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-restituted {
            background: #d4edda;
            color: #155724;
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-view {
            background: #e3f2fd;
            color: #1976d2;
        }

        .pagination {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>

    <!-- Sidebar Agent -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><span>🇹🇬</span> e-Déclaration TG</h2>
            <div class="agent-badge">👮 AGENT</div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('agent.dashboard') }}" class="{{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('agent.documents-trouves.index') }}" class="active">Documents trouvés</a>
            <a href="{{ route('agent.dashboard') }}?statut=en_attente">En attente</a>
            <a href="{{ route('agent.dashboard') }}?statut=validee">Validées</a>
            <a href="{{ route('agent.dashboard') }}?statut=rejetee">Rejetées</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Se déconnecter</button>
            </form>
        </div>
    </div>

    <!-- Main -->
    <div class="main">
        <div class="top-bar">
            <h1>📄 Documents trouvés</h1>
        </div>

        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Statistiques -->
            <div class="stats-grid">
                <div class="stat-card pending">
                    <div class="stat-value">{{ $stats['en_attente'] }}</div>
                    <div>En attente</div>
                </div>
                <div class="stat-card matched">
                    <div class="stat-value">{{ $stats['matches'] }}</div>
                    <div>Matchés</div>
                </div>
                <div class="stat-card restituted">
                    <div class="stat-value">{{ $stats['restitues'] }}</div>
                    <div>Restitués</div>
                </div>
            </div>

            <!-- Filtres -->
            <form method="GET" class="filters">
                <div class="filter-group">
                    <div class="filter-label">Statut</div>
                    <select name="statut" class="filter-select">
                        <option value="">Tous</option>
                        <option value="en_attente" {{ $statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="matche" {{ $statut == 'matche' ? 'selected' : '' }}>Matché</option>
                        <option value="restitue" {{ $statut == 'restitue' ? 'selected' : '' }}>Restitué</option>
                    </select>
                </div>
                <div class="filter-group">
                    <div class="filter-label">Recherche</div>
                    <input type="text" name="search" class="filter-input" placeholder="Nom, numéro, déclaration..." value="{{ $search }}">
                </div>
                <button type="submit" class="btn-filter">Filtrer</button>
                <a href="{{ route('agent.documents-trouves.index') }}" class="btn-reset">Réinitialiser</a>
            </form>

            <!-- Tableau -->
            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>N° Déclaration</th>
                            <th>Type</th>
                            <th>Nom/Prénom (document)</th>
                            <th>Date découverte</th>
                            <th>Lieu</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documentsTrouves as $doc)
                        <tr>
                            <td><strong>{{ $doc->numero_declaration }}</strong></td>
                            <td>{{ $doc->type_document }}</td>
                            <td>{{ $doc->nom_sur_document }} {{ $doc->prenom_sur_document }}</td>
                            <td>{{ $doc->date_decouverte->format('d/m/Y') }}</td>
                            <td>{{ $doc->lieu_decouverte }}</td>
                            <td>
                                @if($doc->statut == 'en_attente')
                                    <span class="status-badge status-pending">En attente</span>
                                @elseif($doc->statut == 'matche')
                                    <span class="status-badge status-matched">Matché</span>
                                @elseif($doc->statut == 'restitue')
                                    <span class="status-badge status-restituted">Restitué</span>
                                @else
                                    {{ $doc->statut }}
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('agent.documents-trouves.show', $doc->id) }}" class="btn-sm btn-view">Voir</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 3rem;">Aucun document trouvé</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                {{ $documentsTrouves->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

</body>
</html>