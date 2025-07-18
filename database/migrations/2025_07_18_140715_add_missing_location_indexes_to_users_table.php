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
            // Add index for location visibility queries (columns exist now)
            if (Schema::hasColumn('users', 'is_visible_on_map')) {
                $table->index(['is_visible_on_map', 'latitude', 'longitude'], 'users_location_visible_idx');
            }

            // Add composite index for location queries
            if (Schema::hasColumn('users', 'latitude')) {
                $table->index(['latitude', 'longitude'], 'users_coordinates_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_visible_on_map')) {
                $table->dropIndex('users_location_visible_idx');
            }
            if (Schema::hasColumn('users', 'latitude')) {
                $table->dropIndex('users_coordinates_idx');
            }
        });
    }
};
