<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Les sélections sont optionnelles. L'admin peut créer un coupon
        // avec juste les codes bookmakers et une description, sans détailler
        // les matchs. S'il veut enrichir le coupon, il peut ajouter les matchs.
        Schema::create('selections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->string('equipe_domicile')->nullable();
            $table->string('equipe_exterieur')->nullable();
            $table->string('competition')->nullable()->comment('Ex: Ligue 1, Champions League');
            $table->timestamp('date_match')->nullable();
            $table->string('type_pari')->nullable()->comment('Ex: Victoire domicile, Plus de 2.5 buts');
            $table->decimal('cote', 5, 2)->nullable()->comment('Cote recommandee par l\'admin depuis 1xBet ou autre');
            $table->string('api_match_id')->nullable()->comment('ID API-Football pour maj automatique du score');
            $table->string('score_final')->nullable()->comment('Ex: 2-1, rempli par le cron job');
            $table->enum('statut', ['en_attente', 'en_cours', 'gagne', 'perdu', 'annule'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('selections');
    }
};