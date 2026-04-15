<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AccessCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'genere_par',
        'plan',
        'expire_le',
        'statut',
        'est_payant',
    ];

    protected $casts = [
        'expire_le' => 'datetime',
    ];

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    // Génère un code unique au format ACC-XXXXXXXX
    public static function generer(): string
    {
        do {
            $code = 'ACC-' . strtoupper(Str::random(8));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    // Génère un code manuel pour un plan donné, sans utilisateur associé

    public static function genererManuel(string $plan, int $adminId, bool $estPayant = false): self
    {
        return self::create([
            'code'       => self::generer(),
            'user_id'    => null,
            'genere_par' => $adminId,
            'plan'       => $plan,
            'expire_le'  => now()->addDays(30),
            'statut'     => 'actif',
            'est_payant' => $estPayant,
        ]);
    }

    public function isValide(): bool
    {
        return $this->statut === 'actif' && $this->expire_le >= now();
    }

    // Active le code : crée l'abonnement et marque le code comme utilisé
    public function activer(int $userId = null): Abonnement
    {
        $targetUserId = $userId ?? $this->user_id;

        $debut = now();
        $fin   = Abonnement::calculerDateFin($this->plan, $debut);

        Abonnement::where('user_id', $targetUserId)
            ->where('statut', 'actif')
            ->update(['statut' => 'expire']);

        $abonnement = Abonnement::create([
            'user_id'    => $targetUserId,
            'plan'       => $this->plan,
            'date_debut' => $debut,
            'date_fin'   => $fin,
            'statut'     => 'actif',
        ]);

        $this->update([
            'statut'  => 'utilise',
            'user_id' => $targetUserId,
        ]);

        return $abonnement;
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function generePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'genere_par');
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif')
                     ->where('expire_le', '>=', now());
    }

    public function scopeExpires($query)
    {
        return $query->where('statut', 'actif')
                     ->where('expire_le', '<', now());
    }
}