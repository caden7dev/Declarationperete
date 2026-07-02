<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatutColumnInPertesTable extends Migration
{
    public function up()
    {
        Schema::table('pertes', function (Blueprint $table) {
            $table->string('statut', 50)->change();
        });
    }

    public function down()
    {
        Schema::table('pertes', function (Blueprint $table) {
            $table->string('statut', 20)->change();
        });
    }
}