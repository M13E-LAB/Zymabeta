@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-edit me-2"></i> Modifier votre partage
                        </h5>
                        <a href="{{ route('profile.posts') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour à mes partages
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
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
                    
                    <form action="{{ route('social.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label for="product_name" class="form-label">Nom du produit</label>
                            <input type="text" class="form-control @error('product_name') is-invalid @enderror" 
                                   id="product_name" name="product_name" value="{{ old('product_name', $post->product_name) }}" required>
                            @error('product_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="store_name" class="form-label">Nom du magasin</label>
                            <input type="text" class="form-control @error('store_name') is-invalid @enderror" 
                                   id="store_name" name="store_name" value="{{ old('store_name', $post->store_name) }}" required>
                            @error('store_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Prix actuel (€)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $post->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="regular_price" class="form-label">Prix habituel (€) <small class="text-muted">(optionnel)</small></label>
                                <input type="number" step="0.01" min="0" class="form-control @error('regular_price') is-invalid @enderror" 
                                       id="regular_price" name="regular_price" value="{{ old('regular_price', $post->regular_price) }}">
                                @error('regular_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        @if($post->post_type === 'deal')
                        <div class="mb-4">
                            <label for="expires_at" class="form-label">Date d'expiration <small class="text-muted">(optionnel)</small></label>
                            <input type="date" class="form-control @error('expires_at') is-invalid @enderror" 
                                   id="expires_at" name="expires_at" value="{{ old('expires_at', $post->expires_at ? $post->expires_at->format('Y-m-d') : '') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif
                        
                        <div class="mb-4">
                            <label for="description" class="form-label">Description <small class="text-muted">(optionnel)</small></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $post->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Image actuelle</label>
                            <div class="current-image-container">
                                @if($post->image)
                                    <img src="{{ $post->image }}" alt="{{ $post->product_name }}" class="img-fluid rounded mb-2" style="max-height: 200px;">
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i> Aucune image disponible
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="image" class="form-label">Nouvelle image <small class="text-muted">(optionnel)</small></label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">Laissez vide pour conserver l'image actuelle.</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('profile.posts') }}" class="btn btn-outline-secondary me-md-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 