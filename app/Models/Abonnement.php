<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Abonnement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan',
        'date_debut',
        'date_fin',
        'statut',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin'   => 'datetime',
    ];

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isActif(): bool
    {
        return $this->statut === 'actif' && $this->date_fin >= now();
    }

    public function joursRestants(): int
    {
        if (!$this->isActif()) return 0;
        return (int) now()->diffInDays($this->date_fin);
    }

    // Calcule la date de fin selon le plan choisi
    public static function calculerDateFin(string $planSlug, Carbon $debut = null): Carbon
    {
        $debut = $debut ?? now();

        $plan = \App\Models\Plan::where('slug', $planSlug)->first();

        if ($plan) {
            return $debut->copy()->addDays($plan->duree_jours);
        }

        // Fallback si plan introuvable
        return $debut->copy()->addDays(30);
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif')
                     ->where('date_fin', '>=', now());
    }

    public function scopeExpires($query)
    {
        return $query->where('date_fin', '<', now());
    }
}