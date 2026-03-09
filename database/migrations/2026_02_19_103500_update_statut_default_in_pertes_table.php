<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatutDefaultInPertesTable extends Migration
{
    public function up()
    {
        Schema::table('pertes', function (Blueprint $table) {
            // Modifier la colonne pour utiliser 'en_attente' avec underscore
            $table->string('statut')->default('en_attente')->change();
        });
        
        // Optionnel : Mettre à jour les enregistrements existants
        DB::table('pertes')->where('statut', 'en attente')->update(['statut' => 'en_attente']);
    }

    public function down()
    {
        Schema::table('pertes', function (Blueprint $table) {
            $table->string('statut')->default('en attente')->change();
        });
        
        DB::table('pertes')->where('statut', 'en_attente')->update(['statut' => 'en attente']);
    }
}