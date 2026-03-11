@extends('layouts.app')

@section('title', 'Statistiques & Rapports')

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

    /* Cartes statistiques */
    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 15px;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.95rem;
    }

    .stat-trend {
        font-size: 0.85rem;
        margin-top: 10px;
    }

    .trend-up {
        color: #27ae60;
    }

    .trend-down {
        color: #e74c3c;
    }

    /* Cartes graphiques */
    .chart-card {
        background: white;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .chart-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: #2c3e50;
    }

    /* Filtres */
    .filter-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    /* Table rapport */
    .report-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
                <a class="nav-link text-white" href="{{ route('admin.roles.index') }}">
                    🔐 Rôles & Droits
                </a>
                <a class="nav-link text-white active" href="{{ route('admin.stats.index') }}">
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
                <h4 class="mb-0">📈 Statistiques & Rapports</h4>
                <div>
                    <button class="btn btn-primary" onclick="window.print()">
                        🖨️ Imprimer
                    </button>
                    <button class="btn btn-success" onclick="exportToExcel()">
                        📊 Exporter Excel
                    </button>
                </div>
            </div>

            {{-- Filtres de Période --}}
            <div class="filter-card">
                <form method="GET" action="{{ route('admin.stats.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Date début</label>
                        <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut', now()->subDays(30)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date fin</label>
                        <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin', now()->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Type de pièce</label>
                        <select name="type_piece_id" class="form-select">
                            <option value="">Tous les types</option>
                            @foreach($typesPieces as $type)
                                <option value="{{ $type->id }}" {{ request('type_piece_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filtrer</button>
                        <a href="{{ route('admin.stats.index') }}" class="btn btn-secondary">Réinitialiser</a>
                    </div>
                </form>
            </div>

            {{-- Statistiques Principales --}}
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #e3f2fd;">
                            <span style="color: #2196f3;">📋</span>
                        </div>
                        <div class="stat-value">{{ $stats['total_declarations'] }}</div>
                        <div class="stat-label">Total Déclarations</div>
                        <div class="stat-trend trend-up">
                            ↗ +{{ $stats['new_this_month'] }} ce mois
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #fff3e0;">
                            <span style="color: #ff9800;">⏳</span>
                        </div>
                        <div class="stat-value">{{ $stats['en_attente'] }}</div>
                        <div class="stat-label">En Attente</div>
                        <div class="stat-trend">
                            {{ number_format(($stats['en_attente'] / max($stats['total_declarations'], 1)) * 100, 1) }}% du total
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #e8f5e9;">
                            <span style="color: #4caf50;">✅</span>
                        </div>
                        <div class="stat-value">{{ $stats['validees'] }}</div>
                        <div class="stat-label">Validées</div>
                        <div class="stat-trend trend-up">
                            {{ number_format(($stats['validees'] / max($stats['total_declarations'], 1)) * 100, 1) }}% du total
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #ffebee;">
                            <span style="color: #f44336;">❌</span>
                        </div>
                        <div class="stat-value">{{ $stats['rejetees'] }}</div>
                        <div class="stat-label">Rejetées</div>
                        <div class="stat-trend">
                            {{ number_format(($stats['rejetees'] / max($stats['total_declarations'], 1)) * 100, 1) }}% du total
                        </div>
                    </div>
                </div>
            </div>

            {{-- Graphiques --}}
            <div class="row mb-4">
                {{-- Évolution des déclarations --}}
                <div class="col-md-8 mb-4">
                    <div class="chart-card">
                        <h5 class="chart-title">📊 Évolution des Déclarations (30 derniers jours)</h5>
                        <canvas id="declarationsChart" height="80"></canvas>
                    </div>
                </div>

                {{-- Répartition par statut --}}
                <div class="col-md-4 mb-4">
                    <div class="chart-card">
                        <h5 class="chart-title">🎯 Répartition par Statut</h5>
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                {{-- Types de pièces les plus déclarés --}}
                <div class="col-md-6 mb-4">
                    <div class="chart-card">
                        <h5 class="chart-title">🪪 Types de Pièces les Plus Déclarés</h5>
                        <canvas id="typesPiecesChart" height="100"></canvas>
                    </div>
                </div>

                {{-- Déclarations par utilisateur (top 10) --}}
                <div class="col-md-6 mb-4">
                    <div class="chart-card">
                        <h5 class="chart-title">👥 Top 10 Utilisateurs Actifs</h5>
                        <canvas id="usersChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            {{-- Statistiques par Type de Pièce --}}
            <div class="chart-card mb-4">
                <h5 class="chart-title">📑 Statistiques Détaillées par Type de Pièce</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Type de Pièce</th>
                                <th>Total</th>
                                <th>En Attente</th>
                                <th>Validées</th>
                                <th>Rejetées</th>
                                <th>Taux Validation</th>
                                <th>Délai Moyen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statsByType as $stat)
                            <tr>
                                <td><strong>{{ $stat['type'] }}</strong></td>
                                <td>{{ $stat['total'] }}</td>
                                <td><span class="badge bg-warning">{{ $stat['en_attente'] }}</span></td>
                                <td><span class="badge bg-success">{{ $stat['validees'] }}</span></td>
                                <td><span class="badge bg-danger">{{ $stat['rejetees'] }}</span></td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: {{ $stat['taux_validation'] }}%">
                                            {{ number_format($stat['taux_validation'], 1) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $stat['delai_moyen'] }} jours</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Statistiques Temporelles --}}
            <div class="row mb-4">
                <div class="col-md-6 mb-4">
                    <div class="chart-card">
                        <h5 class="chart-title">📅 Déclarations par Jour de la Semaine</h5>
                        <canvas id="weekdayChart"></canvas>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="chart-card">
                        <h5 class="chart-title">🕐 Déclarations par Heure</h5>
                        <canvas id="hourlyChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Résumé Mensuel --}}
            <div class="chart-card">
                <h5 class="chart-title">📆 Résumé des 12 Derniers Mois</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Mois</th>
                                <th>Déclarations</th>
                                <th>Validées</th>
                                <th>Rejetées</th>
                                <th>Taux Validation</th>
                                <th>Évolution</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyStats as $month)
                            <tr>
                                <td><strong>{{ $month['mois'] }}</strong></td>
                                <td>{{ $month['total'] }}</td>
                                <td><span class="badge bg-success">{{ $month['validees'] }}</span></td>
                                <td><span class="badge bg-danger">{{ $month['rejetees'] }}</span></td>
                                <td>{{ number_format($month['taux_validation'], 1) }}%</td>
                                <td>
                                    @if($month['evolution'] > 0)
                                        <span class="trend-up">↗ +{{ $month['evolution'] }}%</span>
                                    @elseif($month['evolution'] < 0)
                                        <span class="trend-down">↘ {{ $month['evolution'] }}%</span>
                                    @else
                                        <span>→ 0%</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Données pour les graphiques
