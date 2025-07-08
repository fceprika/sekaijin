<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add map-related columns
            $table->boolean('is_visible_on_map')->default(false)->after('is_verified');
            $table->decimal('latitude', 10, 8)->nullable()->after('is_visible_on_map');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('city_detected')->nullable()->after('longitude');
            
            // Add map performance index
            $table->index(['is_visible_on_map', 'latitude', 'longitude'], 'users_map_location_idx');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_map_location_idx');
            $table->dropColumn(['is_visible_on_map', 'latitude', 'longitude', 'city_detected']);
        });
    }
};