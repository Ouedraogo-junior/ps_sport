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
    ];

    protected $casts = [
        'actif' => 'boolean',
        'prix'  => 'integer',
    ];

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------
    public function prixFormate(): string
    {
        return number_format($this->prix, 0, '.', ' ') . ' XOF';
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------
    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }
}