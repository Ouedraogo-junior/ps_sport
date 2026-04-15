<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('plan', ['hebdomadaire', 'mensuel', 'premium']);
            $table->decimal('montant', 10, 0)->comment('Montant en XOF');
            $table->enum('operateur', ['orange', 'moov']);
            $table->enum('statut', ['en_attente', 'valide', 'rejete'])->default('en_attente');
            // La capture est envoyée via WhatsApp, elle n'est pas uploadée sur le serveur.
            // L'admin coche simplement qu'il l'a bien vérifiée.
            $table->boolean('capture_verifiee')->default(false);
            $table->text('note_admin')->nullable()->comment('Observation de l\'admin');
            $table->text('motif_rejet')->nullable()->comment('Raison du rejet si applicable');
            $table->foreignId('traite_par')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('traite_le')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};