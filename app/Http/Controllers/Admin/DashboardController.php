<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Paiement;
use App\Models\User;
use App\Models\Abonnement;
use App\Models\AccessCode;
use App\Models\Plan;

class DashboardController extends Controller
{
    public function index()
    {
        // Paiements en attente
        $paiementsEnAttente = Paiement::enAttente()->count();

        // Abonnés actifs
        $abonnesActifs = Abonnement::actifs()->count();

        // Total utilisateurs (hors admins)
        $totalUtilisateurs = User::where('role', 'user')->count();

        // Coupons publiés aujourd'hui
        $couponsDuJour = Coupon::publies()->duJour()->count();

        // Revenus du mois
        $revenusFormulaire = Paiement::valides()
            ->whereMonth('traite_le', now()->month)
            ->whereYear('traite_le', now()->year)
            ->sum('montant');

        $revenusWhatsApp = AccessCode::where('est_payant', true)
            ->where('statut', 'utilise')  // ← plus whereIn
            ->whereMonth('access_codes.created_at', now()->month)
            ->whereYear('access_codes.created_at', now()->year)
            ->join('plans', 'access_codes.plan', '=', 'plans.slug')
            ->sum('plans.prix');

        $revenusMois = $revenusFormulaire + $revenusWhatsApp;

        // Taux de réussite
        $couponsTermines = Coupon::whereIn('statut_resultat', ['gagne', 'perdu'])
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();

        $couponsGagnes = Coupon::where('statut_resultat', 'gagne')
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();

        $tauxReussite = $couponsTermines > 0
            ? round(($couponsGagnes / $couponsTermines) * 100)
            : 0;

        // Derniers paiements en attente
        $derniersPaiements = Paiement::with('user')
            ->enAttente()
            ->latest()
            ->take(5)
            ->get();

        // Derniers coupons
        $derniersCoupons = Coupon::with('codes')
            ->latest()
            ->take(5)
            ->get();

        // Historique transactions
        $paiementsValides = Paiement::with('user')
            ->valides()
            ->latest('traite_le')
            ->take(10)
            ->get()
            ->map(fn($p) => [
                'date'    => $p->traite_le,
                'user'    => $p->user->nom ?? $p->user->telephone,
                'montant' => $p->montant,
                'plan'    => $p->plan,
                'source'  => 'formulaire',
            ]);

        $codesPayants = AccessCode::with(['user', 'generePar'])
            ->where('est_payant', true)
            ->where('statut', 'utilise')  // ← plus whereIn
            ->join('plans', 'access_codes.plan', '=', 'plans.slug')
            ->select('access_codes.*', 'plans.prix as montant_plan')
            ->latest('access_codes.created_at')
            ->take(10)
            ->get()
            ->map(fn($c) => [
                'date'    => $c->created_at,
                'user'    => $c->user->nom ?? $c->user->telephone ?? 'Non attribué',
                'montant' => $c->montant_plan,
                'plan'    => $c->plan,
                'source'  => 'whatsapp',
            ]);

        $historiqueTransactions = $paiementsValides
            ->concat($codesPayants)
            ->sortByDesc('date')
            ->take(10)
            ->values();

        return view('admin.dashboard', compact(
            'paiementsEnAttente',
            'abonnesActifs',
            'totalUtilisateurs',
            'couponsDuJour',
            'revenusMois',
            'tauxReussite',
            'couponsGagnes',
            'couponsTermines',
            'derniersPaiements',
            'derniersCoupons',
            'historiqueTransactions',
        ));
    }
}