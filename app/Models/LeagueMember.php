<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LeagueMember extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'weekly_score' => 'integer',
        'monthly_score' => 'integer',
        'total_score' => 'integer',
        'position' => 'integer',
        'last_score_update' => 'datetime',
    ];

    /**
     * Get the league that the member belongs to.
     */
    public function league()
    {
        return $this->belongsTo(League::class);
    }

    /**
     * Get the user that is a member of the league.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 