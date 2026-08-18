<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parrain_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('filleul_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('abonnement_id')->constrained()->onDelete('cascade');
            $table->decimal('bonus_filleul', 10, 2)->default(0);  // bonus par filleul crédité
            $table->decimal('bonus_palier', 10, 2)->default(0);   // bonus palier crédité ce jour (0 si aucun palier franchi)
            $table->integer('palier_atteint')->nullable();         // ex: 10, 25, 50
            $table->enum('statut', ['en_attente', 'credite'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};