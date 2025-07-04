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
            // Add index for location visibility queries
            $table->index(['is_visible_on_map', 'latitude', 'longitude'], 'users_location_visible_idx');
            
            // Add index for country-based queries
            $table->index('country_residence', 'users_country_residence_idx');
            
            // Add composite index for location queries
            $table->index(['latitude', 'longitude'], 'users_coordinates_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_location_visible_idx');
            $table->dropIndex('users_country_residence_idx');
            $table->dropIndex('users_coordinates_idx');
        });
    }
};