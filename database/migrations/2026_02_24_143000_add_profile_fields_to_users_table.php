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
        Schema::table('users', function (Blueprint $table) {
            // Add profile fields if they don't exist
            if (!Schema::hasColumn('users', 'contact')) {
                $table->string('contact', 20)->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('users', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('contact');
            }
            
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address', 500)->nullable()->after('birth_date');
            }
            
            if (!Schema::hasColumn('users', 'nationality')) {
                $table->string('nationality', 100)->nullable()->default('Togolaise')->after('address');
            }
            
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['M', 'F'])->nullable()->after('nationality');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['contact', 'birth_date', 'address', 'nationality', 'gender']);
        });
    }
};
