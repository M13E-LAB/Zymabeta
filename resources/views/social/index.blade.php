@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="container">
        <!-- En-tête du feed social -->
        <h1 class="section-title mb-4">Feed Social</h1>
        <p class="feed-subtitle">Découvrez les produits partagés par la communauté et participez aux ligues de nutrition</p>
        
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
                <div class="mb-2 mb-md-0">
                    <a href="{{ route('social.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Partager un produit
                    </a>
                </div>
                
                <!-- Filtres et tris -->
                <div class="d-flex flex-wrap gap-2">
                    <!-- Filtre par ligue -->
                    <div class="dropdown mb-2 mb-lg-0">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" id="leagueDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-users me-1"></i> 
                            @if(request('league'))
                                {{ collect($userLeagues)->firstWhere('slug', request('league'))->name ?? 'Toutes les ligues' }}
                            @else
                                Toutes les ligues
                            @endif
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="leagueDropdown">
                            <li>
                                <a class="dropdown-item {{ !request('league') ? 'active' : '' }}" href="{{ route('social.feed', ['type' => request('type'), 'sort' => request('sort')]) }}">
                                    Toutes les ligues
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @foreach($userLeagues as $userLeague)
                                <li>
                                    <a class="dropdown-item {{ request('league') === $userLeague->slug ? 'active' : '' }}" 
                                       href="{{ route('social.feed', ['league' => $userLeague->slug, 'type' => request('type'), 'sort' => request('sort')]) }}">
                                        {{ $userLeague->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Tri des résultats -->
                    <div class="dropdown mb-2 mb-lg-0">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-sort me-1"></i> 
                            @switch(request('sort'))
                                @case('top_rated')
                                    Mieux notés
                                    @break
                                @case('most_liked')
                                    Plus aimés
                                    @break
                                @case('most_commented')
                                    Plus commentés
                                    @break
                                @default
                                    Plus récents
                            @endswitch
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                            <li>
                                <a class="dropdown-item {{ request('sort') == 'latest' || !request('sort') ? 'active' : '' }}" 
                                   href="{{ route('social.feed', ['type' => request('type'), 'league' => request('league'), 'sort' => 'latest']) }}">
                                    <i class="fas fa-clock me-1"></i> Plus récents
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request('sort') == 'top_rated' ? 'active' : '' }}" 
                                   href="{{ route('social.feed', ['type' => request('type'), 'league' => request('league'), 'sort' => 'top_rated']) }}">
                                    <i class="fas fa-star me-1"></i> Mieux notés
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request('sort') == 'most_liked' ? 'active' : '' }}" 
                                   href="{{ route('social.feed', ['type' => request('type'), 'league' => request('league'), 'sort' => 'most_liked']) }}">
                                    <i class="fas fa-heart me-1"></i> Plus aimés
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request('sort') == 'most_commented' ? 'active' : '' }}" 
                                   href="{{ route('social.feed', ['type' => request('type'), 'league' => request('league'), 'sort' => 'most_commented']) }}">
                                    <i class="fas fa-comment me-1"></i> Plus commentés
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Filtres par type de post -->
            <div class="col-12 mt-3">
                <div class="filter-tabs">
                    <a href="{{ route('social.feed', ['sort' => request('sort'), 'league' => request('league')]) }}" 
                       class="filter-btn {{ !request('type') ? 'active' : '' }}">
                        <i class="fas fa-th-large me-1"></i> Tous
                    </a>
                    <a href="{{ route('social.feed', ['type' => 'price', 'sort' => request('sort'), 'league' => request('league')]) }}" 
                       class="filter-btn {{ request('type') === 'price' ? 'active' : '' }}">
                        <i class="fas fa-tag me-1"></i> Prix
                    </a>
                    <a href="{{ route('social.feed', ['type' => 'deal', 'sort' => request('sort'), 'league' => request('league')]) }}" 
                       class="filter-btn {{ request('type') === 'deal' ? 'active' : '' }}">
                        <i class="fas fa-percent me-1"></i> Promos
                    </a>
                    <a href="{{ route('social.feed', ['type' => 'meal', 'sort' => request('sort'), 'league' => request('league')]) }}" 
                       class="filter-btn {{ request('type') === 'meal' ? 'active' : '' }}">
                        <i class="fas fa-utensils me-1"></i> Repas
                    </a>
                    <a href="{{ route('social.feed', ['type' => 'review', 'sort' => request('sort'), 'league' => request('league')]) }}" 
                       class="filter-btn {{ request('type') === 'review' ? 'active' : '' }}">
                        <i class="fas fa-star me-1"></i> Avis
                    </a>
                </div>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif
        
        <!-- Affichage dans un grid à 3 colonnes sur grand écran -->
        <div class="row">
            <!-- Colonne de gauche : Feed principal -->
            <div class="col-lg-9">
                <div class="row">
                    @forelse($posts as $post)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card profile-card h-100">
                                <div class="post-image-container">
                                    <img src="{{ $post->image }}" class="post-image" alt="{{ $post->product_name }}">
                                    <span class="post-type-badge">
                                        @switch($post->post_type)
                                            @case('price')
                                                <i class="fas fa-tag me-1"></i> Prix
                                                @break
                                            @case('deal')
                                                <i class="fas fa-percent me-1"></i> Promo
                                                @break
                                            @case('meal')
                                                <i class="fas fa-utensils me-1"></i> Repas
                                                @break
                                            @case('review')
                                                <i class="fas fa-star me-1"></i> Avis
                                                @break
                                        @endswitch
                                    </span>
                                    
                                    @if($post->regular_price && $post->getSavingsPercentage() > 0)
                                        <span class="discount-badge">
                                            -{{ $post->getSavingsPercentage() }}%
                                        </span>
                                    @endif
                                    
                                    @if($post->post_type === 'meal' && $post->hasScore())
                                        <span class="score-badge" style="background-color: {{ $post->score_color }};">
                                            {{ $post->mealScore->total_score }}/100
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="card-body">
                                    <div class="post-author">
                                        @if($post->user->avatar)
                                            <img src="{{ $post->user->avatar }}" alt="Avatar" class="author-avatar">
                                        @else
                                            <div class="author-avatar-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="author-name">{{ $post->user->name }}</div>
                                            <div class="post-date">{{ $post->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    
                                    <h5 class="post-title">{{ $post->product_name }}</h5>
                                    <h6 class="store-name">{{ $post->store_name }}</h6>
                                    
                                    @if($post->post_type === 'meal' && $post->hasScore())
                                        <div class="meal-score-container mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Santé</span>
                                                <span>{{ $post->mealScore->health_score }}/100</span>
                                            </div>
                                            <div class="progress mb-2" style="height: 5px;">
                                                <div class="progress-bar" style="width: {{ $post->mealScore->health_score }}%"></div>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Visuel</span>
                                                <span>{{ $post->mealScore->visual_score }}/100</span>
                                            </div>
                                            <div class="progress mb-2" style="height: 5px;">
                                                <div class="progress-bar" style="width: {{ $post->mealScore->visual_score }}%"></div>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Diversité</span>
                                                <span>{{ $post->mealScore->diversity_score }}/100</span>
                                            </div>
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar" style="width: {{ $post->mealScore->diversity_score }}%"></div>
                                            </div>
                                        </div>
                                    @elseif($post->post_type !== 'meal')
                                        <div class="price-container">
                                            <span class="current-price">{{ number_format($post->price, 2) }} €</span>
                                            
                                            @if($post->regular_price)
                                                <span class="original-price">
                                                    {{ number_format($post->regular_price, 2) }} €
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    @if($post->description)
                                        <p class="post-description">{{ Str::limit($post->description, 100) }}</p>
                                    @endif
                                    
                                    <div class="post-actions">
                                        <div>
                                            <form action="{{ route('social.like', $post) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-action">
                                                    @if($post->likes()->where('user_id', auth()->id())->exists())
                                                        <i class="fas fa-heart liked"></i>
                                                    @else
                                                        <i class="far fa-heart"></i>
                                                    @endif
                                                    <span>{{ $post->likes_count }}</span>
                                                </button>
                                            </form>
                                            
                                            <a href="{{ route('social.show', $post) }}" class="btn btn-action ms-1">
                                                <i class="far fa-comment"></i>
                                                <span>{{ $post->comments_count }}</span>
                                            </a>
                                            
                                            @if($post->post_type === 'meal' && !$post->hasScore())
                                                <a href="{{ route('social.show', $post) }}" class="btn btn-action ms-1" title="Noter ce repas">
                                                    <i class="fas fa-star text-warning"></i>
                                                </a>
                                            @endif
                                        </div>
                                        
                                        <a href="{{ route('social.show', $post) }}" class="btn btn-view">
                                            Voir plus <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="card profile-card">
                                <div class="card-body empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-stream"></i>
                                    </div>
                                    <h3>Aucune publication pour le moment</h3>
                                    <p>Soyez le premier à partager un produit !</p>
                                    <a href="{{ route('social.create') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus-circle me-2"></i> Partager un produit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $posts->appends(['type' => request('type'), 'league' => request('league'), 'sort' => request('sort')])->links() }}
                </div>
            </div>
            
            <!-- Colonne de droite : Classement et infos des ligues -->
            <div class="col-lg-3 d-none d-lg-block">
                <!-- Top des utilisateurs du site -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-crown me-2 text-warning"></i> Top Mangeurs</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($topUsers as $index => $topUser)
                                <li class="list-group-item bg-transparent text-white">
                                    <div class="d-flex align-items-center">
                                        <div class="position-number me-2">{{ $index + 1 }}</div>
                                        @if($topUser->avatar)
                                            <img src="{{ $topUser->avatar }}" alt="Avatar" class="avatar-small me-2">
                                        @else
                                            <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
                                        @endif
                                        <div>
                                            <div class="user-name">{{ $topUser->name }}</div>
                                            <div class="user-score">{{ $topUser->points }} pts</div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('leaderboard.global') }}" class="btn btn-outline-light btn-sm w-100">
                            Voir le classement complet
                        </a>
                    </div>
                </div>
                
                <!-- Mes ligues -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-users me-2"></i> Mes ligues</h5>
                    </div>
                    <div class="card-body">
                        @if(count($userLeagues) > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($userLeagues as $userLeague)
                                    <li class="list-group-item bg-transparent">
                                        <a href="{{ route('leagues.show', $userLeague->slug) }}" class="text-white text-decoration-none">
                                            {{ $userLeague->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">Vous n'avez pas encore rejoint de ligue.</p>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('leagues.index') }}" class="btn btn-outline-light btn-sm w-100">
                            Gérer mes ligues
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles généraux */
.profile-container {
    background-color: #000;
    color: #fff;
    padding: 2rem 0;
    min-height: calc(100vh - 70px);
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #fff;
}

.feed-subtitle {
    color: #999;
    margin-bottom: 2rem;
}

/* Cartes */
.profile-card {
    background-color: #111;
    border: 1px solid #222;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 1.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s, box-shadow 0.3s;
}

.profile-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.card-body {
    padding: 1.5rem;
}

/* Dropdown */
.dropdown-menu {
    background-color: #111;
    border: 1px solid #333;
}

.dropdown-item {
    color: #fff;
}

.dropdown-item:hover, .dropdown-item.active {
    background-color: #222;
    color: #fff;
}

.dropdown-divider {
    border-color: #333;
}

/* Filtres */
.filter-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 1rem;
}

.filter-btn {
    background-color: #1a1a1a;
    border: 1px solid #333;
    color: #ccc;
    padding: 0.6rem 1rem;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s;
    text-decoration: none;
}

.filter-btn:hover {
    background-color: #222;
    color: #fff;
}

.filter-btn.active {
    background-color: #E67E22;
    border-color: #E67E22;
    color: #fff;
}

/* Score badge */
.score-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: white;
    color: black;
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 8px;
    font-size: 0.85rem;
    z-index: 2;
}

/* Meal score styles */
.meal-score-container {
    background-color: rgba(255, 255, 255, 0.05);
    padding: 10px 15px;
    border-radius: 10px;
    margin-top: 10px;
    font-size: 0.9rem;
}

.position-number {
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #222;
    border-radius: 50%;
    font-weight: bold;
    font-size: 0.8rem;
}

.user-name {
    font-weight: 500;
}

.user-score {
    font-size: 0.85rem;
    color: #999;
}

.gap-2 {
    gap: 0.5rem;
}

/* Styles spécifiques au flux social */
.post-image-container {
    position: relative;
    max-height: 350px;
    overflow: hidden;
    border-radius: 12px;
    margin-bottom: 1rem;
}

.post-image {
    width: 100%;
    height: auto;
    max-height: 350px;
    object-fit: cover;
}

.author-avatar {
    width: 36px;
    height: 36px;
    object-fit: cover;
    border-radius: 50%;
}

.author-avatar-placeholder {
    width: 36px;
    height: 36px;
    background-color: #4F46E5;
    color: white;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}
</style>
@endsection 