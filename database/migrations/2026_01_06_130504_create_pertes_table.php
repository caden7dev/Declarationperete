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
        Schema::create('pertes', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('type_piece_id')->nullable()->constrained('types_pieces')->nullOnDelete();
            
            // Informations du déclarant (de votre version)
            $table->string('last_name');
            $table->string('first_name');
            $table->string('contact')->nullable();
            $table->string('email');
            
            // Informations sur la pièce originale (version locale)
            $table->string('type_piece')->nullable(); // Gardé pour compatibilité
            $table->string('numero_piece')->nullable();
            $table->date('date_delivrance')->nullable();
            $table->string('autorite_delivrance')->nullable();
            
            // Informations sur la perte (combiné)
            $table->date('date_perte');
            $table->string('lieu_perte');
            $table->text('circonstances')->nullable(); // Version locale
            $table->text('description')->nullable();    // Version distante (similaire)
            
            // Statut et suivi (version distante améliorée)
            $table->enum('statut', ['en_attente', 'validee', 'rejetee'])->default('en_attente');
            $table->string('numero_declaration')->unique()->nullable();
            $table->text('motif_rejet')->nullable();
            
            // Dates importantes (combiné)
            $table->dateTime('date_declaration')->nullable();
            $table->dateTime('date_traitement')->nullable();
            $table->timestamp('validated_at')->nullable();
            
            // Documents (version locale)
            $table->string('copie_piece')->nullable();
            $table->string('declaration_vol')->nullable();
            $table->string('document_complementaire')->nullable();
            
            // Validation par agent (version distante)
            $table->foreignId('validated_by')->nullable()->constrained('users');
            
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('statut');
            $table->index('date_perte');
            $table->index('user_id');
            $table->index('numero_declaration');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertes');
    }
};