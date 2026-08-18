<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'parrain_id',
        'filleul_id',
        'abonnement_id',
        'bonus_filleul',
        'bonus_palier',
        'palier_atteint',
        'statut',
    ];

    public function parrain()
    {
        return $this->belongsTo(User::class, 'parrain_id');
    }

    public function filleul()
    {
        return $this->belongsTo(User::class, 'filleul_id');
    }

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class);
    }

    // Total crédité pour ce referral
    public function totalBonus(): float
    {
        return $this->bonus_filleul + $this->bonus_palier;
    }
}