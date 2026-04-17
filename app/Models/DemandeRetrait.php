<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeRetrait extends Model
{
    protected $table = 'demandes_retrait';

    protected $fillable = [
        'user_id',
        'montant',
        'operateur',
        'numero_telephone',
        'statut',
        'traite_par',
        'note_admin',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function traitePar()
    {
        return $this->belongsTo(User::class, 'traite_par');
    }

    // Valider la demande — débite le solde + enregistre la transaction
    public function valider(int $adminId): void
    {
        //dd($adminId);
        $solde = SoldeInvestissement::pourUser($this->user_id);
        $solde->debiter($this->montant);

        TransactionSolde::enregistrerRetrait($this->user_id, $this->montant);

        $this->update([
            'statut'     => 'valide',
            'traite_par' => $adminId,
        ]);
    }

    // Rejeter la demande
    public function rejeter(int $adminId, ?string $note = null): void
    {
        $this->update([
            'statut'     => 'rejete',
            'traite_par' => $adminId,
            'note_admin' => $note,
        ]);
    }

    // Montant formaté
    public function montantFormate(): string
    {
        return number_format($this->montant, 2, ',', ' ') . ' FCFA';
    }

    // Label opérateur
    public function operateurLabel(): string
    {
        return match($this->operateur) {
            'orange' => 'Orange Money',
            'moov'   => 'Moov Money',
            'wave'   => 'Wave',
            default  => $this->operateur,
        };
    }

    // Badge statut
    public function statutBadge(): array
    {
        return match($this->statut) {
            'en_attente' => ['label' => 'En attente', 'class' => 'bg-yellow-100 text-yellow-800'],
            'valide'     => ['label' => 'Validé',     'class' => 'bg-green-100 text-green-800'],
            'rejete'     => ['label' => 'Rejeté',     'class' => 'bg-red-100 text-red-800'],
            default      => ['label' => $this->statut, 'class' => 'bg-gray-100 text-gray-800'],
        };
    }

    // Scopes
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeValides($query)
    {
        return $query->where('statut', 'valide');
    }
}