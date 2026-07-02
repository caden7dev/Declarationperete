<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatePassageNonRetrouveToPertesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('pertes', function (Blueprint $table) {
        $table->timestamp('date_passage_non_retrouve')->nullable()->after('date_perte');
    });
}
public function down()
{
    Schema::table('pertes', function (Blueprint $table) {
        $table->dropColumn('date_passage_non_retrouve');
    });
}
}
