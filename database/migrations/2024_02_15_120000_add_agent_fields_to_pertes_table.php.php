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
            // Numéro de déclaration unique généré après validation
            $table->string('numero_declaration')->nullable()->after('statut');
            
            // Agent qui a validé/rejeté la déclaration
            $table->unsignedBigInteger('validated_by')->nullable()->after('numero_declaration');
            
            // Date de validation/rejet
            $table->timestamp('validated_at')->nullable()->after('validated_by');
            
            // Motif de rejet (si déclaration rejetée)
            $table->text('motif_rejet')->nullable()->after('validated_at');
            
            // Clé étrangère vers la table users (agent)
            $table->foreign('validated_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pertes', function (Blueprint $table) {
            // Supprimer la clé étrangère d'abord
            $table->dropForeign(['validated_by']);
            
            // Supprimer les colonnes
            $table->dropColumn([
                'numero_declaration',
                'validated_by',
                'validated_at',
                'motif_rejet',
            ]);
        });
    }
};
