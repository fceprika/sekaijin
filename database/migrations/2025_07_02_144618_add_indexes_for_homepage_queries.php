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
        Schema::table('news', function (Blueprint $table) {
            $table->index(['country_id', 'created_at'], 'news_country_created_idx');
        });
        
        Schema::table('articles', function (Blueprint $table) {
            $table->index(['country_id', 'created_at'], 'articles_country_created_idx');
        });
        
        Schema::table('events', function (Blueprint $table) {
            $table->index(['country_id', 'start_date'], 'events_country_start_date_idx');
        });
        
        Schema::table('countries', function (Blueprint $table) {
            $table->index('slug', 'countries_slug_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex('news_country_created_idx');
        });
        
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex('articles_country_created_idx');
        });
        
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('events_country_start_date_idx');
        });
        
        Schema::table('countries', function (Blueprint $table) {
            $table->dropIndex('countries_slug_idx');
        });
    }
};
