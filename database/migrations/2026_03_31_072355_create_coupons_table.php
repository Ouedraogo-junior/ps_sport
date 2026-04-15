<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('titre')->comment('Ex: Coupon du jour #12');
            $table->enum('niveau_risque', ['faible', 'modere', 'risque']);
            // Pas de restriction par plan pour le moment.
            // Tous les abonnés actifs ont accès à tous les coupons.
            // Le champ plan_requis pourra être ajouté via une migration
            // ultérieure si le client décide d'ajouter des restrictions.
            $table->text('description')->nullable()->comment('Description libre du coupon par l\'admin');
            $table->text('analyse')->nullable()->comment('Analyse optionnelle');
            $table->enum('statut_publication', ['brouillon', 'publie', 'depublie'])->default('brouillon');
            $table->enum('statut_resultat', ['en_attente', 'en_cours', 'gagne', 'perdu', 'annule'])->default('en_attente');
            $table->foreignId('cree_par')->constrained('users')->onDelete('cascade');
            $table->timestamp('publie_le')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};