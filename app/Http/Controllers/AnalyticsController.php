<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\PointTransaction;
use App\Models\BetaInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        $stats = $this->getGeneralStats();
        $userAnalytics = $this->getUserAnalytics();
        $activityData = $this->getActivityData();
        $topUsers = $this->getTopUsers();
        
        return view('analytics.dashboard', compact('stats', 'userAnalytics', 'activityData', 'topUsers'));
    }

    /**
     * Get general application statistics.
     */
    private function getGeneralStats()
    {
        return [
            'total_users' => User::count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_week' => User::whereBetween('created_at', [now()->subWeek(), now()])->count(),
            'total_posts' => Post::count(),
            'posts_today' => Post::whereDate('created_at', today())->count(),
            'total_comments' => Comment::count(),
            'total_points_awarded' => PointTransaction::sum('points'),
            'beta_codes_used' => BetaInvitation::where('used', true)->count(),
            'beta_codes_available' => BetaInvitation::where('used', false)->count(),
        ];
    }

    /**
     * Get user analytics data.
     */
    private function getUserAnalytics()
    {
        // Répartition des utilisateurs par tranche de points
        $pointsDistribution = User::select(
            DB::raw('CASE 
                WHEN points = 0 THEN "0 points"
                WHEN points BETWEEN 1 AND 50 THEN "1-50 points"
                WHEN points BETWEEN 51 AND 100 THEN "51-100 points"
                WHEN points BETWEEN 101 AND 250 THEN "101-250 points"
                WHEN points > 250 THEN "250+ points"
                END as range'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('range')
        ->pluck('count', 'range')
        ->toArray();

        // Utilisateurs les plus actifs par publications
        $mostActiveUsers = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        // Évolution des inscriptions sur 30 jours
        $registrationTrend = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('created_at', [now()->subDays(30), now()])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return [
            'points_distribution' => $pointsDistribution,
            'most_active_users' => $mostActiveUsers,
            'registration_trend' => $registrationTrend,
        ];
    }

    /**
     * Get activity data for charts.
     */
    private function getActivityData()
    {
        // Posts par jour sur les 7 derniers jours
        $postsPerDay = Post::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('created_at', [now()->subDays(7), now()])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Répartition des types de posts
        $postTypes = Post::select('post_type', DB::raw('COUNT(*) as count'))
            ->groupBy('post_type')
            ->pluck('count', 'post_type')
            ->toArray();

        // Activité par heure (dernières 24h)
        $hourlyActivity = Post::select(
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('created_at', [now()->subDay(), now()])
        ->groupBy('hour')
        ->orderBy('hour')
        ->get();

        return [
            'posts_per_day' => $postsPerDay,
            'post_types' => $postTypes,
            'hourly_activity' => $hourlyActivity,
        ];
    }

    /**
     * Get top users by different metrics.
     */
    private function getTopUsers()
    {
        return [
            'top_points' => User::orderBy('points', 'desc')->take(10)->get(),
            'top_posters' => User::withCount('posts')->orderBy('posts_count', 'desc')->take(10)->get(),
            'recent_joiners' => User::latest()->take(10)->get(),
        ];
    }

    /**
     * Export user data as CSV.
     */
    public function exportUsers()
    {
        $users = User::with(['posts', 'pointTransactions'])->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users_export_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Nom',
                'Email',
                'Date d\'inscription',
                'Points',
                'Nombre de posts',
                'Dernière connexion',
                'Statut'
            ]);

            // Données des utilisateurs
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->points ?? 0,
                    $user->posts->count(),
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Jamais',
                    $user->email_verified_at ? 'Vérifié' : 'Non vérifié'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get detailed analytics for a specific user.
     */
    public function userDetails($userId)
    {
        $user = User::with(['posts', 'pointTransactions', 'comments'])->findOrFail($userId);
        
        $userStats = [
            'registration_date' => $user->created_at,
            'total_points' => $user->points ?? 0,
            'total_posts' => $user->posts->count(),
            'total_comments' => $user->comments->count(),
            'points_history' => $user->pointTransactions()->latest()->take(20)->get(),
            'recent_posts' => $user->posts()->latest()->take(10)->get(),
            'activity_by_month' => $user->posts()
                ->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get(),
        ];

        return view('analytics.user-details', compact('user', 'userStats'));
    }

    /**
     * API endpoint for real-time stats.
     */
    public function apiStats()
    {
        return response()->json([
            'users_online' => $this->getOnlineUsersCount(),
            'posts_today' => Post::whereDate('created_at', today())->count(),
            'comments_today' => Comment::whereDate('created_at', today())->count(),
            'points_awarded_today' => PointTransaction::whereDate('created_at', today())->sum('points'),
            'last_update' => now()->toISOString(),
        ]);
    }

    /**
     * Get count of online users (based on recent activity).
     */
    private function getOnlineUsersCount()
    {
        // Considérer comme "en ligne" les utilisateurs actifs dans les 15 dernières minutes
        return DB::table('sessions')
            ->where('last_activity', '>=', now()->subMinutes(15)->timestamp)
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count();
    }
} 