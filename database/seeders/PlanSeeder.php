<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('plans')->insert([
            [
                'nom'         => 'Hebdomadaire',
                'slug'        => 'hebdomadaire',
                'prix'        => 1000,
                'duree_jours' => 7,
                'actif'       => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nom'         => 'Mensuel',
                'slug'        => 'mensuel',
                'prix'        => 3000,
                'duree_jours' => 30,
                'actif'       => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nom'         => 'Premium',
                'slug'        => 'premium',
                'prix'        => 5000,
                'duree_jours' => 30,
                'actif'       => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}