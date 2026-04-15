<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Selection extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'equipe_domicile',
        'equipe_exterieur',
        'competition',
        'date_match',
        'type_pari',
        'cote',
        'api_match_id',
        'score_final',
        'statut',
    ];

    protected $casts = [
        'date_match' => 'datetime',
        'cote'       => 'float',
    ];

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    // Affichage du match : "PSG vs OM"
    public function matchLabel(): string
    {
        if ($this->equipe_domicile && $this->equipe_exterieur) {
            return "{$this->equipe_domicile} vs {$this->equipe_exterieur}";
        }
        return 'Match non renseigné';
    }

    // Classe CSS Tailwind pour le statut
    public function statutBadge(): string
    {
        return match($this->statut) {
            'gagne'    => 'bg-green-100 text-green-800',
            'perdu'    => 'bg-red-100 text-red-800',
            'en_cours' => 'bg-blue-100 text-blue-800',
            'annule'   => 'bg-gray-100 text-gray-800',
            default    => 'bg-yellow-100 text-yellow-800',
        };
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeAvecApiId($query)
    {
        return $query->whereNotNull('api_match_id');
    }
}