@extends('layouts.agent')

@section('title', 'Statistiques - Agent')

@section('header-title', '📊 Statistiques')
@section('header-subtitle', 'Analyse des déclarations et documents trouvés')

@section('content')
<div class="container">
    <!-- Cartes récapitulatives -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total déclarations</h5>
                    <p class="card-text display-6">{{ $statuts['en_attente'] + $statuts['en_cours'] + $statuts['correspondance_trouvee'] + $statuts['restitue'] + $statuts['non_retrouve'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">En attente</h5>
                    <p class="card-text display-6">{{ $statuts['en_attente'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Restituées</h5>
                    <p class="card-text display-6">{{ $statuts['restitue'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Non retrouvées</h5>
                    <p class="card-text display-6">{{ $statuts['non_retrouve'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique des statuts -->
    <div class="card mb-4">
        <div class="card-header">
            <strong>Répartition des déclarations par statut</strong>
        </div>
        <div class="card-body">
            <canvas id="statusChart" height="300"></canvas>
        </div>
    </div>

    <!-- Graphique des déclarations par mois -->
    <div class="card">
        <div class="card-header">
            <strong>Évolution des déclarations (par mois)</strong>
        </div>
        <div class="card-body">
            <canvas id="monthlyChart" height="300"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Graphique des statuts
        const ctx1 = document.getElementById('statusChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['En attente', 'En cours', 'Correspondance trouvée', 'Restitué', 'Non retrouvé'],
                datasets: [{
                    label: 'Nombre de déclarations',
                    data: [
                        {{ $statuts['en_attente'] }},
                        {{ $statuts['en_cours'] }},
                        {{ $statuts['correspondance_trouvee'] }},
                        {{ $statuts['restitue'] }},
                        {{ $statuts['non_retrouve'] }}
                    ],
                    backgroundColor: [
                        '#f39c12',
                        '#3498db',
                        '#9b59b6',
                        '#27ae60',
                        '#7f8c8d'
                    ],
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { callbacks: { label: (ctx) => `${ctx.raw} déclaration(s)` } }
                }
            }
        });

        // Graphique mensuel
        const ctx2 = document.getElementById('monthlyChart').getContext('2d');
        const mois = @json($pertesParMois->pluck('mois'));
        const totaux = @json($pertesParMois->pluck('total'));

        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: mois,
                datasets: [{
                    label: 'Déclarations soumises',
                    data: totaux,
                    borderColor: '#f39c12',
                    backgroundColor: 'rgba(243, 156, 18, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#e67e22',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Nombre de déclarations' } },
                    x: { title: { display: true, text: 'Mois' } }
                }
            }
        });
    });
</script>
@endsection