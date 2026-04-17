<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions_solde', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['credit', 'retrait']);
            $table->decimal('montant', 10, 2);
            $table->string('description')->nullable();  // ex: "Gain journalier 15/04/2025"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions_solde');
    }
};