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
        'referred_by', 
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
    // Relations existantes
    // -------------------------------------------------------
    public function abonnements(): HasMany
    {
        return $this->hasMany(Abonnement::class);
    }

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

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class, 'cree_par');
    }

    // -------------------------------------------------------
    // Relations affiliation
    // -------------------------------------------------------
    public function referralCode(): HasOne
    {
        return $this->hasOne(ReferralCode::class);
    }

    public function filleuls(): HasMany
    {
        return $this->hasMany(Referral::class, 'parrain_id');
    }

    public function parrain(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'referred_by');
    }

    public function soldeAffiliation(): HasOne
    {
        return $this->hasOne(SoldeAffiliation::class);
    }
    
    // -------------------------------------------------------
    // Helpers affiliation
    // -------------------------------------------------------
    public function nombreFilleulsActifs(): int
    {
        return $this->filleuls()->where('statut', 'credite')->count();
    }

    public function totalGainsAffiliation(): float
    {
        return $this->filleuls()
                    ->where('statut', 'credite')
                    ->selectRaw('SUM(bonus_filleul + bonus_palier) as total')
                    ->value('total') ?? 0;
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