<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'niveau_risque',
        'description',
        'analyse',
        'statut_publication',
        'statut_resultat',
        'cree_par',
        'publie_le',
        'capture_gagnant',
    ];

    protected $casts = [
        'publie_le' => 'datetime',
    ];

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isPublie(): bool
    {
        return $this->statut_publication === 'publie';
    }

    public function publier(): void
    {
        $this->update([
            'statut_publication' => 'publie',
            'publie_le'          => now(),
        ]);
    }

    public function depublier(): void
    {
        $this->update(['statut_publication' => 'depublie']);
    }

    // Label lisible du niveau de risque
    public function niveauRisqueLabel(): string
    {
        return match($this->niveau_risque) {
            'faible'  => 'Faible risque',
            'modere'  => 'Risque modéré',
            'risque'  => 'Risqué',
            default   => $this->niveau_risque,
        };
    }

    // Classe CSS Tailwind pour le badge de niveau de risque
    public function niveauRisqueBadge(): string
    {
        return match($this->niveau_risque) {
            'faible' => 'bg-green-100 text-green-800',
            'modere' => 'bg-orange-100 text-orange-800',
            'risque' => 'bg-red-100 text-red-800',
            default  => 'bg-gray-100 text-gray-800',
        };
    }

    // Classe CSS Tailwind pour le badge de résultat
    public function statutResultatBadge(): string
    {
        return match($this->statut_resultat) {
            'gagne'      => 'bg-green-100 text-green-800',
            'perdu'      => 'bg-red-100 text-red-800',
            'en_cours'   => 'bg-blue-100 text-blue-800',
            'annule'     => 'bg-gray-100 text-gray-800',
            default      => 'bg-yellow-100 text-yellow-800',
        };
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function creePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function codes(): HasMany
    {
        return $this->hasMany(CouponCode::class);
    }

    public function selections(): HasMany
    {
        return $this->hasMany(Selection::class);
    }

    // Récupère le code pour un bookmaker donné
    public function codeBookmaker(string $bookmaker): ?CouponCode
    {
        return $this->codes->firstWhere('bookmaker', $bookmaker);
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopePublies($query)
    {
        return $query->where('statut_publication', 'publie');
    }

    public function scopeDuJour($query)
    {
        return $query->whereDate('publie_le', today());
    }

    public function scopeParNiveauRisque($query, string $niveau)
    {
        return $query->where('niveau_risque', $niveau);
    }
}