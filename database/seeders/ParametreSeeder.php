<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parametre;

class ParametreSeeder extends Seeder
{
    public function run(): void
    {
        $parametres = [
            [
                'cle'     => 'whatsapp_numero',
                'valeur'  => '22600000000',
                'libelle' => 'Numéro WhatsApp admin (avec indicatif, sans +)',
            ],
            [
                'cle'     => 'whatsapp_message',
                'valeur'  => 'Bonjour, je viens de faire mon paiement pour un abonnement psport.',
                'libelle' => 'Message pré-rempli WhatsApp',
            ],
            [
                'cle'     => 'ussd_orange',
                'valeur'  => '*144#',
                'libelle' => 'Code USSD Orange Money',
            ],
            [
                'cle'     => 'ussd_moov',
                'valeur'  => '*555#',
                'libelle' => 'Code USSD Moov Money',
            ],
        ];

        foreach ($parametres as $p) {
            Parametre::updateOrCreate(['cle' => $p['cle']], $p);
        }
    }
}