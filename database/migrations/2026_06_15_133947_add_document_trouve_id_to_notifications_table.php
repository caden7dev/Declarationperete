<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentTrouveIdToNotificationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('notifications', 'document_trouve_id')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->unsignedBigInteger('document_trouve_id')->nullable()->after('perte_id');
                $table->foreign('document_trouve_id')->references('id')->on('documents_trouves')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('notifications', 'document_trouve_id')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropForeign(['document_trouve_id']);
                $table->dropColumn('document_trouve_id');
            });
        }
    }
}