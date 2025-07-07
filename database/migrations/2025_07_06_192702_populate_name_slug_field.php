<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Populate name_slug for existing users
        DB::statement('UPDATE users SET name_slug = LOWER(name) WHERE name_slug IS NULL');
        
        // Make name_slug NOT NULL after populating
        Schema::table('users', function (Blueprint $table) {
            $table->string('name_slug')->nullable(false)->change();
            $table->unique('name_slug', 'idx_users_name_slug_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('idx_users_name_slug_unique');
            $table->string('name_slug')->nullable()->change();
        });
        
        // Clear name_slug values only if they exist
        DB::statement('UPDATE users SET name_slug = NULL WHERE name_slug IS NOT NULL');
    }
};
