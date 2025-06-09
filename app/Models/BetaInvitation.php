<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BetaInvitation extends Model
{
    protected $fillable = [
        'code',
        'email',
        'used',
        'used_at',
        'user_id'
    ];

    protected $casts = [
        'used' => 'boolean',
        'used_at' => 'datetime'
    ];

    /**
     * Relation avec l'utilisateur qui a utilisé le code
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Générer un code d'invitation unique
     */
    public static function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Marquer le code comme utilisé
     */
    public function markAsUsed(User $user): void
    {
        $this->update([
            'used' => true,
            'used_at' => now(),
            'user_id' => $user->id
        ]);
    }

    /**
     * Vérifier si le code est valide
     */
    public function isValid(): bool
    {
        return !$this->used;
    }
} 