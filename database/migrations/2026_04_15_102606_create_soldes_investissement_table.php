<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soldes_investissement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('solde', 10, 2)->default(0);          // solde disponible actuel
            $table->decimal('total_cumule', 10, 2)->default(0);   // total gagné depuis le début
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soldes_investissement');
    }
};