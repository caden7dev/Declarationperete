<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdatePerteStructureComplete extends Migration
{
    public function up()
    {
        // 1. Ajout document_trouve_id à pertes
        if (!Schema::hasColumn('pertes', 'document_trouve_id')) {
            Schema::table('pertes', function (Blueprint $table) {
                $table->unsignedBigInteger('document_trouve_id')->nullable()->after('user_id');
                $table->foreign('document_trouve_id')->references('id')->on('documents_trouves')->onDelete('set null');
            });
        }

        // 2. Ajout date_restitution à pertes
        if (!Schema::hasColumn('pertes', 'date_restitution')) {
            Schema::table('pertes', function (Blueprint $table) {
                $table->timestamp('date_restitution')->nullable()->after('updated_at');
            });
        }

        // 3. Ajout perte_matchee_id à documents_trouves
        if (!Schema::hasColumn('documents_trouves', 'perte_matchee_id')) {
            Schema::table('documents_trouves', function (Blueprint $table) {
                $table->unsignedBigInteger('perte_matchee_id')->nullable()->after('statut');
                $table->foreign('perte_matchee_id')->references('id')->on('pertes')->onDelete('set null');
            });
        }

        // 4. Modification du statut (conserve validee pour compatibilité)
        DB::statement("ALTER TABLE pertes MODIFY COLUMN statut ENUM('en_attente', 'en_cours', 'correspondance_trouvee', 'restitue', 'non_retrouve', 'validee', 'rejetee') DEFAULT 'en_attente'");
    }

    public function down()
    {
        // Annulation des modifications
        if (Schema::hasColumn('pertes', 'document_trouve_id')) {
            Schema::table('pertes', function (Blueprint $table) {
                $table->dropForeign(['document_trouve_id']);
                $table->dropColumn('document_trouve_id');
            });
        }
        if (Schema::hasColumn('pertes', 'date_restitution')) {
            Schema::table('pertes', function (Blueprint $table) {
                $table->dropColumn('date_restitution');
            });
        }
        if (Schema::hasColumn('documents_trouves', 'perte_matchee_id')) {
            Schema::table('documents_trouves', function (Blueprint $table) {
                $table->dropForeign(['perte_matchee_id']);
                $table->dropColumn('perte_matchee_id');
            });
        }
        DB::statement("ALTER TABLE pertes MODIFY COLUMN statut ENUM('en_attente', 'validee', 'rejetee') DEFAULT 'en_attente'");
    }
}