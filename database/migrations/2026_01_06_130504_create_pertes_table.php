<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePertesTable extends Migration
{
    public function up()
    {
        Schema::create('pertes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            
            // Déclarant
            $table->string('last_name');
            $table->string('first_name');
            $table->string('contact')->nullable();
            $table->string('email');

            // Pièce
            $table->string('type_piece');
            $table->string('numero_piece')->nullable();
            $table->date('date_delivrance')->nullable();
            $table->string('autorite_delivrance')->nullable();

            // Perte
            $table->date('date_perte');
            $table->string('lieu_perte');
            $table->text('circonstances')->nullable();

            

            // Statut et dates
            $table->string('statut')->default('en attente');
            $table->dateTime('date_declaration')->nullable();
            $table->dateTime('date_traitement')->nullable();

            // Fichiers
            $table->string('copie_piece')->nullable();
            $table->string('declaration_vol')->nullable();
            $table->string('document_complementaire')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pertes');
    }
}
