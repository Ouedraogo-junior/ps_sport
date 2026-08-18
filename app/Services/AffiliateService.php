<?php
namespace App\Services;

use App\Models\Abonnement;
use App\Models\AffiliateBonus;
use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\SoldeAffiliation;
use App\Models\TransactionAffiliation;
use Illuminate\Support\Facades\DB;

class AffiliateService
{
    public function traiterAffiliation(Abonnement $abonnement): void
    {
        $filleul = $abonnement->user;

        if (!$filleul->referred_by) return;
        if (Referral::where('abonnement_id', $abonnement->id)->exists()) return;

        $parrain = $filleul->parrain;
        if (!$parrain || $parrain->isBloque()) return;

        DB::transaction(function () use ($parrain, $filleul, $abonnement) {

            // Récupérer ou créer le solde affiliation
            $solde = SoldeAffiliation::firstOrCreate(
                ['user_id' => $parrain->id],
                ['solde' => 0, 'total_cumule' => 0, 'filleuls_depuis_retrait' => 0]
            );

            // Incrémenter le compteur
            $solde->increment('filleuls_depuis_retrait');
            $solde->refresh();

            $nbFilleuls = $solde->filleuls_depuis_retrait;

            // Chercher un palier correspondant exactement au compteur actuel
            $palier = AffiliateBonus::actifs()
                ->paliers()
                ->where('seuil', $nbFilleuls)
                ->first();

            // Enregistrer le referral
            Referral::create([
                'parrain_id'     => $parrain->id,
                'filleul_id'     => $filleul->id,
                'abonnement_id'  => $abonnement->id,
                'bonus_filleul'  => 0, // plus utilisé
                'bonus_palier'   => $palier ? $palier->montant : 0,
                'palier_atteint' => $palier ? $palier->seuil : null,
                'statut'         => 'credite',
            ]);

            // Créditer uniquement si palier atteint
            if ($palier) {
                $solde->increment('solde', $palier->montant);
                $solde->increment('total_cumule', $palier->montant);

                TransactionAffiliation::create([
                    'user_id'        => $parrain->id,
                    'type'           => 'credit_palier',
                    'montant'        => $palier->montant,
                    'palier_atteint' => $palier->seuil,
                    'description'    => "Palier {$palier->seuil} filleuls atteint",
                ]);
            }
        });
    }

    public function genererCodeSiAbsent(int $userId): void
    {
        ReferralCode::firstOrCreate(
            ['user_id' => $userId],
            ['code' => $this->genererCode(), 'actif' => true]
        );
    }

    /**
     * Appelé après un retrait validé — remet le compteur à zéro
     */
    public function reinitialiserCompteur(int $userId): void
    {
        SoldeAffiliation::where('user_id', $userId)
            ->update(['filleuls_depuis_retrait' => 0]);
    }

    private function genererCode(): string
    {
        do {
            $code = 'ZEN-' . strtoupper(substr(uniqid(), -6));
        } while (ReferralCode::where('code', $code)->exists());
        return $code;
    }
}