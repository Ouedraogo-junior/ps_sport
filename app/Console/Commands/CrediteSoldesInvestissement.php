<?php

namespace App\Console\Commands;

use App\Models\Abonnement;
use App\Models\Plan;
use App\Models\SoldeInvestissement;
use App\Models\TransactionSolde;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CrediteSoldesInvestissement extends Command
{
    protected $signature   = 'investissement:crediter';
    protected $description = 'Crédite le gain journalier de chaque abonné à un plan investissement actif';

    public function handle(): void
    {
        // Récupère tous les abonnements actifs dont la date_fin est dans le futur
        $abonnements = Abonnement::with('user')
            ->where('statut', 'actif')
            ->where('date_fin', '>=', now())
            ->get();

        // Charge tous les plans investissement en mémoire pour éviter N+1
        $plans = Plan::where('est_investissement', true)
            ->where('actif', true)
            ->get()
            ->keyBy('slug');

        $credites = 0;

        foreach ($abonnements as $abonnement) {
            $plan = $plans->get($abonnement->plan);

            // Passe si le plan n'est pas investissement
            if (!$plan) {
                continue;
            }

            $gain = $plan->gainJournalier();

            if ($gain <= 0) {
                continue;
            }

            // Crédite le solde
            $solde = SoldeInvestissement::pourUser($abonnement->user_id);
            $solde->crediter($gain);

            // Enregistre la transaction
            TransactionSolde::enregistrerCredit(
                $abonnement->user_id,
                $gain,
                'Gain journalier — ' . now()->format('d/m/Y')
            );

            $credites++;
        }

        $this->info("Crédits effectués : {$credites}");
        Log::info("[investissement:crediter] {$credites} soldes crédités le " . now()->toDateString());
    }
}