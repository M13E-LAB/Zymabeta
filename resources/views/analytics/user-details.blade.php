@extends('layouts.app')

@section('content')
<div class="user-details-page">
    <div class="container">
        <!-- Header avec retour -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="details-header">
                    <a href="{{ route('analytics.dashboard') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Retour au Dashboard
                    </a>
                    <h1><i class="fas fa-user-chart"></i> Profil Analytique</h1>
                    <p>Analyse détaillée de l'utilisateur {{ $user->name }}</p>
                </div>
            </div>
        </div>

        <!-- Informations de base -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="user-info-card">
                    <div class="user-avatar">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}">
                        @else
                            <div class="avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <h3>{{ $user->name }}</h3>
                    <p class="user-email">{{ $user->email }}</p>
                    <div class="user-badges">
                        <span class="badge badge-primary">
                            @if($user->email_verified_at)
                                <i class="fas fa-check"></i> Vérifié
                            @else
                                <i class="fas fa-times"></i> Non vérifié
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-box">
                            <div class="stat-number">{{ $userStats['total_points'] }}</div>
                            <div class="stat-label">Points Total</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <div class="stat-number">{{ $userStats['total_posts'] }}</div>
                            <div class="stat-label">Publications</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <div class="stat-number">{{ $userStats['total_comments'] }}</div>
                            <div class="stat-label">Commentaires</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box">
                            <div class="stat-number">{{ $userStats['registration_date']->diffInDays(now()) }}</div>
                            <div class="stat-label">Jours depuis inscription</div>
                        </div>
                    </div>
                </div>

                <div class="registration-info">
                    <h4><i class="fas fa-calendar"></i> Informations d'inscription</h4>
                    <p><strong>Date d'inscription :</strong> {{ $userStats['registration_date']->format('d/m/Y à H:i') }}</p>
                    <p><strong>Il y a :</strong> {{ $userStats['registration_date']->diffForHumans() }}</p>
                </div>
            </div>
        </div>

        <!-- Graphiques d'activité -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="chart-card">
                    <h4><i class="fas fa-chart-line"></i> Activité Mensuelle</h4>
                    <canvas id="monthlyActivityChart" height="300"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="activity-summary">
                    <h4><i class="fas fa-lightning"></i> Résumé d'Activité</h4>
                    <div class="activity-metrics">
                        <div class="metric">
                            <span class="metric-label">Moyenne posts/mois</span>
                            <span class="metric-value">{{ round($userStats['total_posts'] / max($userStats['registration_date']->diffInMonths(now()), 1), 1) }}</span>
                        </div>
                        <div class="metric">
                            <span class="metric-label">Ratio commentaires/posts</span>
                            <span class="metric-value">{{ $userStats['total_posts'] > 0 ? round($userStats['total_comments'] / $userStats['total_posts'], 1) : 0 }}</span>
                        </div>
                        <div class="metric">
                            <span class="metric-label">Points/post</span>
                            <span class="metric-value">{{ $userStats['total_posts'] > 0 ? round($userStats['total_points'] / $userStats['total_posts'], 1) : 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des points et publications récentes -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="table-card">
                    <h4><i class="fas fa-star"></i> Historique des Points (20 derniers)</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Points</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userStats['points_history'] as $transaction)
                                <tr>
                                    <td>
                                        <small>{{ $transaction->description ?? $transaction->action_type }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $transaction->points > 0 ? 'badge-success' : 'badge-danger' }}">
                                            {{ $transaction->points > 0 ? '+' : '' }}{{ $transaction->points }}
                                        </span>
                                    </td>
                                    <td><small>{{ $transaction->created_at->format('d/m H:i') }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Aucune transaction de points</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="table-card">
                    <h4><i class="fas fa-share-alt"></i> Publications Récentes (10 dernières)</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Contenu</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userStats['recent_posts'] as $post)
                                <tr>
                                    <td>
                                        <small>{{ Str::limit($post->product_name ?? $post->description, 30) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $post->post_type }}</span>
                                    </td>
                                    <td><small>{{ $post->created_at->format('d/m H:i') }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Aucune publication</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions administratives -->
        <div class="row">
            <div class="col-12">
                <div class="admin-actions">
                    <h4><i class="fas fa-tools"></i> Actions Administratives</h4>
                    <div class="action-buttons">
                        <button class="btn btn-warning" onclick="sendMessage()">
                            <i class="fas fa-envelope"></i> Envoyer un Message
                        </button>
                        <button class="btn btn-info" onclick="viewFullProfile()">
                            <i class="fas fa-eye"></i> Voir Profil Complet
                        </button>
                        <button class="btn btn-success" onclick="exportUserData()">
                            <i class="fas fa-download"></i> Exporter Données
                        </button>
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
    --info-color: #17a2b8;
    --dark-color: #2c3e50;
    --light-color: #f8f9fa;
}

