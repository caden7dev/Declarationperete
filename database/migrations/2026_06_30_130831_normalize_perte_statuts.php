<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class NormalizePerteStatuts extends Migration
{
    public function up()
    {
        // Remplacer les espaces par des underscores dans le champ 'statut'
        DB::statement("UPDATE pertes SET statut = REPLACE(statut, ' ', '_')");
    }

    public function down()
    {
        // Optionnel : remettre les espaces
        DB::statement("UPDATE pertes SET statut = REPLACE(statut, '_', ' ')");
    }
}