@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="container">
        <!-- En-tête de la page des ligues -->
        <h1 class="section-title mb-4">Ligues de Nutrition</h1>
        <p class="feed-subtitle">Comparez vos habitudes alimentaires avec celles de vos amis et des autres utilisateurs</p>
        
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('leagues.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Créer une nouvelle ligue
                    </a>
                </div>
                
                <div>
                    <a href="{{ route('leaderboard.global') }}" class="btn btn-secondary">
                        <i class="fas fa-list-ol me-2"></i> Classement global
                    </a>
                </div>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger mb-4">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            </div>
        @endif
        
        <!-- Rejoindre une ligue existante -->
        <div class="card mb-4">
            <div class="card-body">
                <h4><i class="fas fa-sign-in-alt me-2"></i> Rejoindre une ligue</h4>
                <p>Entrez le code d'invitation partagé par vos amis pour rejoindre leur ligue</p>
                
                <form action="{{ route('leagues.join') }}" method="POST" class="d-flex">
                    @csrf
                    <input type="text" name="invite_code" class="form-control me-2" placeholder="Code d'invitation">
                    <button type="submit" class="btn btn-primary">Rejoindre</button>
                </form>
            </div>
        </div>
        
        <!-- Mes ligues -->
        <h3 class="mb-3"><i class="fas fa-users me-2"></i> Mes ligues</h3>
        
        <div class="row">
            @forelse($userLeagues as $league)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="card-title">{{ $league->name }}</h4>
                            <p class="text-muted">Créée par {{ $league->creator->name }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-users me-1"></i> {{ $league->members()->count() }} membres
                                </span>
                                
                                @if($league->is_private)
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-lock me-1"></i> Privée
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-globe me-1"></i> Publique
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Ma position dans cette ligue -->
                            @php
                                $myPosition = $league->members()
                                    ->where('user_id', auth()->id())
                                    ->first()
                                    ->pivot
                                    ->position ?? 0;
                            @endphp
                            
                            <div class="league-position-indicator mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Ma position :</span>
                                    <span class="badge bg-primary rounded-pill">{{ $myPosition }}</span>
                                </div>
                            </div>
                            
                            <a href="{{ route('leagues.show', $league->slug) }}" class="btn btn-outline-light w-100">
                                Voir le classement
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                            <h4>Vous n'avez pas encore rejoint de ligue</h4>
                            <p class="text-muted">Créez une ligue ou rejoignez-en une avec le code d'invitation</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Classement global (aperçu) -->
        <h3 class="mb-3 mt-5"><i class="fas fa-trophy me-2"></i> Classement global</h3>
        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Utilisateur</th>
                                <th>Score</th>
                                <th>Niveau</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($globalLeaderboard as $index => $user)
                                <tr @if($user->id === auth()->id()) class="bg-dark" @endif>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($user->avatar)
                                                <img src="{{ $user->avatar }}" alt="Avatar" class="avatar-small me-2">
                                            @else
                                                <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
                                            @endif
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td>{{ $user->points }}</td>
                                    <td>{{ $user->level_title }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-3">
                    <a href="{{ route('leaderboard.global') }}" class="btn btn-outline-light">
                        <i class="fas fa-list-ol me-2"></i> Voir le classement complet
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.league-position-indicator {
    background-color: rgba(255, 255, 255, 0.05);
    padding: 10px 15px;
    border-radius: 10px;
}
</style>
@endsection 