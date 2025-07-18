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
            // Add index for country-based queries (this column exists)
            $table->index('country_residence', 'users_country_residence_idx');

            // Skip location indexes as columns don't exist yet
            // They will be added in a later migration after the columns are created
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_country_residence_idx');
        });
    }
};
