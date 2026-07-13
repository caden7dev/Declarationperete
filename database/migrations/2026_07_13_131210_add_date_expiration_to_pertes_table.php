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
        Schema::table('pertes', function (Blueprint $table) {
            // ✅ Ajout de la date d'expiration
            $table->date('date_expiration')->nullable()->after('date_delivrance');
            
            // ✅ Ajout du statut de vérification (auto/manuelle)
            $table->enum('statut_verification', ['auto', 'manuelle'])
                  ->default('auto')
                  ->after('statut');
            
            // ✅ Ajout des champs pour la vérification manuelle
            $table->foreignId('verified_by')
                  ->nullable()
                  ->after('validated_by')
                  ->constrained('users')
                  ->nullOnDelete();
            
            $table->timestamp('verified_at')
                  ->nullable()
                  ->after('validated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pertes', function (Blueprint $table) {
            // Supprimer les contraintes de clé étrangère d'abord
            $table->dropForeign(['verified_by']);
            
            // Supprimer les colonnes
            $table->dropColumn([
                'date_expiration',
                'statut_verification',
                'verified_by',
                'verified_at'
            ]);
        });
    }
};