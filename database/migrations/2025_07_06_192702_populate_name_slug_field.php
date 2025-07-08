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
        // Populate name_slug for existing users where it's NULL or empty
        DB::statement('UPDATE users SET name_slug = LOWER(name) WHERE name_slug IS NULL OR name_slug = ""');
        
        // Make name_slug NOT NULL after populating
        Schema::table('users', function (Blueprint $table) {
            $table->string('name_slug')->nullable(false)->change();
        });
        
        // Add unique constraint separately with try-catch
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('name_slug', 'idx_users_name_slug_unique');
            });
        } catch (\Exception $e) {
            // Unique constraint might already exist, ignore error
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropUnique('idx_users_name_slug_unique');
            } catch (\Exception $e) {
                // Unique constraint might not exist, ignore error
            }
            $table->string('name_slug')->nullable()->change();
        });
        
        // Clear name_slug values only if they exist
        DB::statement('UPDATE users SET name_slug = NULL WHERE name_slug IS NOT NULL');
    }
};
