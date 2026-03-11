<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('type_pieces', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Nom du type de pièce
            $table->text('description')->nullable(); // Description optionnelle
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('type_pieces');
    }
};