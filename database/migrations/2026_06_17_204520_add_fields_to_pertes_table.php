<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPertesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('pertes', function (Blueprint $table) {
        $table->string('pdf_recu')->nullable()->after('date_restitution');
        $table->string('lieu_recuperation')->nullable()->after('pdf_recu');
        $table->timestamp('date_preparation')->nullable()->after('lieu_recuperation');
        $table->timestamp('date_recuperation')->nullable()->after('date_preparation');
    });
}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pertes', function (Blueprint $table) {
            //
        });
    }
}
