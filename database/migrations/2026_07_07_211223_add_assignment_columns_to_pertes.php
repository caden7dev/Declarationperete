<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Vérifier si la colonne assigned_to existe
        $result = DB::select("SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'pertes' AND COLUMN_NAME = 'assigned_to'");
        
        if ($result[0]->count == 0) {
            // Ajouter la colonne avec une requête SQL brute
            DB::statement("ALTER TABLE pertes ADD COLUMN assigned_to BIGINT UNSIGNED NULL");
            DB::statement("ALTER TABLE pertes ADD INDEX idx_assigned_to_is_locked (assigned_to, is_locked)");
            
            // Ajouter la contrainte de clé étrangère
            DB::statement("ALTER TABLE pertes ADD CONSTRAINT pertes_assigned_to_foreign FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL");
        }
    }

    public function down(): void
    {
        Schema::table('pertes', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropColumn('assigned_to');
        });
    }
};