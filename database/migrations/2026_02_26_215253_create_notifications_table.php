<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Destinataire
            $table->foreignId('from_user_id')->nullable()->constrained('users')->onDelete('set null'); // Expéditeur (agent)
            $table->foreignId('perte_id')->nullable()->constrained()->onDelete('cascade'); // Déclaration concernée
            
            $table->string('type'); // 'validation', 'rejet', 'message', 'info'
            $table->string('title');
            $table->text('content');
            $table->string('action_url')->nullable(); // Lien vers la déclaration concernée
            $table->string('icon')->default('🔔');
            $table->string('color')->nullable(); // 'success', 'warning', 'danger', 'info'
            
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // Expiration de la notification
            
            $table->timestamps();
            
            // Index pour optimiser les recherches
            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};