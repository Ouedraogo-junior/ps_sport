<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE abonnements MODIFY COLUMN plan VARCHAR(50) NOT NULL");
        DB::statement("ALTER TABLE access_codes MODIFY COLUMN plan VARCHAR(50) NOT NULL");
        DB::statement("ALTER TABLE paiements MODIFY COLUMN plan VARCHAR(50) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE abonnements MODIFY COLUMN plan ENUM('hebdomadaire','mensuel','premium') NOT NULL");
        DB::statement("ALTER TABLE access_codes MODIFY COLUMN plan ENUM('hebdomadaire','mensuel','premium') NOT NULL");
        DB::statement("ALTER TABLE paiements MODIFY COLUMN plan ENUM('hebdomadaire','mensuel','premium') NOT NULL");
    }
};