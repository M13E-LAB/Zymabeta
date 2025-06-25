<?php

namespace App\Http\Controllers;

use App\Models\League;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LeagueController extends Controller
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
     * Display a listing of the user's leagues.
     */
    public function index()
    {
        $userLeagues = Auth::user()->leagues()->with('creator')->get();
        $createdLeagues = Auth::user()->createdLeagues()->with('creator')->get();
        
        // Classement global des meilleurs mangeurs
        $globalLeaderboard = User::orderByDesc('points')
            ->limit(20)
            ->get();
        
        return view('leagues.index', compact('userLeagues', 'createdLeagues', 'globalLeaderboard'));
    }

    /**
     * Show the form for creating a new league.
     */
    public function create()
    {
        return view('leagues.create');
    }

    /**
     * Store a newly created league.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_private' => 'boolean',
            'max_members' => 'required|integer|min:2|max:100',
        ]);
        
        $league = new League();
        $league->name = $request->name;
        $league->slug = Str::slug($request->name) . '-' . Str::random(5);
        $league->description = $request->description;
        $league->created_by = Auth::id();
        $league->invite_code = Str::random(10);
        $league->is_private = $request->is_private ?? true;
        $league->max_members = $request->max_members;
        $league->save();
        
        // Ajouter le créateur comme membre et admin
        $league->addMember(Auth::user(), 'admin');
        
        return redirect()->route('leagues.show', $league->slug)
            ->with('success', 'Votre ligue a été créée avec succès ! Partagez le code d\'invitation pour que vos amis puissent rejoindre.');
    }

    /**
     * Display the specified league.
     */
    public function show($slug)
    {
        $league = League::where('slug', $slug)
            ->with(['creator', 'members' => function($query) {
                $query->orderBy('league_members.position', 'asc');
            }])
            ->firstOrFail();
        
        // Vérifier si l'utilisateur est membre ou si la ligue est publique
        $isMember = $league->members()->where('user_id', Auth::id())->exists();
        
        if (!$isMember && $league->is_private && $league->created_by !== Auth::id()) {
            return redirect()->route('leagues.index')
                ->with('error', 'Vous n\'avez pas accès à cette ligue privée.');
        }
        
        // Récupérer les classements pour différentes périodes
        $weeklyLeaderboard = $league->getLeaderboard('weekly');
        $monthlyLeaderboard = $league->getLeaderboard('monthly');
        $overallLeaderboard = $league->getLeaderboard('total');
        
        return view('leagues.show', compact(
            'league', 
            'isMember',
            'weeklyLeaderboard',
            'monthlyLeaderboard',
            'overallLeaderboard'
        ));
    }

    /**
     * Join a league using an invitation code.
     */
    public function join(Request $request)
    {
        $request->validate([
            'invite_code' => 'required|string|max:20',
        ]);
        
        $league = League::where('invite_code', $request->invite_code)->first();
        
        if (!$league) {
            return back()->with('error', 'Code d\'invitation invalide.');
        }
        
        // Vérifier si la ligue a atteint son nombre maximum de membres
        if ($league->members()->count() >= $league->max_members) {
            return back()->with('error', 'Cette ligue a atteint son nombre maximum de membres.');
        }
        
        // Ajouter l'utilisateur comme membre
        $added = $league->addMember(Auth::user());
        
        if ($added) {
            return redirect()->route('leagues.show', $league->slug)
                ->with('success', 'Vous avez rejoint la ligue avec succès !');
        } else {
            return back()->with('error', 'Vous êtes déjà membre de cette ligue.');
        }
    }

    /**
     * Leave a league.
     */
    public function leave($slug)
    {
        $league = League::where('slug', $slug)->firstOrFail();
        
        // Vérifier si l'utilisateur est membre
        $isMember = $league->members()->where('user_id', Auth::id())->exists();
        
        if (!$isMember) {
            return back()->with('error', 'Vous n\'êtes pas membre de cette ligue.');
        }
        
        // Vérifier si l'utilisateur est le créateur et si la ligue a d'autres membres
        if ($league->created_by === Auth::id() && $league->members()->count() > 1) {
            return back()->with('error', 'En tant que créateur, vous ne pouvez pas quitter la ligue tant qu\'il y a d\'autres membres. Transférez les droits d\'administrateur à un autre membre ou supprimez la ligue.');
        }
        
        // Retirer l'utilisateur de la ligue
        $league->removeMember(Auth::user());
        
        // Si c'était le dernier membre et le créateur, supprimer la ligue
        if ($league->members()->count() === 0 && $league->created_by === Auth::id()) {
            $league->delete();
            return redirect()->route('leagues.index')
                ->with('success', 'Vous avez quitté et supprimé la ligue car vous étiez le dernier membre.');
        }
        
        return redirect()->route('leagues.index')
            ->with('success', 'Vous avez quitté la ligue avec succès.');
    }

    /**
     * Update the role of a member in a league.
     */
    public function updateMemberRole(Request $request, $slug, $userId)
    {
        $league = League::where('slug', $slug)->firstOrFail();
        
        // Vérifier si l'utilisateur actuel est admin de la ligue
        $isAdmin = $league->members()
            ->where('user_id', Auth::id())
            ->wherePivot('role', 'admin')
            ->exists();
        
        if (!$isAdmin) {
            return back()->with('error', 'Vous n\'avez pas les droits d\'administration pour cette ligue.');
        }
        
        $request->validate([
            'role' => 'required|in:member,admin',
        ]);
        
        // Mettre à jour le rôle du membre
        $league->members()->updateExistingPivot($userId, ['role' => $request->role]);
        
        return back()->with('success', 'Le rôle du membre a été mis à jour.');
    }

    /**
     * Remove a member from a league.
     */
    public function removeMember($slug, $userId)
    {
        $league = League::where('slug', $slug)->firstOrFail();
        
        // Vérifier si l'utilisateur actuel est admin de la ligue
        $isAdmin = $league->members()
            ->where('user_id', Auth::id())
            ->wherePivot('role', 'admin')
            ->exists();
        
        if (!$isAdmin) {
            return back()->with('error', 'Vous n\'avez pas les droits d\'administration pour cette ligue.');
        }
        
        // Récupérer l'utilisateur à retirer
        $user = User::findOrFail($userId);
        
        // Vérifier que ce n'est pas le créateur
        if ($league->created_by === (int)$userId) {
            return back()->with('error', 'Vous ne pouvez pas retirer le créateur de la ligue.');
        }
        
        // Retirer l'utilisateur de la ligue
        $league->removeMember($user);
        
        return back()->with('success', 'Le membre a été retiré de la ligue.');
    }

    /**
     * Global leaderboard
     */
    public function globalLeaderboard()
    {
        // Classement des utilisateurs par points
        $topUsers = User::orderByDesc('points')
            ->paginate(20);
        
        // Classement des utilisateurs par leurs repas
        $topMealUsers = User::select('users.*')
            ->selectRaw('(SELECT COUNT(*) FROM posts WHERE posts.user_id = users.id AND posts.post_type = "meal" AND EXISTS (SELECT * FROM meal_scores WHERE meal_scores.post_id = posts.id)) as meal_count')
            ->selectRaw('(SELECT AVG(meal_scores.total_score) FROM posts JOIN meal_scores ON posts.id = meal_scores.post_id WHERE posts.user_id = users.id AND posts.post_type = "meal" AND posts.deleted_at IS NULL) as average_meal_score')
            ->whereRaw('(SELECT COUNT(*) FROM posts WHERE posts.user_id = users.id AND posts.post_type = "meal" AND EXISTS (SELECT * FROM meal_scores WHERE meal_scores.post_id = posts.id)) > 0')
            ->orderByDesc('average_meal_score')
            ->paginate(20);
        
        return view('leagues.global', compact('topUsers', 'topMealUsers'));
    }
} 