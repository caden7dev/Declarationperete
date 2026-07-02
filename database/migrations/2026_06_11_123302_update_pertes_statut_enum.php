<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdatePertesStatutEnum extends Migration
{
    public function up()
    {
        // Inclure 'validee' dans la nouvelle liste pour ne pas perdre les anciennes données
        DB::statement("ALTER TABLE pertes MODIFY COLUMN statut ENUM('en_attente', 'en_cours', 'correspondance_trouvee', 'restitue', 'non_retrouve', 'validee', 'rejetee') DEFAULT 'en_attente'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE pertes MODIFY COLUMN statut ENUM('en_attente', 'validee', 'rejetee') DEFAULT 'en_attente'");
    }
}