<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            // Image illustrant les événements/matchs du jour du coupon.
            // Distincte de "capture_gagnant" qui sert de preuve de gain a posteriori.
            $table->string('image_evenements')->nullable()->after('analyse');
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('image_evenements');
        });
    }
};