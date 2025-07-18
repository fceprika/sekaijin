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
            if (! Schema::hasColumn('users', 'name_slug')) {
                $table->string('name_slug')->nullable()->after('name');
            }
        });

        // Add index separately with try-catch
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->index('name_slug', 'idx_users_name_slug');
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore error
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropIndex('idx_users_name_slug');
            } catch (\Exception $e) {
                // Index might not exist, ignore error
            }

            if (Schema::hasColumn('users', 'name_slug')) {
                $table->dropColumn('name_slug');
            }
        });
    }
};
