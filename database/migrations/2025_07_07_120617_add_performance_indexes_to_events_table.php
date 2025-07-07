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
        Schema::table('events', function (Blueprint $table) {
            // Add performance indexes for frequently queried fields
            $table->index('slug', 'events_slug_index');
            $table->index('country_id', 'events_country_id_index');
            $table->index('start_date', 'events_start_date_index');
            $table->index(['country_id', 'start_date'], 'events_country_date_index');
            $table->index(['is_published', 'start_date'], 'events_published_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Remove performance indexes
            $table->dropIndex('events_slug_index');
            $table->dropIndex('events_country_id_index');
            $table->dropIndex('events_start_date_index');
            $table->dropIndex('events_country_date_index');
            $table->dropIndex('events_published_date_index');
        });
    }
};
