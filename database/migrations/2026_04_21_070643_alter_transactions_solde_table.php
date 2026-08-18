<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE transactions_solde 
            MODIFY COLUMN type ENUM(
                'credit',
                'retrait',
                'credit_affiliation',
                'bonus_palier'
            ) NOT NULL"
        );
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transactions_solde 
            MODIFY COLUMN type ENUM('credit', 'retrait') NOT NULL"
        );
    }
};