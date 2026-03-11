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
        Schema::create('types_pieces', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Ex: Carte d'identité nationale
            $table->string('code', 50)->nullable()->unique(); // Ex: CIN
            $table->string('categorie', 100)->nullable(); // Ex: Identité, Véhicule, Académique
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('delai_traitement')->nullable(); // En jours
            $table->decimal('prix', 10, 2)->nullable()->default(0); // Prix en FCFA
            $table->text('documents_requis')->nullable(); // Liste des documents nécessaires
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('types_pieces');
    }
};