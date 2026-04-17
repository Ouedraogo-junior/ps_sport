<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'nom',
        'slug',
        'prix',
        'duree_jours',
        'actif',
        'est_investissement',
        'taux_journalier',
        'seuil_retrait',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'est_investissement' => 'boolean',
        'prix'  => 'integer',
        'taux_journalier'    => 'decimal:2',
    ];

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------
    public function prixFormate(): string
    {
        return number_format($this->prix, 0, '.', ' ') . ' XOF';
    }

    // Gain journalier formaté
    public function gainJournalierFormate(): string
    {
        return number_format($this->gainJournalier(), 2, ',', ' ') . ' FCFA/jour';
    }
 
    // Gain total sur toute la durée de l'abonnement
    public function gainTotal(): float
    {
        return round($this->gainJournalier() * $this->duree_jours, 2);
    }
 
    // Gain total formaté
    public function gainTotalFormate(): string
    {
        return number_format($this->gainTotal(), 2, ',', ' ') . ' FCFA';
    }
 
    // Seuil de retrait formaté
    public function seuilRetraitFormate(): string
    {
        if (!$this->seuil_retrait) return 'Non défini';
        return number_format($this->seuil_retrait, 0, ',', ' ') . ' FCFA';
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    // Scope pour ne récupérer que les plans actifs
    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    // Scope pour ne récupérer que les plans d'investissement
    public function scopeInvestissement($query)
    {
        return $query->where('actif', true)->where('est_investissement', true);
    }

        // Calcule le gain journalier en FCFA à partir du taux_journalier (%)
    public function gainJournalier(): float
    {
        if (!$this->est_investissement || !$this->taux_journalier) {
            return 0;
        }
 
        return round(($this->prix * $this->taux_journalier) / 100, 2);
    }
    
}