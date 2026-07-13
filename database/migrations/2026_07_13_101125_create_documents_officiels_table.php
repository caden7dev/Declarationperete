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
        Schema::create('documents_officiels', function (Blueprint $table) {
            $table->id();
            $table->string('type_piece', 100);
            $table->string('numero_document', 100)->unique();
            $table->string('nom_complet', 255)->nullable();
            $table->string('nom', 255)->nullable();
            $table->string('prenom', 255)->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance', 255)->nullable();
            $table->date('date_delivrance')->nullable();
            $table->date('date_expiration')->nullable();
            $table->string('autorite_delivrance', 255)->nullable();
            $table->string('lieu_delivrance', 255)->nullable();
            $table->boolean('est_valide')->default(true);
            $table->boolean('est_volé')->default(false);
            $table->boolean('est_perdu')->default(false);
            $table->boolean('est_suspendu')->default(false);
            $table->text('remarques')->nullable();
            $table->string('photo_url')->nullable();
            $table->timestamp('derniere_verification')->nullable();
            $table->string('source')->default('import');
            $table->timestamps();
            
            $table->index('type_piece');
            $table->index('numero_document');
            $table->index('est_valide');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_officiels');
    }
};