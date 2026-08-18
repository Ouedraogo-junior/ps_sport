<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_bonuses', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['par_filleul', 'palier']);
            $table->integer('seuil');       // pour par_filleul : 1 / pour palier : 10, 25, 50...
            $table->decimal('montant', 10, 2);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_bonuses');
    }
};