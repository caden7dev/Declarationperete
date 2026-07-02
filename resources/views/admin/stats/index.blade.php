@extends('layouts.app')
@section('title', 'Statistiques & Rapports')
@section('content')
<style>
    .admin-sidebar { position:fixed; top:0; left:0; height:100vh; width:250px; background:#2c3e50; overflow-y:auto; z-index:1000; }
    .admin-content { margin-left:250px; min-height:100vh; background:#f8f9fa; padding:2rem; }
    .logout-section { position:fixed; bottom:0; left:0; width:250px; padding:15px; background:#2c3e50; border-top:1px solid rgba(255,255,255,0.1); }
    .logout-btn { width:100%; padding:10px 15px; background:#e74c3c; color:white; border:none; border-radius:8px; font-weight:600; cursor:pointer; transition:all 0.3s; }
    .logout-btn:hover { background:#c0392b; transform:translateY(-2px); }
    .sidebar-nav-wrapper { padding-bottom:80px; }
    .nav-link { transition:all 0.3s; border-radius:5px; margin-bottom:5px; }
    .nav-link:hover { background:rgba(255,255,255,0.1); }
    .nav-link.active { background:#27ae60 !important; }
    .stat-card { background:white; border-radius:8px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1); transition:all 0.3s; }
    .stat-card:hover { transform:translateY(-5px); box-shadow:0 4px 15px rgba(0,0,0,0.15); }
    .stat-icon { width:60px; height:60px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.8rem; margin-bottom:15px; }
    .stat-value { font-size:2.5rem; font-weight:700; margin-bottom:5px; }
    .stat-label { color:#6c757d; font-size:0.95rem; }
    .stat-trend { font-size:0.85rem; margin-top:10px; }
    .trend-up { color:#27ae60; }
    .trend-down { color:#e74c3c; }
    .chart-card { background:white; border-radius:8px; padding:25px; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:20px; }
    .chart-title { font-size:1.2rem; font-weight:600; margin-bottom:20px; color:#2c3e50; }
    .filter-card { background:white; border-radius:8px; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:20px; }

    /* ===== PROFIL DROPDOWN ===== */
    .profile-dropdown { position:relative; cursor:pointer; }
    .profile-trigger { display:flex; align-items:center; gap:1rem; padding:0.5rem 1rem; background:#f8f9fa; border-radius:50px; transition:all 0.3s; border:1px solid #e9ecef; }
    .profile-trigger:hover { background:#f1f5f9; border-color:#667eea; }
    .profile-avatar { width:45px; height:45px; background:linear-gradient(135deg,#667eea,#764ba2); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:1.2rem; }
    .profile-info { text-align:right; }
    .profile-name { font-weight:700; color:#2c3e50; font-size:0.95rem; }
    .profile-role { font-size:0.75rem; color:#6c757d; }
    .dropdown-icon { color:#6c757d; transition:transform 0.3s; }
    .profile-dropdown.active .dropdown-icon { transform:rotate(180deg); }
    .dropdown-menu-custom { position:absolute; top:calc(100% + 10px); right:0; width:280px; background:white; border-radius:16px; box-shadow:0 10px 40px rgba(0,0,0,0.15); opacity:0; visibility:hidden; transform:translateY(-10px); transition:all 0.3s; z-index:2000; }
    .profile-dropdown.active .dropdown-menu-custom { opacity:1; visibility:visible; transform:translateY(0); }
    .dropdown-header { padding:1.5rem; background:linear-gradient(135deg,#667eea,#764ba2); border-radius:16px 16px 0 0; color:white; text-align:center; }
    .dropdown-header .avatar-large { width:60px; height:60px; background:rgba(255,255,255,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem; font-size:1.8rem; font-weight:700; }
    .dropdown-header .user-email { font-size:0.8rem; opacity:0.9; }
    .dropdown-divider { height:1px; background:#e9ecef; margin:0.5rem 0; }
    .dropdown-item2 { display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1.5rem; color:#2c3e50; text-decoration:none; transition:all 0.2s; font-size:0.9rem; width:100%; text-align:left; background:none; border:none; cursor:pointer; font-family:inherit; }
    .dropdown-item2:hover { background:#f8f9fa; }
    .dropdown-item2.danger:hover { background:#fee2e2; color:#dc2626; }
</style>

<div class="d-flex">
    <div class="admin-sidebar text-white p-3">
        <div class="sidebar-nav-wrapper">
            <h5 class="mb-4 text-center">🇹🇬 e-Déclaration TG</h5>
            <nav class="nav flex-column">
                <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">📊 Tableau de bord</a>
                <a class="nav-link text-white" href="{{ route('admin.users.index') }}">👤 Gestion des Utilisateurs</a>
                <a class="nav-link text-white" href="{{ route('admin.types-pieces.index') }}">🪪 Types de Pièces</a>
                <a class="nav-link text-white" href="{{ route('admin.roles.index') }}">🔐 Rôles & Droits</a>
                <a class="nav-link text-white active" href="{{ route('admin.stats.index') }}">📈 Statistiques & Rapports</a>
            </nav>
        </div>
        <div class="logout-section">
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="logout-btn">🚪 Se déconnecter</button>
            </form>
        </div>
    </div>

    <div class="admin-content flex-fill">
        <div class="container-fluid">

        {{-- ===== HEADER AVEC PROFIL (identique au dashboard) ===== --}}
        <div style="background:white;border-radius:16px;padding:1rem 2rem;margin-bottom:2rem;box-shadow:0 2px 10px rgba(0,0,0,0.05);display:flex;justify-content:space-between;align-items:center;">
            <div>
                <h2 style="font-size:1.5rem;font-weight:700;color:#2c3e50;margin:0;">📈 Statistiques & Rapports</h2>
                <p style="color:#6c757d;margin:0.25rem 0 0;font-size:0.9rem;">Analyse des déclarations et indicateurs clés de la plateforme</p>
            </div>
            <div class="profile-dropdown" id="profileDropdown">
                <div class="profile-trigger" onclick="toggleDropdown()">
                    <div class="profile-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <div class="profile-info">
                        <div class="profile-name">{{ Auth::user()->name }}</div>
                        <div class="profile-role">Administrateur</div>
                    </div>
                    <svg class="dropdown-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="dropdown-menu-custom">
                    <div class="dropdown-header">
                        <div class="avatar-large">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <div style="font-weight:700;">{{ Auth::user()->name }}</div>
                        <div class="user-email">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.profile') }}" class="dropdown-item2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Mon profil
                    </a>
                    <div class="dropdown-divider"></div>
                   <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Voulez-vous vraiment vous déconnecter ?')">
    @csrf
    <button type="submit" class="logout-link">
        Déconnecter
    </button>
</form>
                </div>
            </div>
        </div>

            <div class="filter-card">
                <form method="GET" action="{{ route('admin.stats.index') }}" class="row g-3">
                    <div class="col-md-3"><label class="form-label">Date début</label><input type="date" name="date_debut" class="form-control" value="{{ request('date_debut', now()->subDays(30)->format('Y-m-d')) }}"></div>
                    <div class="col-md-3"><label class="form-label">Date fin</label><input type="date" name="date_fin" class="form-control" value="{{ request('date_fin', now()->format('Y-m-d')) }}"></div>
                    <div class="col-md-3"><label class="form-label">Type de pièce</label><select name="type_piece_id" class="form-select"><option value="">Tous les types</option>@foreach($typesPieces as $type)<option value="{{ $type->id }}" {{ request('type_piece_id')==$type->id?'selected':'' }}>{{ $type->nom }}</option>@endforeach</select></div>
                    <div class="col-md-3 d-flex align-items-end"><button type="submit" class="btn btn-primary me-2">Filtrer</button><a href="{{ route('admin.stats.index') }}" class="btn btn-secondary">Réinitialiser</a></div>
                </form>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 mb-3"><div class="stat-card"><div class="stat-icon" style="background:#e3f2fd;"><span style="color:#2196f3;">📋</span></div><div class="stat-value">{{ $stats['total_declarations'] }}</div><div class="stat-label">Total Déclarations</div><div class="stat-trend trend-up">↗ +{{ $stats['new_this_month'] }} ce mois</div></div></div>
                <div class="col-md-3 mb-3"><div class="stat-card"><div class="stat-icon" style="background:#fff3e0;"><span style="color:#ff9800;">⏳</span></div><div class="stat-value">{{ $stats['en_attente'] }}</div><div class="stat-label">En Attente</div><div class="stat-trend">{{ number_format(($stats['en_attente']/max($stats['total_declarations'],1))*100,1) }}% du total</div></div></div>
                <div class="col-md-3 mb-3"><div class="stat-card"><div class="stat-icon" style="background:#e8f5e9;"><span style="color:#4caf50;">✅</span></div><div class="stat-value">{{ $stats['validees'] }}</div><div class="stat-label">Validées</div><div class="stat-trend trend-up">{{ number_format(($stats['validees']/max($stats['total_declarations'],1))*100,1) }}% du total</div></div></div>
                <div class="col-md-3 mb-3"><div class="stat-card"><div class="stat-icon" style="background:#ffebee;"><span style="color:#f44336;">❌</span></div><div class="stat-value">{{ $stats['rejetees'] }}</div><div class="stat-label">Rejetées</div><div class="stat-trend">{{ number_format(($stats['rejetees']/max($stats['total_declarations'],1))*100,1) }}% du total</div></div></div>
            </div>

            <div class="row mb-4">
                <div class="col-md-8 mb-4"><div class="chart-card"><h5 class="chart-title">📊 Évolution des Déclarations (30 derniers jours)</h5><canvas id="declarationsChart" height="80"></canvas></div></div>
                <div class="col-md-4 mb-4"><div class="chart-card"><h5 class="chart-title">🎯 Répartition par Statut</h5><canvas id="statusChart"></canvas></div></div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 mb-4"><div class="chart-card"><h5 class="chart-title">🪪 Types de Pièces les Plus Déclarés</h5><canvas id="typesPiecesChart" height="100"></canvas></div></div>
                <div class="col-md-6 mb-4"><div class="chart-card"><h5 class="chart-title">👥 Top 10 Utilisateurs Actifs</h5><canvas id="usersChart" height="100"></canvas></div></div>
            </div>

            <div class="chart-card mb-4">
                <h5 class="chart-title">📑 Statistiques Détaillées par Type de Pièce</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light"><tr><th>Type de Pièce</th><th>Total</th><th>En Attente</th><th>Validées</th><th>Rejetées</th><th>Taux Validation</th><th>Délai Moyen</th></tr></thead>
                        <tbody>
                            @foreach($statsByType as $stat)
                            <tr>
                                <td><strong>{{ $stat['type'] }}</strong></td>
                                <td>{{ $stat['total'] }}</td>
                                <td><span class="badge bg-warning">{{ $stat['en_attente'] }}</span></td>
                                <td><span class="badge bg-success">{{ $stat['validees'] }}</span></td>
                                <td><span class="badge bg-danger">{{ $stat['rejetees'] }}</span></td>
                                <td><div class="progress" style="height:20px;"><div class="progress-bar bg-success" style="width:{{ $stat['taux_validation'] }}%">{{ number_format($stat['taux_validation'],1) }}%</div></div></td>
                                <td>{{ $stat['delai_moyen'] }} jours</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="chart-card">
                <h5 class="chart-title">📆 Résumé des 12 Derniers Mois</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-dark"><tr><th>Mois</th><th>Déclarations</th><th>Validées</th><th>Rejetées</th><th>Taux</th><th>Évolution</th></tr></thead>
                        <tbody>
                            @foreach($monthlyStats as $month)
                            <tr>
                                <td><strong>{{ $month['mois'] }}</strong></td>
                                <td>{{ $month['total'] }}</td>
                                <td><span class="badge bg-success">{{ $month['validees'] }}</span></td>
                                <td><span class="badge bg-danger">{{ $month['rejetees'] }}</span></td>
                                <td>{{ number_format($month['taux_validation'],1) }}%</td>
                                <td>@if($month['evolution']>0)<span class="trend-up">↗ +{{ $month['evolution'] }}%</span>@elseif($month['evolution']<0)<span class="trend-down">↘ {{ $month['evolution'] }}%</span>@else<span>→ 0%</span>@endif</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const chartData = {!! json_encode($chartData) !!};
const commonOptions = { responsive:true, maintainAspectRatio:true, plugins:{ legend:{ display:true, position:'bottom' } } };
new Chart(document.getElementById('declarationsChart'), { type:'line', data:{ labels:chartData.evolution.labels, datasets:[{ label:'Déclarations', data:chartData.evolution.data, borderColor:'#2196f3', backgroundColor:'rgba(33,150,243,0.1)', borderWidth:2, fill:true, tension:0.4 }] }, options:commonOptions });
new Chart(document.getElementById('statusChart'), { type:'doughnut', data:{ labels:chartData.status.labels, datasets:[{ data:chartData.status.data, backgroundColor:['#ff9800','#4caf50','#f44336'], borderWidth:0 }] }, options:commonOptions });
new Chart(document.getElementById('typesPiecesChart'), { type:'bar', data:{ labels:chartData.types.labels, datasets:[{ label:'Déclarations', data:chartData.types.data, backgroundColor:'#9c27b0', borderRadius:5 }] }, options:{ ...commonOptions, indexAxis:'y', scales:{ x:{ beginAtZero:true } } } });
new Chart(document.getElementById('usersChart'), { type:'bar', data:{ labels:chartData.users.labels, datasets:[{ label:'Déclarations', data:chartData.users.data, backgroundColor:'#00bcd4', borderRadius:5 }] }, options:{ ...commonOptions, indexAxis:'y', scales:{ x:{ beginAtZero:true } } } });
</script>

<script>
function toggleDropdown() { document.getElementById('profileDropdown').classList.toggle('active'); }
document.addEventListener('click', function(e) {
    const d = document.getElementById('profileDropdown');
    if (d && !d.contains(e.target)) d.classList.remove('active');
});
</script>
@endsection