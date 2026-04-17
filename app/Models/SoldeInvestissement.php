<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoldeInvestissement extends Model
{
    protected $table = 'soldes_investissement';

    protected $fillable = [
        'user_id',
        'solde',
        'total_cumule',
    ];

    protected $casts = [
        'solde'        => 'decimal:2',
        'total_cumule' => 'decimal:2',
    ];

    // Relation
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Crédite le solde et met à jour le total cumulé
    public function crediter(float $montant): void
    {
        $this->solde        += $montant;
        $this->total_cumule += $montant;
        $this->save();
    }

    // Débite le solde lors d'un retrait validé
    public function debiter(float $montant): void
    {
        $this->solde = max(0, $this->solde - $montant);
        $this->save();
    }

    // Solde formaté en XOF
    public function soldeFormate(): string
    {
        return number_format($this->solde, 2, ',', ' ') . ' FCFA';
    }

    // Total cumulé formaté
    public function totalCumuleFormate(): string
    {
        return number_format($this->total_cumule, 2, ',', ' ') . ' FCFA';
    }

    // Récupère ou crée le solde d'un user
    public static function pourUser(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            ['solde' => 0, 'total_cumule' => 0]
        );
    }
}