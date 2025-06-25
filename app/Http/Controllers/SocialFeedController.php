<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PointTransaction;
use App\Models\League;
use App\Models\User;

class SocialFeedController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display the social feed with filters for meal leagues.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Filtres
        $type = $request->input('type');
        $league = $request->input('league');
        $sort = $request->input('sort', 'latest');
        
        // Préparer la requête de base
        $query = Post::with(['user', 'likes', 'mealScore']);
        
        // Filtrer par type si spécifié
        if ($type) {
            $query->where('post_type', $type);
        }
        
        // Filtrer par ligue si spécifiée
        if ($league) {
            $leagueModel = League::where('slug', $league)->first();
            if ($leagueModel) {
                $memberIds = $leagueModel->members()->pluck('users.id')->toArray();
                $query->whereIn('user_id', $memberIds);
            }
        }
        
        // Tri des résultats
        switch ($sort) {
            case 'top_rated':
                $query->whereHas('mealScore')
                    ->orderByDesc(function ($q) {
                        $q->select('total_score')
                            ->from('meal_scores')
                            ->whereColumn('post_id', 'posts.id');
                    });
                break;
            case 'most_liked':
                $query->orderByDesc('likes_count');
                break;
            case 'most_commented':
                $query->orderByDesc('comments_count');
                break;
            case 'latest':
            default:
                $query->orderByDesc('created_at');
                break;
        }
        
        // Récupérer les posts paginés
        $posts = $query->paginate(10);
        
        // Récupérer les ligues de l'utilisateur pour le filtre
        $userLeagues = [];
        if (Auth::check()) {
            $userLeagues = Auth::user()->leagues()->get();
        }
        
        // Récupérer les top mangeurs (classement global)
        $topUsers = User::orderByDesc('points')->limit(5)->get();
        
        return view('social.index', compact('posts', 'userLeagues', 'topUsers', 'type', 'league', 'sort'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return view('social.create');
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'store_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'regular_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'post_type' => 'required|in:price,deal,meal,review',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);
        
        $post = new Post();
        $post->user_id = Auth::id();
        $post->product_name = $request->product_name;
        $post->store_name = $request->store_name;
        $post->price = $request->price;
        $post->regular_price = $request->regular_price;
        $post->description = $request->description;
        $post->post_type = $request->post_type;
        
        // Traitement de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/posts'), $filename);
            $post->image = '/uploads/posts/' . $filename;
        }
        
        // Utilisation facultative de la géolocalisation
        if ($request->has('latitude') && $request->has('longitude')) {
            $post->location = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $request->address
            ];
        }
        
        // Date d'expiration pour les offres
        if ($request->post_type === 'deal' && $request->has('expires_at')) {
            $post->expires_at = $request->expires_at;
        }
        
        $post->save();
        
        // Attribution de points pour la publication
        PointTransaction::awardPoints(Auth::id(), 'post_created', 'Publication dans le feed social');
        
        return redirect()->route('social.feed')
            ->with('success', 'Votre publication a été partagée avec succès !');
    }

    /**
     * Display a specific post.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'comments.user']);
        return view('social.show', compact('post'));
    }

    /**
     * Like or unlike a post.
     */
    public function like(Post $post)
    {
        $user_id = Auth::id();
        
        // Vérifier si l'utilisateur a déjà aimé ce post
        $liked = $post->likes()->where('user_id', $user_id)->exists();
        
        if ($liked) {
            // Unlike
            $post->unlikeBy($user_id);
            $message = 'Like retiré';
        } else {
            // Like
            $post->likeBy($user_id);
            
            // Si ce n'est pas son propre post, attribuer des points au créateur
            if ($post->user_id !== $user_id) {
                PointTransaction::awardPoints($post->user_id, 'post_liked', 'Publication aimée');
            }
            
            $message = 'Publication aimée';
        }
        
        if (request()->ajax()) {
            return response()->json([
                'liked' => !$liked,
                'likes_count' => $post->likes_count,
                'message' => $message
            ]);
        }
        
        return back()->with('success', $message);
    }

    /**
     * Add a comment to a post.
     */
    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);
        
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->content = $request->content;
        
        $post->comments()->save($comment);
        $post->increment('comments_count');
        
        // Si ce n'est pas son propre post, attribuer des points au créateur
        if ($post->user_id !== Auth::id()) {
            PointTransaction::awardPoints($post->user_id, 'post_commented', 'Commentaire reçu sur une publication');
        }
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => $comment->load('user'),
                'comments_count' => $post->comments_count
            ]);
        }
        
        return back()->with('success', 'Commentaire ajouté');
    }

    /**
     * Show the form for editing a post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\View\View
     */
    public function edit(Post $post)
    {
        // Vérifier que l'utilisateur est bien le propriétaire du post
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('social.feed')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette publication.');
        }
        
        return view('social.edit', compact('post'));
    }

    /**
     * Update the specified post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Post $post)
    {
        // Vérifier que l'utilisateur est bien le propriétaire du post
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('social.feed')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette publication.');
        }
        
        $request->validate([
            'product_name' => 'required|string|max:255',
            'store_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'regular_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
        ]);
        
        $post->product_name = $request->product_name;
        $post->store_name = $request->store_name;
        $post->price = $request->price;
        $post->regular_price = $request->regular_price;
        $post->description = $request->description;
        
        // Traitement de l'image si une nouvelle est fournie
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($post->image && file_exists(public_path(str_replace('/uploads', 'uploads', $post->image)))) {
                unlink(public_path(str_replace('/uploads', 'uploads', $post->image)));
            }
            
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/posts'), $filename);
            $post->image = '/uploads/posts/' . $filename;
        }
        
        // Date d'expiration pour les offres
        if ($post->post_type === 'deal' && $request->has('expires_at')) {
            $post->expires_at = $request->expires_at;
        }
        
        $post->save();
        
        return redirect()->route('profile.posts')
            ->with('success', 'Votre publication a été mise à jour avec succès !');
    }

    /**
     * Remove the specified post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $post)
    {
        // Vérifier que l'utilisateur est bien le propriétaire du post
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('social.feed')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer cette publication.');
        }
        
        // Supprimer l'image si elle existe
        if ($post->image && file_exists(public_path(str_replace('/uploads', 'uploads', $post->image)))) {
            unlink(public_path(str_replace('/uploads', 'uploads', $post->image)));
        }
        
        // Supprimer le post
        $post->delete();
        
        return redirect()->route('profile.posts')
            ->with('success', 'Votre publication a été supprimée avec succès !');
    }
}