.user-details-page {
    padding: 2rem 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

.details-header {
    background: rgba(255, 255, 255, 0.95);
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
    position: relative;
}

.btn-back {
    position: absolute;
    left: 2rem;
    top: 50%;
    transform: translateY(-50%);
    background: var(--primary-color);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: var(--secondary-color);
    color: white;
    text-decoration: none;
}

.details-header h1 {
    color: var(--dark-color);
    margin: 0;
    font-weight: 700;
}

.details-header h1 i {
    color: var(--primary-color);
    margin-right: 0.5rem;
}

.details-header p {
    color: #6c757d;
    margin: 0.5rem 0 0 0;
}

.user-info-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
    height: 100%;
}

.user-avatar {
    width: 100px;
    height: 100px;
    margin: 0 auto 1.5rem;
    border-radius: 50%;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
}

.user-info-card h3 {
    color: var(--dark-color);
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.user-email {
    color: #6c757d;
    margin-bottom: 1rem;
}

.stat-box {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin-bottom: 1rem;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color);
}

.stat-label {
    color: var(--dark-color);
    font-weight: 600;
    margin-top: 0.5rem;
}

.registration-info {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-top: 1rem;
}

.registration-info h4 {
    color: var(--dark-color);
    margin-bottom: 1rem;
    font-weight: 700;
}

.registration-info h4 i {
    color: var(--primary-color);
    margin-right: 0.5rem;
}

.chart-card, .table-card, .activity-summary, .admin-actions {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem;
    height: 100%;
}

.chart-card h4, .table-card h4, .activity-summary h4, .admin-actions h4 {
    color: var(--dark-color);
    margin-bottom: 1.5rem;
    font-weight: 700;
    display: flex;
    align-items: center;
}

.chart-card h4 i, .table-card h4 i, .activity-summary h4 i, .admin-actions h4 i {
    color: var(--primary-color);
    margin-right: 0.5rem;
}

.activity-metrics {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.metric {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: rgba(102, 126, 234, 0.1);
    border-radius: 8px;
}

.metric-label {
    color: var(--dark-color);
    font-weight: 600;
}

.metric-value {
    color: var(--primary-color);
    font-weight: 700;
    font-size: 1.2rem;
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

.badge-danger {
    background: var(--danger-color);
}

.badge-info {
    background: var(--info-color);
}

.action-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn {
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn-warning {
    background: var(--warning-color);
    color: white;
}

.btn-info {
    background: var(--info-color);
    color: white;
}

.btn-success {
    background: var(--success-color);
    color: white;
}

@media (max-width: 768px) {
    .btn-back {
        position: static;
        transform: none;
        margin-bottom: 1rem;
        display: inline-block;
    }
    
    .details-header {
        padding: 1.5rem;
        text-align: center;
    }
    
    .action-buttons {
        justify-content: center;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique d'activité mensuelle
const monthlyCtx = document.getElementById('monthlyActivityChart').getContext('2d');
const monthlyChart = new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($userStats['activity_by_month']->pluck('month')->map(function($month, $index) use ($userStats) {
            $year = $userStats['activity_by_month'][$index]->year;
            return date('M Y', mktime(0, 0, 0, $month, 1, $year));
        })) !!},
        datasets: [{
            label: 'Publications par mois',
            data: {!! json_encode($userStats['activity_by_month']->pluck('count')) !!},
            backgroundColor: '#667eea80',
            borderColor: '#667eea',
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

// Fonctions d'actions administratives
function sendMessage() {
    alert('Fonctionnalité de messagerie - À implémenter');
}

function viewFullProfile() {
    window.open('{{ route("profile.show") }}', '_blank');
}

function exportUserData() {
    // Créer un export CSV spécifique à cet utilisateur
    const userData = {
        user: @json($user),
        stats: @json($userStats)
    };
    
    const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(userData, null, 2));
    const downloadAnchorNode = document.createElement('a');
    downloadAnchorNode.setAttribute("href", dataStr);
    downloadAnchorNode.setAttribute("download", "user_{{ $user->id }}_analytics.json");
    document.body.appendChild(downloadAnchorNode);
    downloadAnchorNode.click();
    downloadAnchorNode.remove();
}
</script>
@endsection 