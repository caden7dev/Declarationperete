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
        Schema::create('documents_trouves', function (Blueprint $table) {
            $table->id();
            
            // Informations du déclarant (celui qui a trouvé)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('nom_declarant');
            $table->string('prenom_declarant');
            $table->string('email_declarant');
            $table->string('telephone_declarant', 30);
            
            // Informations du document trouvé
            $table->string('type_document'); // CNI, Passeport, Permis, etc.
            $table->string('nom_sur_document')->nullable(); // Nom visible sur le document
            $table->string('prenom_sur_document')->nullable();
            $table->string('numero_document')->nullable(); // Si visible
            
            // Circonstances de la découverte
            $table->date('date_decouverte');
            $table->string('lieu_decouverte');
            $table->text('description')->nullable(); // Description du document
            $table->text('circonstances')->nullable(); // Comment il a été trouvé
            
            // Photos/fichiers
            $table->string('photo_document')->nullable(); // Photo du document (floutée si nécessaire)
            
            // Statut et matching
            $table->enum('statut', ['en_attente', 'matche', 'restitue', 'archive'])->default('en_attente');
            $table->foreignId('perte_matchee_id')->nullable()->constrained('pertes')->onDelete('set null');
            $table->timestamp('date_restitution')->nullable();
            
            // Numéro unique de déclaration
            $table->string('numero_declaration')->unique();
            
            // Notes administratives
            $table->text('notes_admin')->nullable();
            
            $table->timestamps();
            
            // Index pour recherche rapide
            $table->index('type_document');
            $table->index('statut');
            $table->index('date_decouverte');
            $table->index('lieu_decouverte');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_trouves');
    }
};
