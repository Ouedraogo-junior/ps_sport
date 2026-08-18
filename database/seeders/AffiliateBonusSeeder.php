<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AffiliateBonus;

class AffiliateBonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AffiliateBonus::insert([
            // Bonus par filleul
            ['type' => 'par_filleul', 'seuil' => 1, 'montant' => 500, 'actif' => true],

            // Paliers
            ['type' => 'palier', 'seuil' => 10,  'montant' => 2000,  'actif' => true],
            ['type' => 'palier', 'seuil' => 25,  'montant' => 5000,  'actif' => true],
            ['type' => 'palier', 'seuil' => 50,  'montant' => 15000, 'actif' => true],
        ]);
    }
}
