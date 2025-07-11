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
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable()->constrained()->onDelete('cascade')->after('user_id');
            
            // Index pour améliorer les performances des requêtes par pays
            $table->index(['country_id', 'status', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropIndex(['country_id', 'status', 'type']);
            $table->dropForeign(['country_id']);
            $table->dropColumn('country_id');
        });
    }
};
