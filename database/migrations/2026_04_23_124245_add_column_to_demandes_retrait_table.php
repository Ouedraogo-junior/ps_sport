<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('demandes_retrait', function (Blueprint $table) {
            $table->enum('source', ['investissement', 'affiliation'])->default('investissement')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes_retrait', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
