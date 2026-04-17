<?php
namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Plan;
use App\Models\Abonnement;
use SEO;

class HomeController extends Controller
{
    public function index()
    {
        $plans = Plan::actifs()->get();

        $couponsTermines = Coupon::whereIn('statut_resultat', ['gagne', 'perdu'])
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();

        $couponsGagnes = Coupon::where('statut_resultat', 'gagne')
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();

        $tauxReussite = $couponsTermines > 0
            ? round(($couponsGagnes / $couponsTermines) * 100)
            : 0;

        $totalCoupons = Coupon::publies()->count();
        $totalAbonnes = Abonnement::actifs()->count();

        $captures = Coupon::whereNotNull('capture_gagnant')
                ->where('statut_resultat', 'gagne')
                ->orderByDesc('publie_le')
                ->limit(5)
                ->get();

        $totalCaptures = Coupon::whereNotNull('capture_gagnant')
                            ->where('statut_resultat', 'gagne')
                            ->count();

        $plansInvestissement = Plan::actifs()
                        ->where('est_investissement', true)
                        ->orderBy('prix')
                        ->get();

        // SEO
        SEO::setTitle('Pronostics Sportifs Premium — ' . config('app.name'));
        SEO::setDescription('Coupons sportifs analysés quotidiennement au Burkina Faso. Paiement via Orange Money et Moov Money.');
        SEO::opengraph()->setUrl(route('home'));

        return view('welcome', compact(
            'plans',
            'tauxReussite',
            'couponsGagnes',
            'couponsTermines',
            'totalCoupons',
            'totalAbonnes',
            'captures',
            'totalCaptures',
            'plansInvestissement',
        ));
    }
}