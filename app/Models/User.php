<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'telephone',
        'nom',
        'password',
        'role',
        'statut',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function getAuthIdentifierName(): string
    {
        return 'telephone';
    }

    public function getAuthPassword(): string
    {
        return $this->password;
    }

    // -------------------------------------------------------
    // Helpers rôle
    // -------------------------------------------------------

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBloque(): bool
    {
        return $this->statut === 'bloque';
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function abonnements(): HasMany
    {
        return $this->hasMany(Abonnement::class);
    }

    // Abonnement actuellement actif (un seul à la fois)
    public function abonnementActif(): HasOne
    {
        return $this->hasOne(Abonnement::class)
            ->where('statut', 'actif')
            ->where('date_fin', '>=', now())
            ->latestOfMany();
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function accessCodes(): HasMany
    {
        return $this->hasMany(AccessCode::class);
    }

    // Coupons créés par cet admin
    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class, 'cree_par');
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }
}