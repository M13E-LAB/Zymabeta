@extends('layouts.app')

@section('content')
<div class="analytics-dashboard">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="analytics-header">
                    <h1><i class="fas fa-chart-line"></i> Analytics ZYMA</h1>
                    <p>Dashboard d'analyse des données utilisateur et d'utilisation</p>
                    <div class="header-actions">
                        <button onclick="refreshData()" class="btn btn-primary">
                            <i class="fas fa-sync"></i> Actualiser
                        </button>
                        <a href="{{ route('analytics.export-users') }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Exporter CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-content">
                        <h3>{{ number_format($stats['total_users']) }}</h3>
                        <p>Utilisateurs Total</p>
                        <span class="stats-change">+{{ $stats['new_users_today'] }} aujourd'hui</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <div class="stats-content">
                        <h3>{{ number_format($stats['total_posts']) }}</h3>
                        <p>Publications Total</p>
                        <span class="stats-change">+{{ $stats['posts_today'] }} aujourd'hui</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stats-content">
                        <h3>{{ number_format($stats['total_comments']) }}</h3>
                        <p>Commentaires Total</p>
                        <span class="stats-change">Engagement actif</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stats-content">
                        <h3>{{ number_format($stats['total_points_awarded']) }}</h3>
                        <p>Points Distribués</p>
                        <span class="stats-change">Gamification</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="chart-card">
                    <h4><i class="fas fa-chart-line"></i> Inscriptions (30 jours)</h4>
                    <canvas id="registrationChart" height="300"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-card">
                    <h4><i class="fas fa-chart-pie"></i> Répartition des Points</h4>
                    <canvas id="pointsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-8">
                <div class="chart-card">
                    <h4><i class="fas fa-chart-bar"></i> Activité Quotidienne (7 jours)</h4>
                    <canvas id="activityChart" height="250"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="chart-card">
                    <h4><i class="fas fa-chart-donut"></i> Types de Posts</h4>
                    <canvas id="postTypesChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="table-card">
                    <h4><i class="fas fa-trophy"></i> Top Points</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topUsers['top_points'] as $user)
                                <tr>
                                    <td>
                                        <a href="{{ route('analytics.user-details', $user->id) }}">
                                            {{ $user->name }}
                                        </a>
                                    </td>
                                    <td><span class="badge badge-primary">{{ $user->points ?? 0 }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="table-card">
                    <h4><i class="fas fa-fire"></i> Top Contributeurs</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Posts</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topUsers['top_posters'] as $user)
                                <tr>
                                    <td>
                                        <a href="{{ route('analytics.user-details', $user->id) }}">
                                            {{ $user->name }}
                                        </a>
                                    </td>
                                    <td><span class="badge badge-success">{{ $user->posts_count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="table-card">
                    <h4><i class="fas fa-user-plus"></i> Nouveaux Membres</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Inscription</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topUsers['recent_joiners'] as $user)
                                <tr>
                                    <td>
                                        <a href="{{ route('analytics.user-details', $user->id) }}">
                                            {{ $user->name }}
                                        </a>
                                    </td>
                                    <td><small>{{ $user->created_at->diffForHumans() }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Beta Stats -->
        <div class="row">
            <div class="col-12">
                <div class="beta-stats-card">
                    <h4><i class="fas fa-vial"></i> Statistiques Beta</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="beta-stat">
                                <div class="beta-number">{{ $stats['beta_codes_used'] }}</div>
                                <div class="beta-label">Codes Utilisés</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="beta-stat">
                                <div class="beta-number">{{ $stats['beta_codes_available'] }}</div>
                                <div class="beta-label">Codes Disponibles</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="beta-stat">
                                <div class="beta-number">{{ round(($stats['beta_codes_used'] / max(($stats['beta_codes_used'] + $stats['beta_codes_available']), 1)) * 100) }}%</div>
                                <div class="beta-label">Taux d'Adoption</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="beta-stat">
                                <div class="beta-number">{{ $stats['new_users_week'] }}</div>
                                <div class="beta-label">Nouveaux cette semaine</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #667eea;
    --secondary-color: #764ba2;
    --success-color: #56ab2f;
    --warning-color: #f093fb;
    --danger-color: #ff6b6b;
    --dark-color: #2c3e50;
    --light-color: #f8f9fa;
}

.analytics-dashboard {
    padding: 2rem 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

.analytics-header {
    background: rgba(255, 255, 255, 0.95);
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.analytics-header h1 {
    color: var(--dark-color);
    margin: 0;
    font-weight: 700;
}

.analytics-header h1 i {
    color: var(--primary-color);
    margin-right: 0.5rem;
}

.analytics-header p {
    color: #6c757d;
    margin: 0.5rem 0 0 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.stats-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    transition: transform 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
}

.stats-icon {
    font-size: 2.5rem;
    margin-right: 1rem;
    color: var(--primary-color);
    width: 60px;
    text-align: center;
}

.stats-content h3 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark-color);
    margin: 0;
}

.stats-content p {
    color: #6c757d;
    margin: 0.2rem 0;
    font-weight: 600;
}

.stats-change {
    font-size: 0.8rem;
    color: var(--success-color);
    font-weight: 600;
}

.chart-card, .table-card, .beta-stats-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem;
    height: 100%;
}