const chartData = {!! json_encode($chartData) !!};

// Configuration commune
const commonOptions = {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
        legend: {
            display: true,
            position: 'bottom'
        }
    }
};

// 1. Graphique d'évolution des déclarations
new Chart(document.getElementById('declarationsChart'), {
    type: 'line',
    data: {
        labels: chartData.evolution.labels,
        datasets: [{
            label: 'Déclarations',
            data: chartData.evolution.data,
            borderColor: '#2196f3',
            backgroundColor: 'rgba(33, 150, 243, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: commonOptions
});

// 2. Graphique de répartition par statut (Doughnut)
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: chartData.status.labels,
        datasets: [{
            data: chartData.status.data,
            backgroundColor: ['#ff9800', '#4caf50', '#f44336'],
            borderWidth: 0
        }]
    },
    options: commonOptions
});

// 3. Graphique types de pièces (Bar horizontal)
new Chart(document.getElementById('typesPiecesChart'), {
    type: 'bar',
    data: {
        labels: chartData.types.labels,
        datasets: [{
            label: 'Déclarations',
            data: chartData.types.data,
            backgroundColor: '#9c27b0',
            borderRadius: 5
        }]
    },
    options: {
        ...commonOptions,
        indexAxis: 'y',
        scales: {
            x: {
                beginAtZero: true
            }
        }
    }
});

// 4. Graphique utilisateurs actifs (Bar horizontal)
new Chart(document.getElementById('usersChart'), {
    type: 'bar',
    data: {
        labels: chartData.users.labels,
        datasets: [{
            label: 'Déclarations',
            data: chartData.users.data,
            backgroundColor: '#00bcd4',
            borderRadius: 5
        }]
    },
    options: {
        ...commonOptions,
        indexAxis: 'y',
        scales: {
            x: {
                beginAtZero: true
            }
        }
    }
});

// 5. Graphique par jour de semaine
new Chart(document.getElementById('weekdayChart'), {
    type: 'bar',
    data: {
        labels: chartData.weekday.labels,
        datasets: [{
            label: 'Déclarations',
            data: chartData.weekday.data,
            backgroundColor: '#ff5722',
            borderRadius: 5
        }]
    },
    options: {
        ...commonOptions,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// 6. Graphique par heure
new Chart(document.getElementById('hourlyChart'), {
    type: 'line',
    data: {
        labels: chartData.hourly.labels,
        datasets: [{
            label: 'Déclarations',
            data: chartData.hourly.data,
            borderColor: '#673ab7',
            backgroundColor: 'rgba(103, 58, 183, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: commonOptions
});

// Fonction d'export Excel (placeholder)
function exportToExcel() {
    alert('Export Excel sera implémenté avec une bibliothèque comme SheetJS');
    // TODO: Implémenter l'export réel
}
</script>
@endsection