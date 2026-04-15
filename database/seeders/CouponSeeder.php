<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use App\Models\User;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            $this->command->error('Aucun admin trouvé.');
            return;
        }

        $coupons = [
            // ── GAGNÉS (85%) ─────────────────────────────────
            ['titre' => 'Double chance PSG - Lyon', 'description' => 'PSG ou nul, cote intéressante sur le match du soir.', 'niveau_risque' => 'faible', 'statut_resultat' => 'gagne'],
            ['titre' => 'Victoire Manchester City', 'description' => 'City à domicile contre une équipe en difficulté.', 'niveau_risque' => 'faible', 'statut_resultat' => 'gagne'],
            ['titre' => 'Real Madrid gagne + BTTS', 'description' => 'Madrid en forme, les deux équipes marquent.', 'niveau_risque' => 'modere', 'statut_resultat' => 'gagne'],
            ['titre' => 'Over 2.5 buts Bayern Munich', 'description' => 'Bayern prolifique à domicile cette saison.', 'niveau_risque' => 'faible', 'statut_resultat' => 'gagne'],
            ['titre' => 'Arsenal victoire et clean sheet', 'description' => 'Arsenal solide défensivement face à un promu.', 'niveau_risque' => 'modere', 'statut_resultat' => 'gagne'],
            ['titre' => 'Combo Liga — Barcelone + Atlético', 'description' => 'Deux favoris à domicile, cotes combinées attractives.', 'niveau_risque' => 'modere', 'statut_resultat' => 'gagne'],
            ['titre' => 'Inter Milan victoire Serie A', 'description' => 'Inter leader du championnat, match abordable.', 'niveau_risque' => 'faible', 'statut_resultat' => 'gagne'],
            ['titre' => 'Liverpool + Over 2.5', 'description' => 'Liverpool très offensif à Anfield.', 'niveau_risque' => 'faible', 'statut_resultat' => 'gagne'],
            ['titre' => 'Dortmund victoire Bundesliga', 'description' => 'Dortmund en confiance après 3 victoires de suite.', 'niveau_risque' => 'faible', 'statut_resultat' => 'gagne'],
            ['titre' => 'PSG victoire + Over 1.5', 'description' => 'PSG dominant en Ligue 1 cette saison.', 'niveau_risque' => 'faible', 'statut_resultat' => 'gagne'],
            ['titre' => 'Combo CL — City + Real', 'description' => 'Deux géants européens en phase de groupes.', 'niveau_risque' => 'modere', 'statut_resultat' => 'gagne'],
            ['titre' => 'Chelsea BTTS Domicile', 'description' => 'Chelsea et adversaire marquent, match ouvert.', 'niveau_risque' => 'modere', 'statut_resultat' => 'gagne'],
            ['titre' => 'Juventus victoire Serie A', 'description' => 'Juve solide à domicile contre un outsider.', 'niveau_risque' => 'faible', 'statut_resultat' => 'gagne'],
            ['titre' => 'Milan AC + Over 2.5', 'description' => 'Milan en forme offensive, adversaire fragile.', 'niveau_risque' => 'faible', 'statut_resultat' => 'gagne'],
            ['titre' => 'Atletico Madrid 1X', 'description' => 'Atletico ne perd pas à domicile en Liga.', 'niveau_risque' => 'faible', 'statut_resultat' => 'gagne'],
            ['titre' => 'Over 3.5 buts Bayern vs Leverkusen', 'description' => 'Choc offensif en Bundesliga, match spectaculaire attendu.', 'niveau_risque' => 'risque', 'statut_resultat' => 'gagne'],
            ['titre' => 'Napoli victoire Serie A', 'description' => 'Napoli solide en championnat cette saison.', 'niveau_risque' => 'faible', 'statut_resultat' => 'gagne'],

            // ── PERDUS (15%) ──────────────────────────────────
            ['titre' => 'Combo risqué 4 équipes', 'description' => 'Combiné ambitieux sur 4 matchs du week-end.', 'niveau_risque' => 'risque', 'statut_resultat' => 'perdu'],
            ['titre' => 'Upset — Upset Premier League', 'description' => 'Pari sur une victoire surprise d\'un outsider.', 'niveau_risque' => 'risque', 'statut_resultat' => 'perdu'],
            ['titre' => 'Over 4.5 buts Ligue 1', 'description' => 'Pari sur un score fleuve en Ligue 1.', 'niveau_risque' => 'risque', 'statut_resultat' => 'perdu'],
        ];

        foreach ($coupons as $index => $data) {
            Coupon::create([
                'titre'          => $data['titre'],
                'description'    => $data['description'],
                'niveau_risque'  => $data['niveau_risque'],
                'statut_publication' => 'publie',
                'statut_resultat'=> $data['statut_resultat'],
                'publie_le'      => now()->subDays(rand(1, 60)),
                'cree_par'       => $admin->id,
            ]);
        }

        $this->command->info('20 coupons créés — 17 gagnés (85%) / 3 perdus (15%).');
    }
}