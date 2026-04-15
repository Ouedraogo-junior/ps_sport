<?php
namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Models\Plan;
use SEO;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::publies()
            ->with(['codes', 'selections'])
            ->orderByDesc('publie_le')
            ->paginate(10);

        return view('coupons.index', compact('coupons'));
    }

    public function show(Coupon $coupon)
    {
        if (!$coupon->isPublie()) {
            abort(404);
        }

        $coupon->load(['codes', 'selections']);

        return view('coupons.show', compact('coupon'));
    }

    public function performances()
    {
        // Taux de réussite global
        $couponsTermines = Coupon::whereIn('statut_resultat', ['gagne', 'perdu'])->count();
        $couponsGagnes   = Coupon::where('statut_resultat', 'gagne')->count();
        $tauxReussite    = $couponsTermines > 0
            ? round(($couponsGagnes / $couponsTermines) * 100)
            : 0;

        // Taux 30 derniers jours
        $termines30j = Coupon::whereIn('statut_resultat', ['gagne', 'perdu'])
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();
        $gagnes30j = Coupon::where('statut_resultat', 'gagne')
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();
        $taux30j = $termines30j > 0 ? round(($gagnes30j / $termines30j) * 100) : 0;

        // Historique des coupons terminés (pagination)
        $historique = Coupon::whereIn('statut_resultat', ['gagne', 'perdu'])
            ->latest()
            ->paginate(15);

        // Captures coupons gagnants
        $captures = Coupon::whereNotNull('capture_gagnant')
                        ->where('statut_resultat', 'gagne')
                        ->orderByDesc('publie_le')
                        ->limit(5)
                        ->get();

        $totalCaptures = Coupon::whereNotNull('capture_gagnant')
                            ->where('statut_resultat', 'gagne')
                            ->count();

        // SEO
        SEO::setTitle('Performances & Historique — ' . config('app.name'));
        SEO::setDescription('Historique complet de nos pronostics sportifs. Taux de réussite transparent et vérifiable.');
        SEO::opengraph()->setUrl(route('performances'));

        return view('performances', compact(
            'tauxReussite',
            'couponsGagnes',
            'couponsTermines',
            'taux30j',
            'gagnes30j',
            'termines30j',
            'historique',
            'captures',
            'totalCaptures',
        ));
    }

    public function captures()
    {
        $captures = Coupon::whereNotNull('capture_gagnant')
                        ->where('statut_resultat', 'gagne')
                        ->orderByDesc('publie_le')
                        ->paginate(20);

        SEO::setTitle('Captures gagnantes — ' . config('app.name'));

        return view('performances-captures', compact('captures'));
    }
    
}