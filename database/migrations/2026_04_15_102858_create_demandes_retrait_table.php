<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes_retrait', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->enum('operateur', ['orange', 'moov', 'wave']);
            $table->string('numero_telephone');
            $table->enum('statut', ['en_attente', 'valide', 'rejete'])->default('en_attente');
            $table->foreignId('traite_par')->nullable()->constrained('users')->nullOnDelete();
            $table->string('note_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes_retrait');
    }
};