<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan',
        'montant',
        'operateur',
        'statut',
        'capture_verifiee',
        'capture_path', 
        'note_admin',
        'motif_rejet',
        'traite_par',
        'traite_le',
    ];

    protected $casts = [
        'capture_verifiee' => 'boolean',
        'traite_le'        => 'datetime',
        'montant'          => 'integer',
    ];

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    public function isEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    // Valide le paiement, génère un code d'accès et le retourne
    public function valider(User $admin): AccessCode
    {
        $code = AccessCode::generer();

        $accessCode = AccessCode::create([
            'code'       => $code,
            'user_id'    => $this->user_id,
            'genere_par' => $admin->id,
            'plan'       => $this->plan,
            // Le code expire 48h après génération s'il n'est pas utilisé
            'expire_le'  => now()->addHours(48),
            'statut'     => 'actif',
        ]);

        $this->update([
            'statut'           => 'valide',
            'capture_verifiee' => true,
            'traite_par'       => $admin->id,
            'traite_le'        => now(),
        ]);

        return $accessCode;
    }

    // Rejette le paiement avec un motif
    public function rejeter(User $admin, string $motif = null): void
    {
        $this->update([
            'statut'      => 'rejete',
            'motif_rejet' => $motif,
            'traite_par'  => $admin->id,
            'traite_le'   => now(),
        ]);
    }

    // Montant formaté en XOF
    public function montantFormate(): string
    {
        return number_format($this->montant, 0, '.', ' ') . ' XOF';
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function traitePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'traite_par');
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeValides($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeRejetes($query)
    {
        return $query->where('statut', 'rejete');
    }
}