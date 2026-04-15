<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use SEO;

class CalendrierController extends Controller
{
    const LEAGUES = [
        2   => 'Ligue des Champions',
        39  => 'Premier League',
        140 => 'La Liga',
        61  => 'Ligue 1',
        78  => 'Bundesliga',
        135 => 'Serie A',
        6   => 'CAN',
    ];

    public function index()
    {
        $dates = [
            now()->format('Y-m-d'),
            now()->addDay()->format('Y-m-d'),
        ];

        $matchs = collect();

        foreach ($dates as $date) {
            foreach (array_keys(self::LEAGUES) as $leagueId) {

                $cacheKey = "calendrier_{$leagueId}_{$date}";

                $fixtures = Cache::remember($cacheKey, 3600, function () use ($leagueId, $date) {
                    try {
                        $response = Http::withHeaders([
                            'x-apisports-key' => config('services.rapidapi.key'),
                        ])->get('https://v3.football.api-sports.io/fixtures', [
                            'league' => $leagueId,
                            'date'   => $date,
                            'season' => now()->month >= 7 ? now()->year : now()->year - 1,
                        ]);

                        if ($response->successful()) {
                            return $response->json('response') ?? [];
                        }

                        return [];
                    } catch (\Exception $e) {
                        Log::error("CalendrierController: {$e->getMessage()}");
                        return [];
                    }
                });

                foreach ($fixtures as $fixture) {
                    $matchs->push([
                        'date'         => $fixture['fixture']['date'] ?? null,
                        'competition'  => self::LEAGUES[$leagueId],
                        'league_logo'  => $fixture['league']['logo'] ?? null,
                        'domicile'     => $fixture['teams']['home']['name'] ?? '—',
                        'domicile_logo'=> $fixture['teams']['home']['logo'] ?? null,
                        'exterieur'    => $fixture['teams']['away']['name'] ?? '—',
                        'exterieur_logo'=> $fixture['teams']['away']['logo'] ?? null,
                        'statut'       => $fixture['fixture']['status']['short'] ?? '—',
                        'statut_long'  => $fixture['fixture']['status']['long'] ?? '—',
                        'score_dom'    => $fixture['goals']['home'],
                        'score_ext'    => $fixture['goals']['away'],
                    ]);
                }
            }
        }

        // Trier par date
        $matchs = $matchs->sortBy('date')->groupBy(function ($m) {
            return \Carbon\Carbon::parse($m['date'])->format('Y-m-d');
        });

        // SEO
        SEO::setTitle('Calendrier des Matchs — ' . config('app.name'));
        SEO::setDescription('Matchs du jour et de demain — Ligue des Champions, Premier League, La Liga, Ligue 1 et plus.');
        SEO::opengraph()->setUrl(route('calendrier'));

        return view('calendrier', compact('matchs', 'dates'));
    }


    public function performances(Request $request)
    {
        $mois = $request->get('mois');
        try {
            $debut = \Carbon\Carbon::createFromFormat('Y-m', $mois)->startOfMonth();
        } catch (\Exception $e) {
            $debut = now()->startOfMonth();
        }

        $fin = $debut->copy()->endOfMonth();

        $coupons = \App\Models\Coupon::where('statut_publication', 'publie')
                    ->whereNotNull('publie_le')
                    ->whereBetween('publie_le', [$debut, $fin])
                    ->orderBy('publie_le')
                    ->get()
                    ->groupBy(fn($c) => $c->publie_le->format('Y-m-d'));

        $moisPrecedent = $debut->copy()->subMonth()->format('Y-m');
        $moisSuivant   = $debut->copy()->addMonth()->format('Y-m');
        $moisCourant   = now()->format('Y-m');

        SEO::setTitle('Calendrier des performances — ' . config('app.name'));
        SEO::setDescription('Résultats de nos coupons jour par jour.');
        SEO::opengraph()->setUrl(route('calendrier.performances'));

        return view('calendrier-performances', compact(
            'coupons', 'debut', 'moisPrecedent', 'moisSuivant', 'moisCourant'
        ));
    }
}