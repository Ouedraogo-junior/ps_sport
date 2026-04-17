<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionSolde extends Model
{
    protected $table = 'transactions_solde';

    protected $fillable = [
        'user_id',
        'type',
        'montant',
        'description',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Enregistre un crédit journalier
    public static function enregistrerCredit(int $userId, float $montant, string $description): self
    {
        return self::create([
            'user_id'     => $userId,
            'type'        => 'credit',
            'montant'     => $montant,
            'description' => $description,
        ]);
    }

    // Enregistre un retrait
    public static function enregistrerRetrait(int $userId, float $montant): self
    {
        return self::create([
            'user_id'     => $userId,
            'type'        => 'retrait',
            'montant'     => $montant,
            'description' => 'Retrait via Mobile Money',
        ]);
    }

    // Montant formaté
    public function montantFormate(): string
    {
        return number_format($this->montant, 2, ',', ' ') . ' FCFA';
    }

    // Scope credits uniquement
    public function scopeCredits($query)
    {
        return $query->where('type', 'credit');
    }

    // Scope retraits uniquement
    public function scopeRetraits($query)
    {
        return $query->where('type', 'retrait');
    }
}