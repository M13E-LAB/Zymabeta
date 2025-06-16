<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class League extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'created_by',
        'invite_code',
        'is_private',
        'max_members',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_private' => 'boolean',
        'max_members' => 'integer',
        'last_score_update' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($league) {
            if (empty($league->slug)) {
                $league->slug = Str::slug($league->name);
            }
            
            if (empty($league->invite_code)) {
                $league->invite_code = Str::random(10);
            }
        });
    }

    /**
     * Get the creator of the league.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the members of the league.
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'league_members')
            ->using(LeagueMember::class)
            ->withPivot('weekly_score', 'monthly_score', 'total_score', 'position', 'role', 'last_score_update')
            ->withTimestamps();
    }

    /**
     * Get the admins of the league.
     */
    public function admins()
    {
        return $this->members()->wherePivot('role', 'admin');
    }

    /**
     * Add a user to the league.
     */
    public function addMember(User $user, $role = 'member')
    {
        // Vérifier si l'utilisateur n'est pas déjà membre
        if (!$this->members()->where('user_id', $user->id)->exists()) {
            $this->members()->attach($user->id, [
                'role' => $role,
                'last_score_update' => now(),
            ]);
            
            // Recalculer les positions après l'ajout
            $this->recalculatePositions();
            
            return true;
        }
        
        return false;
    }

    /**
     * Remove a user from the league.
     */
    public function removeMember(User $user)
    {
        $removed = $this->members()->detach($user->id) > 0;
        
        if ($removed) {
            // Recalculer les positions après le retrait
            $this->recalculatePositions();
        }
        
        return $removed;
    }

    /**
     * Update a member's scores.
     */
    public function updateMemberScore(User $user, $weeklyDelta = 0, $monthlyDelta = 0, $totalDelta = 0)
    {
        $member = $this->members()->where('user_id', $user->id)->first();
        
        if ($member) {
            $pivot = $member->pivot;
            
            $pivot->weekly_score += $weeklyDelta;
            $pivot->monthly_score += $monthlyDelta;
            $pivot->total_score += $totalDelta;
            $pivot->last_score_update = now();
            $pivot->save();
            
            // Recalculer les positions après la mise à jour
            $this->recalculatePositions();
            
            return true;
        }
        
        return false;
    }

    /**
     * Recalculate the positions of all members.
     */
    public function recalculatePositions()
    {
        // Récupérer tous les membres triés par score total décroissant
        $sortedMembers = $this->members()
            ->orderByDesc('league_members.total_score')
            ->get();
        
        // Mettre à jour les positions
        $position = 1;
        foreach ($sortedMembers as $member) {
            $this->members()->updateExistingPivot($member->id, ['position' => $position]);
            $position++;
        }
    }

    /**
     * Reset the weekly scores of all members.
     */
    public function resetWeeklyScores()
    {
        $this->members()->update(['league_members.weekly_score' => 0]);
    }

    /**
     * Reset the monthly scores of all members.
     */
    public function resetMonthlyScores()
    {
        $this->members()->update(['league_members.monthly_score' => 0]);
    }

    /**
     * Get the league's leaderboard.
     */
    public function getLeaderboard($period = 'total', $limit = 10)
    {
        $scoreColumn = 'league_members.total_score';
        
        if ($period === 'weekly') {
            $scoreColumn = 'league_members.weekly_score';
        } elseif ($period === 'monthly') {
            $scoreColumn = 'league_members.monthly_score';
        }
        
        return $this->members()
            ->orderByDesc($scoreColumn)
            ->limit($limit)
            ->get();
    }
} 