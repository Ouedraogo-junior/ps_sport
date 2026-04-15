<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Un coupon peut avoir un code différent par bookmaker.
        // Ex: code 1xBet = "XBT123", code BetWinner = "BW456" pour le même coupon.
        // L'utilisateur voit le code du bookmaker où il veut parier.
        Schema::create('coupon_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->string('bookmaker')->comment('Ex: 1xbet, betwinner, melbet');
            $table->string('code', 100)->comment('Code coupon fourni par le bookmaker');
            $table->timestamps();

            // Un seul code par bookmaker par coupon
            $table->unique(['coupon_id', 'bookmaker']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_codes');
    }
};