.chart-card h4, .table-card h4, .beta-stats-card h4 {
    color: var(--dark-color);
    margin-bottom: 1.5rem;
    font-weight: 700;
    display: flex;
    align-items: center;
}

.chart-card h4 i, .table-card h4 i, .beta-stats-card h4 i {
    color: var(--primary-color);
    margin-right: 0.5rem;
}

.table {
    margin: 0;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: var(--dark-color);
    background: rgba(102, 126, 234, 0.1);
}

.table td {
    vertical-align: middle;
}

.table a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
}

.table a:hover {
    color: var(--secondary-color);
}

.badge {
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
}

.badge-primary {
    background: var(--primary-color);
}

.badge-success {
    background: var(--success-color);
}

.beta-stats-card {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border: 1px solid rgba(102, 126, 234, 0.2);
}

.beta-stat {
    text-align: center;
    padding: 1rem;
}

.beta-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color);
}

.beta-label {
    color: var(--dark-color);
    font-weight: 600;
    margin-top: 0.5rem;
}

.btn {
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
}

.btn-success {
    background: var(--success-color);
    color: white;
}

.btn-success:hover {
    background: #4e9a2e;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .analytics-header {
        flex-direction: column;
        text-align: center;
    }
    
    .header-actions {
        margin-top: 1rem;
        justify-content: center;
    }
    
    .stats-card {
        flex-direction: column;
        text-align: center;
    }
    
    .stats-icon {
        margin-right: 0;
        margin-bottom: 1rem;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Configuration des couleurs
const colors = {
    primary: '#667eea',
    secondary: '#764ba2',
    success: '#56ab2f',
    warning: '#f093fb',
    danger: '#ff6b6b'
};

// Graphique des inscriptions
const registrationCtx = document.getElementById('registrationChart').getContext('2d');
const registrationChart = new Chart(registrationCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($userAnalytics['registration_trend']->pluck('date')) !!},
        datasets: [{
            label: 'Nouvelles inscriptions',
            data: {!! json_encode($userAnalytics['registration_trend']->pluck('count')) !!},
            borderColor: colors.primary,
            backgroundColor: colors.primary + '20',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Graphique de répartition des points
const pointsCtx = document.getElementById('pointsChart').getContext('2d');
const pointsChart = new Chart(pointsCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($userAnalytics['points_distribution'])) !!},
        datasets: [{
            data: {!! json_encode(array_values($userAnalytics['points_distribution'])) !!},
            backgroundColor: [
                colors.primary,
                colors.secondary,
                colors.success,
                colors.warning,
                colors.danger
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Graphique d'activité quotidienne
const activityCtx = document.getElementById('activityChart').getContext('2d');
const activityChart = new Chart(activityCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($activityData['posts_per_day']->pluck('date')) !!},
        datasets: [{
            label: 'Publications',
            data: {!! json_encode($activityData['posts_per_day']->pluck('count')) !!},
            backgroundColor: colors.primary + '80',
            borderColor: colors.primary,
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Graphique des types de posts
const postTypesCtx = document.getElementById('postTypesChart').getContext('2d');
const postTypesChart = new Chart(postTypesCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode(array_keys($activityData['post_types'])) !!},
        datasets: [{
            data: {!! json_encode(array_values($activityData['post_types'])) !!},
            backgroundColor: [
                colors.primary,
                colors.success,
                colors.warning,
                colors.danger
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Fonction pour actualiser les données
function refreshData() {
    fetch('/analytics/api/stats')
        .then(response => response.json())
        .then(data => {
            // Mise à jour des stats en temps réel
            console.log('Stats actualisées:', data);
            // Vous pouvez ici mettre à jour les éléments de la page
        })
        .catch(error => {
            console.error('Erreur lors de l\'actualisation:', error);
        });
}

// Actualisation automatique toutes les 5 minutes
setInterval(refreshData, 300000);
</script>
@endsection 