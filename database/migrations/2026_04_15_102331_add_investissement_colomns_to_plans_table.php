<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->boolean('est_investissement')->default(false)->after('actif');
            $table->decimal('taux_journalier', 5, 2)->nullable()->after('est_investissement'); // % du prix du plan
            $table->integer('seuil_retrait')->nullable()->after('taux_journalier');             // montant min en XOF pour retrait
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['est_investissement', 'taux_journalier', 'seuil_retrait']);
        });
    }
};