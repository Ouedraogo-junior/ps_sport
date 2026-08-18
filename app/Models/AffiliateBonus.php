<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateBonus extends Model
{
    protected $fillable = ['type', 'seuil', 'montant', 'actif'];

    protected $casts = [
        'montant' => 'float',
        'actif'   => 'boolean',
    ];

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeParFilleul($query)
    {
        return $query->where('type', 'par_filleul');
    }

    public function scopePaliers($query)
    {
        return $query->where('type', 'palier')->orderBy('seuil');
    }

    public function montantFormate(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }
}