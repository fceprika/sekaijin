<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add map-related columns only if they don't exist
            if (!Schema::hasColumn('users', 'is_visible_on_map')) {
                $table->boolean('is_visible_on_map')->default(false)->after('is_verified');
            }
            if (!Schema::hasColumn('users', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('is_visible_on_map');
            }
            if (!Schema::hasColumn('users', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('users', 'city_detected')) {
                $table->string('city_detected')->nullable()->after('longitude');
            }
        });
        
        // Add index only if it doesn't exist
        if (!Schema::hasIndex('users', 'users_map_location_idx')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index(['is_visible_on_map', 'latitude', 'longitude'], 'users_map_location_idx');
            });
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_map_location_idx');
            $table->dropColumn(['is_visible_on_map', 'latitude', 'longitude', 'city_detected']);
        });
    }
};