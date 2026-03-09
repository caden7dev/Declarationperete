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
        Schema::table('pertes', function (Blueprint $table) {
            $table->boolean('document_retrouve')->default(false)->after('statut');
            $table->timestamp('date_retrouvaille')->nullable()->after('document_retrouve');
            $table->foreignId('document_trouve_id')->nullable()->constrained('documents_trouves')->onDelete('set null')->after('date_retrouvaille');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pertes', function (Blueprint $table) {
            $table->dropForeign(['document_trouve_id']);
            $table->dropColumn(['document_retrouve', 'date_retrouvaille', 'document_trouve_id']);
        });
    }
};
